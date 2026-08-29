<?php

namespace App\Http\Controllers\Api;

use App\Events\Registered;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new user via API (Mobile).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
            'message'      => 'Registration successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'         => $user->id,
                'firstname'  => $user->firstname,
                'lastname'   => $user->lastname,
                'username'   => $user->username,
                'email'      => $user->email,
                'avatar'     => $user->avatar ? asset($user->avatar) : null,
                'is_author'  => (bool) $user->is_author,
                'balance'    => $user->balance,
                'created_at' => $user->created_at,
            ],
        ], 201);
    }

    /**
     * Authenticate user and issue Sanctum token (Mobile).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login'       => ['required', 'string'], // email or username
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
        } catch (\Throwable $th) {
            // Silently handle login log in case of API/CLI requests
        }

        $deviceName = $request->device_name ?? 'Mobile Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'         => $user->id,
                'firstname'  => $user->firstname,
                'lastname'   => $user->lastname,
                'username'   => $user->username,
                'email'      => $user->email,
                'avatar'     => $user->avatar ? asset($user->avatar) : null,
                'is_author'  => (bool) $user->is_author,
                'balance'    => $user->balance,
                'created_at' => $user->created_at,
            ],
        ], 200);
    }

    /**
     * Get authenticated user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'user'    => [
                'id'                   => $user->id,
                'firstname'            => $user->firstname,
                'lastname'             => $user->lastname,
                'username'             => $user->username,
                'email'                => $user->email,
                'avatar'               => $user->avatar ? asset($user->avatar) : null,
                'profile_cover'        => $user->profile_cover ? asset($user->profile_cover) : null,
                'profile_heading'      => $user->profile_heading,
                'profile_description'  => $user->profile_description,
                'is_author'            => (bool) $user->is_author,
                'balance'              => $user->balance,
                'created_at'           => $user->created_at,
            ],
        ], 200);
    }

    /**
     * Revoke mobile token on logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
}
