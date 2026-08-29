<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\UserResource;
use App\Models\KycVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Get authenticated user profile.
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => new UserResource($request->user()),
        ], 200);
    }

    /**
     * Update user basic details and bio.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'firstname'           => ['required', 'string', 'max:50'],
            'lastname'            => ['required', 'string', 'max:50'],
            'profile_heading'     => ['nullable', 'string', 'max:100'],
            'profile_description' => ['nullable', 'string', 'max:1000'],
            'social_links'        => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->profile_heading = $request->profile_heading;
        $user->profile_description = $request->profile_description;
        if ($request->has('social_links')) {
            $user->profile_social_links = $request->social_links;
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user'    => new UserResource($user),
        ], 200);
    }

    /**
     * Change user account password.
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided current password does not match our records.',
            ], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ], 200);
    }

    /**
     * Upload / Update avatar image.
     */
    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/avatars', $filename, 'public');

            if ($user->avatar && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }

            $user->avatar = 'storage/' . $path;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Avatar updated successfully.',
            'avatar'  => asset($user->avatar),
            'user'    => new UserResource($user),
        ], 200);
    }

    /**
     * Check KYC Status and requirements.
     */
    public function kycStatus(Request $request)
    {
        $user = $request->user();
        $latestKyc = $user->kycVerifications()->latest()->first();

        return response()->json([
            'success'    => true,
            'kyc_status' => (int) $user->kyc_status,
            'is_verified'=> $user->kyc_status == User::KYC_STATUS_VERIFIED,
            'submission' => $latestKyc ? [
                'id'         => $latestKyc->id,
                'status'     => (int) $latestKyc->status,
                'created_at' => $latestKyc->created_at ? $latestKyc->created_at->toISOString() : null,
                'updated_at' => $latestKyc->updated_at ? $latestKyc->updated_at->toISOString() : null,
            ] : null,
        ], 200);
    }

    /**
     * Upgrade user account to Author / Creator status.
     */
    public function becomeAuthor(Request $request)
    {
        $user = $request->user();

        if ($user->is_author) {
            return response()->json([
                'success' => true,
                'message' => 'You are already an author.',
                'user'    => new UserResource($user),
            ], 200);
        }

        $level = \App\Models\Level::default()->with('badge')->first();
        if ($level) {
            $user->level_id = $level->id;
            $user->is_author = \App\Models\User::AUTHOR;
            $user->save();

            if ($level->badge) {
                $user->addBadge($level->badge);
            }
        } else {
            $user->is_author = \App\Models\User::AUTHOR;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Congratulations! You are now an author.',
            'user'    => new UserResource($user),
        ], 200);
    }

    /**
     * Update author payout withdrawal account.
     */
    public function updateWithdrawalAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdrawal_method_id' => ['required', 'exists:withdrawal_methods,id'],
            'withdrawal_account'   => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        $user->withdrawal_method_id = $request->withdrawal_method_id;
        $user->withdrawal_account = $request->withdrawal_account;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal account updated successfully.',
            'user'    => new UserResource($user),
        ], 200);
    }
}

