<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\UserResource;
use App\Models\KycVerification;
use App\Models\User;
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
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user'    => new UserResource($user),
        ], 200);
    }

    /**
     * Update user basic details and bio.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        $validator = Validator::make($request->all(), [
            'firstname'           => ['required', 'string', 'max:50'],
            'lastname'            => ['required', 'string', 'max:50'],
            'profile_heading'     => ['nullable', 'string', 'max:100'],
            'profile_description' => ['nullable', 'string', 'max:1000'],
            'social_links'        => ['nullable'],
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

        $socialLinks = null;
        if ($request->has('social_links')) {
            $raw = $request->input('social_links');
            if (is_string($raw)) {
                $socialLinks = json_decode($raw, true) ?? [];
            } elseif (is_array($raw)) {
                $socialLinks = $raw;
            } elseif (is_object($raw)) {
                $socialLinks = (array) $raw;
            }
        }
        foreach (['spotify', 'instagram', 'twitter', 'x', 'youtube', 'soundcloud', 'facebook'] as $platform) {
            if ($request->filled($platform)) {
                if (!is_array($socialLinks)) {
                    $existing = $user->profile_social_links;
                    $socialLinks = is_array($existing) ? $existing : (is_object($existing) ? (array) $existing : []);
                }
                $socialLinks[$platform] = $request->input($platform);
            }
        }
        if ($socialLinks !== null) {
            $user->profile_social_links = $socialLinks;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->has('address_line_1') || $request->has('country')) {
            $user->address = [
                'line_1'  => $request->input('address_line_1', @$user->address->line_1),
                'line_2'  => $request->input('address_line_2', @$user->address->line_2),
                'city'    => $request->input('city', @$user->address->city),
                'state'   => $request->input('state', @$user->address->state),
                'zip'     => $request->input('zip', @$user->address->zip),
                'country' => $request->input('country', @$user->address->country),
            ];
            if ($request->filled('country') && method_exists($user, 'addCountryBadge')) {
                $user->addCountryBadge($request->country);
            }
        }

        if ($user->isAuthor() && $request->filled('exclusivity')) {
            $user->exclusivity = $request->exclusivity;
            $user->addExclusiveAuthorBadge();
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user'    => new UserResource($user->fresh()),
        ], 200);
    }

    /**
     * Update user account details (first/last name, email, billing address, exclusivity).
     */
    public function updateAccountDetails(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        $validator = Validator::make($request->all(), [
            'firstname'      => ['required', 'string', 'max:50'],
            'lastname'       => ['required', 'string', 'max:50'],
            'email'          => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:150'],
            'state'          => ['required', 'string', 'max:150'],
            'zip'            => ['required', 'string', 'max:100'],
            'country'        => ['required', 'string', 'max:50'],
            'exclusivity'    => ['nullable', 'string', 'in:exclusive,non_exclusive'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $country = $request->country;

        $address = [
            'line_1'  => $request->address_line_1,
            'line_2'  => $request->address_line_2,
            'city'    => $request->city,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'country' => $country,
        ];

        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->email     = $request->email;
        $user->address   = $address;

        if ($user->isAuthor() && $request->filled('exclusivity')) {
            $user->exclusivity = $request->exclusivity;
            $user->addExclusiveAuthorBadge();
        }

        $user->save();

        if (method_exists($user, 'addCountryBadge')) {
            $user->addCountryBadge($country);
        }

        return response()->json([
            'success' => true,
            'message' => 'Account details updated successfully.',
            'user'    => new UserResource($user->fresh()),
        ], 200);
    }

    /**
     * Get referral program stats and referred users list.
     */
    public function referrals(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        if (!@settings('referral')->status) {
            return response()->json([
                'success' => false,
                'message' => 'Referral program is currently disabled.',
            ], 404);
        }

        $query = \App\Models\Referral::where('author_id', $user->id);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('username', 'like', $searchTerm);
            });
        }

        $totalEarnings = (float) \App\Models\Referral::where('author_id', $user->id)->sum('earnings');
        $referrals = $query->with('user')->orderByDesc('id')->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => [
                'referral_code'         => $user->username,
                'referral_link'         => $user->getReferralLink(),
                'commission_percentage' => (float) (@settings('referral')->percentage ?? 0),
                'stats'                 => [
                    'total_referrals' => $referrals->total(),
                    'total_earnings'  => $totalEarnings,
                    'currency'        => (function_exists('defaultCurrency') && defaultCurrency()) ? defaultCurrency()->code : 'USD',
                ],
                'referrals'             => $referrals->map(function ($ref) {
                    return [
                        'id'         => $ref->id,
                        'user'       => $ref->user ? [
                            'id'       => $ref->user->id,
                            'username' => $ref->user->username,
                            'avatar'   => $ref->user->avatar ? asset($ref->user->avatar) : null,
                        ] : null,
                        'earnings'   => (float) $ref->earnings,
                        'created_at' => $ref->created_at ? $ref->created_at->toISOString() : null,
                    ];
                }),
                'meta'                  => [
                    'current_page' => $referrals->currentPage(),
                    'last_page'    => $referrals->lastPage(),
                    'per_page'     => $referrals->perPage(),
                    'total'        => $referrals->total(),
                ],
            ],
        ], 200);
    }

    /**
     * Change user account password.
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

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
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

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
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
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
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

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
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
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

