<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Events\Registered;
use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\UserResource;
use App\Models\User;
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
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'device_name' => ['nullable', 'string', 'max:100'],
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
            'provider'    => ['required', 'string', 'in:google,apple,facebook'],
            'provider_id' => ['required', 'string'],
            'email'       => ['required', 'email'],
            'firstname'   => ['nullable', 'string', 'max:50'],
            'lastname'    => ['nullable', 'string', 'max:50'],
            'avatar'      => ['nullable', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $email = strtolower($request->email);
        $user = User::where('email', $email)->first();

        if (!$user) {
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
                'firstname' => $request->firstname ?: 'User',
                'lastname'  => $request->lastname ?: '',
                'username'  => $username,
                'email'     => $email,
                'password'  => Hash::make(Str::random(24)),
                'avatar'    => $request->avatar,
            ]);

            try {
                $user->addCountryBadge();
            } catch (\Throwable $th) {}
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

