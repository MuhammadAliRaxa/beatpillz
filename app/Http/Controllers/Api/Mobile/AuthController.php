<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Events\Registered;
use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\UserResource;
use App\Models\User;
use App\Services\AppleTokenVerifier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname'   => ['required', 'string', 'max:50'],
            'lastname'    => ['required', 'string', 'max:50'],
            'username'    => ['required', 'string', 'min:6', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email'       => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'device_name'   => ['nullable', 'string', 'max:100'],
            'ref'           => ['nullable', 'string', 'max:50'],
            'referral_code' => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'username'  => strtolower($request->username),
            'email'     => strtolower($request->email),
            'password'  => Hash::make($request->password),
            'status'    => User::STATUS_ACTIVE,
        ]);

        try {
            $user->addCountryBadge();
        } catch (\Throwable $th) {}

        try {
            $user->registerLoginLog();
        } catch (\Throwable $th) {}

        try {
            event(new Registered($user));
        } catch (\Throwable $th) {}

        try {
            if (function_exists('adminNotify')) {
                $title = translate(':username has registered', ['username' => $user->getName()]);
                $image = $user->getAvatar();
                $link = route('admin.members.users.edit', $user->id);
                adminNotify($title, $image, $link);
            }
        } catch (\Throwable $th) {}

        if (function_exists('isAddonActive') && isAddonActive('newsletter') && @settings('newsletter')->register_new_users) {
            if (function_exists('registerForNewsletter')) {
                try {
                    registerForNewsletter($user->email);
                } catch (\Throwable $th) {}
            }
        }

        $deviceName = $request->device_name ?? 'Mobile Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Registration successful.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new UserResource($user),
        ], 201);
    }

    /**
     * Authenticate user and return access token.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login'       => ['required', 'string'],
            'password'    => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $loginInput)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email/username or password.',
            ], 401);
        }

        if ($user->isBanned()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is blocked. Please contact support.',
            ], 403);
        }

        // Check if 2FA Authentication is enabled
        if ($user->google2fa_status) {
            $twoFactorToken = encrypt([
                'user_id'    => $user->id,
                'expires_at' => now()->addMinutes(10)->timestamp,
            ]);

            return response()->json([
                'success'          => true,
                'requires_2fa'     => true,
                'two_factor_token' => $twoFactorToken,
                'message'          => '2FA authentication code required.',
            ], 200);
        }

        try {
            $user->registerLoginLog();
        } catch (\Throwable $th) {}

        $deviceName = $request->device_name ?? 'Mobile Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Login successful.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new UserResource($user),
        ], 200);
    }

    /**
     * Verify 2FA OTP code and complete login authentication.
     */
    public function verify2fa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'two_factor_token' => ['required', 'string'],
            'otp_code'         => ['required', 'numeric'],
            'device_name'      => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $payload = decrypt($request->input('two_factor_token'));
            if (!is_array($payload) || !isset($payload['user_id']) || !isset($payload['expires_at'])) {
                throw new Exception('Invalid two factor session token.');
            }

            if (now()->timestamp > $payload['expires_at']) {
                return response()->json([
                    'success' => false,
                    'message' => '2FA session expired. Please log in again.',
                ], 401);
            }

            $user = User::find($payload['user_id']);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User account not found.',
                ], 404);
            }

            if ($user->isBanned()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is blocked. Please contact support.',
                ], 403);
            }

            $google2fa = app('pragmarx.google2fa');
            $valid = $google2fa->verifyKey($user->google2fa_secret, $request->otp_code);

            if (!$valid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP code. Please check your Authenticator app and try again.',
                ], 422);
            }

            try {
                $user->registerLoginLog();
            } catch (\Throwable $th) {}

            $deviceName = $request->device_name ?? 'Mobile Device';
            $token = $user->createToken($deviceName)->plainTextToken;

            return response()->json([
                'success'      => true,
                'message'      => 'Login successful.',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => new UserResource($user),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired 2FA session token. Please log in again.',
            ], 401);
        }
    }

    /**
     * Send password reset link to email.
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Password::broker()->createToken($user);
            try {
                $user->sendPasswordResetNotification($token);
            } catch (\Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send password reset email. Please try again.',
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset instructions have been sent to your email address.',
        ], 200);
    }

    /**
     * Revoke current mobile device token.
     */
    public function logout(Request $request)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }

    /**
     * Mobile Social Login (Google, Apple, Facebook).
     */
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider'       => ['required', 'string', 'in:google,apple,facebook'],
            'provider_id'    => ['required_without:identity_token', 'nullable', 'string'],
            'identity_token' => ['nullable', 'string'],
            'email'          => [in_array($request->provider, ['apple', 'google']) ? 'nullable' : 'required', 'nullable', 'email'],
            'firstname'      => ['nullable', 'string', 'max:50'],
            'lastname'       => ['nullable', 'string', 'max:50'],
            'avatar'         => ['nullable', 'string'],
            'device_name'    => ['nullable', 'string', 'max:100'],
            'ref'            => ['nullable', 'string', 'max:50'],
            'referral_code'  => ['nullable', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $email = $request->email ? strtolower($request->email) : null;
        $provider = $request->provider;
        $providerId = $request->provider_id;

        // Cryptographically verify Apple Identity Token (JWT) against Apple JWKS public keys
        if ($provider === 'apple') {
            if ($request->filled('identity_token')) {
                try {
                    $appleClaims = AppleTokenVerifier::verify($request->identity_token);
                    $providerId = $appleClaims->sub;
                    if (empty($email) && !empty($appleClaims->email)) {
                        $email = strtolower($appleClaims->email);
                    }
                } catch (\Throwable $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Apple authentication failed: ' . $e->getMessage(),
                    ], 401);
                }
            } elseif (app()->environment('production') && empty($providerId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The identity_token is required for Sign in with Apple in production.',
                ], 422);
            }
        }

        $user = null;
        if ($provider === 'facebook') {
            $user = User::where('facebook_id', $providerId)->first();
        } elseif ($provider === 'google') {
            $user = User::where('google_id', $providerId)->first();
        } elseif ($provider === 'apple') {
            $user = User::where('apple_id', $providerId)->first();
        }

        if (!$user && !empty($email)) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            if (empty($email)) {
                if (in_array($provider, ['apple', 'google'])) {
                    $email = $provider === 'apple'
                        ? ($providerId . '@privaterelay.appleid.com')
                        : ($provider . '_' . substr(preg_replace('/[^A-Za-z0-9]/', '', $providerId), -8) . '@beatpillz.com');
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email address is required for first-time account registration.',
                    ], 422);
                }
            }

            // Generate unique username from email
            $baseUsername = strtolower(explode('@', $email)[0]);
            $baseUsername = preg_replace('/[^A-Za-z0-9_]/', '', $baseUsername);
            if (strlen($baseUsername) < 6) {
                $baseUsername = $baseUsername . '_' . rand(100, 999);
            }
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $user = User::create([
                'firstname'    => $request->firstname ?: 'User',
                'lastname'     => $request->lastname ?: '',
                'username'     => $username,
                'email'        => $email,
                'password'     => Hash::make(Str::random(24)),
                'avatar'       => $request->avatar,
                'status'       => User::STATUS_ACTIVE,
                'facebook_id'  => $provider === 'facebook' ? $providerId : null,
                'google_id'    => $provider === 'google' ? $providerId : null,
                'apple_id'     => $provider === 'apple' ? $providerId : null,
            ]);

            // Mark email as verified for social sign-in so NO verification email is sent
            $user->forceFill(['email_verified_at' => now()])->save();

            try {
                $user->addCountryBadge();
            } catch (\Throwable $th) {}

            try {
                event(new Registered($user));
            } catch (\Throwable $th) {}
        } else {
            // Ensure email is marked verified for social sign-in so no verification email is sent
            if (!$user->hasVerifiedEmail()) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            // If user's status is null, set it to active
            if ($user->status === null) {
                $user->status = User::STATUS_ACTIVE;
                $user->save();
            }

            // Link provider ID if not yet linked
            if ($provider === 'facebook' && empty($user->facebook_id)) {
                $user->facebook_id = $providerId;
                $user->save();
            } elseif ($provider === 'google' && empty($user->google_id)) {
                $user->google_id = $providerId;
                $user->save();
            } elseif ($provider === 'apple' && empty($user->apple_id)) {
                $user->apple_id = $providerId;
                $user->save();
            }
        }

        if ($user->isBanned()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is blocked.',
            ], 403);
        }

        try {
            $user->registerLoginLog();
        } catch (\Throwable $th) {}

        $deviceName = $request->device_name ?? 'Mobile Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Social login successful.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => new UserResource($user),
        ], 200);
    }
}

