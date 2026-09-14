<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Events\KycVerificationPending;
use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\UserResource;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
     * Update user profile (text info, avatar, cover image, social links).
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        $validator = Validator::make($request->all(), [
            'firstname'            => ['nullable', 'string', 'max:50'],
            'lastname'             => ['nullable', 'string', 'max:50'],
            'profile_heading'      => ['nullable', 'string', 'max:100'],
            'profile_description'  => ['nullable', 'string', 'max:1000'],
            'profile_contact_email' => ['nullable', 'email', 'max:255'],
            'social_links'         => ['nullable'],
            'avatar'               => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'profile_cover'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:6144'],
            'cover'                => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:6144'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        if ($request->filled('firstname')) $user->firstname = $request->firstname;
        if ($request->filled('lastname')) $user->lastname = $request->lastname;
        if ($request->has('profile_heading')) $user->profile_heading = $request->profile_heading;
        if ($request->has('profile_description')) $user->profile_description = $request->profile_description;
        if ($request->has('profile_contact_email')) $user->profile_contact_email = $request->profile_contact_email;

        $profilesPath = 'images/profiles/' . strtolower(hash_encode($user->id)) . '/';

        // Avatar file upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $user->avatar = imageUpload($file, $profilesPath, '120x120', null, $user->avatar);
        }

        // Cover file upload
        if ($request->hasFile('profile_cover') || $request->hasFile('cover')) {
            $coverFile = $request->file('profile_cover') ?? $request->file('cover');
            $user->profile_cover = imageUpload($coverFile, $profilesPath, '1200x500', null, $user->profile_cover);
        }

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
            $profilesPath = 'images/profiles/' . strtolower(hash_encode($user->id)) . '/';
            $user->avatar = imageUpload($request->file('avatar'), $profilesPath, '120x120', null, $user->avatar);
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
     * Upload / Update profile cover image.
     */
    public function updateCover(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:6144'],
            'profile_cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:6144'],
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

        $file = $request->file('cover') ?? $request->file('profile_cover');
        if ($file) {
            $profilesPath = 'images/profiles/' . strtolower(hash_encode($user->id)) . '/';
            $user->profile_cover = imageUpload($file, $profilesPath, '1200x500', null, $user->profile_cover);
            $user->save();
        }

        return response()->json([
            'success'       => true,
            'message'       => 'Profile cover image updated successfully.',
            'profile_cover' => asset($user->profile_cover),
            'user'          => new UserResource($user),
        ], 200);
    }

    /**
     * Check KYC Status, requirements, sample guidance images, and latest submission.
     */
    public function kycStatus(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        $latestKyc = $user->kycVerifications()->latest()->first();

        // Normalized KYC status: 0 = Unverified, 1 = Pending, 2 = Verified, 3 = Rejected
        $kycStatus = 0;
        if ($user->isKycVerified()) {
            $kycStatus = 2;
        } elseif ($latestKyc && $latestKyc->isPending()) {
            $kycStatus = 1;
        } elseif ($latestKyc && $latestKyc->isRejected()) {
            $kycStatus = 3;
        }

        $kycSettings = @settings('kyc');

        // Sample/guidance images from admin settings (stored in public/images/kyc/)
        $sampleImages = [
            'id_front_image' => (@$kycSettings && @$kycSettings->id_front_image) ? asset($kycSettings->id_front_image) : null,
            'id_back_image'  => (@$kycSettings && @$kycSettings->id_back_image) ? asset($kycSettings->id_back_image) : null,
            'passport_image' => (@$kycSettings && @$kycSettings->passport_image) ? asset($kycSettings->passport_image) : null,
            'selfie_image'   => (@$kycSettings && @$kycSettings->selfie_image) ? asset($kycSettings->selfie_image) : null,
        ];

        $submissionData = null;
        if ($latestKyc) {
            $docs = [];
            if ($latestKyc->documents) {
                foreach ((array) $latestKyc->documents as $docKey => $docPath) {
                    if ($docPath) {
                        $docs[$docKey] = [
                            'path' => $docPath,
                            'url'  => route('api.v1.user.kyc.document', ['document' => $docKey]),
                        ];
                    }
                }
            }

            $submissionData = [
                'id'                 => $latestKyc->id,
                'document_type'      => $latestKyc->document_type,
                'document_type_name' => $latestKyc->getDocumentTypeOptions()[$latestKyc->document_type] ?? ucfirst(str_replace('_', ' ', $latestKyc->document_type)),
                'document_number'    => $latestKyc->document_number,
                'status'             => (int) $latestKyc->status,
                'status_name'        => $latestKyc->getStatusName(),
                'rejection_reason'   => $latestKyc->rejection_reason,
                'documents'          => $docs,
                'created_at'         => $latestKyc->created_at ? $latestKyc->created_at->toISOString() : null,
                'updated_at'         => $latestKyc->updated_at ? $latestKyc->updated_at->toISOString() : null,
            ];
        }

        return response()->json([
            'success'     => true,
            'kyc_status'  => $kycStatus,
            'is_verified' => (bool) $user->isKycVerified(),
            'is_pending'  => (bool) $user->isKycPending(),
            'is_required' => (bool) $user->isKycRequired(),
            'settings'    => [
                'is_enabled'               => (bool) @$kycSettings->status,
                'is_required'              => (bool) @$kycSettings->required,
                'selfie_verification'      => (bool) @$kycSettings->selfie_verification,
                'supported_document_types' => [
                    KycVerification::DOCUMENT_TYPE_NATIONAL_ID => 'National ID',
                    KycVerification::DOCUMENT_TYPE_PASSPORT    => 'Passport',
                ],
                'sample_images'            => $sampleImages,
            ],
            'submission'  => $submissionData,
        ], 200);
    }

    /**
     * Submit KYC verification documents (identical validation & storage to web).
     */
    public function submitKyc(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        if (!@settings('kyc')->status) {
            return response()->json([
                'success' => false,
                'message' => 'KYC verification is currently disabled.',
            ], 403);
        }

        if ($user->isKycVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is already KYC verified.',
            ], 400);
        }

        if ($user->isKycPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Your KYC verification documents are currently pending review.',
            ], 400);
        }

        $rules = [
            'document_type' => ['required', 'string', 'in:national_id,passport'],
        ];

        if (@settings('kyc')->selfie_verification) {
            $rules['selfie'] = ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'];
        }

        if ($request->document_type == KycVerification::DOCUMENT_TYPE_NATIONAL_ID) {
            $rules['front_of_id']        = ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'];
            $rules['back_of_id']         = ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'];
            $rules['national_id_number'] = ['required', 'string', 'block_patterns', 'max:30'];
            $documentNumber              = $request->national_id_number;
        } elseif ($request->document_type == KycVerification::DOCUMENT_TYPE_PASSPORT) {
            $rules['passport']        = ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'];
            $rules['passport_number'] = ['required', 'string', 'block_patterns', 'max:30'];
            $documentNumber           = $request->passport_number;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $documents = ['front_of_id' => null, 'back_of_id' => null, 'passport' => null, 'selfie' => null];
        $hashId = strtolower(hash_encode($user->id));

        if ($request->document_type == KycVerification::DOCUMENT_TYPE_NATIONAL_ID) {
            $documents['front_of_id'] = storageFileUpload($request->file('front_of_id'), "kyc/docs/{$hashId}/", 'local');
            $documents['back_of_id']  = storageFileUpload($request->file('back_of_id'), "kyc/docs/{$hashId}/", 'local');
        } elseif ($request->document_type == KycVerification::DOCUMENT_TYPE_PASSPORT) {
            $documents['passport']    = storageFileUpload($request->file('passport'), "kyc/docs/{$hashId}/", 'local');
        }

        if (@settings('kyc')->selfie_verification) {
            $documents['selfie']      = storageFileUpload($request->file('selfie'), "kyc/docs/{$hashId}/", 'local');
        }

        $kycVerification = new KycVerification();
        $kycVerification->user_id         = $user->id;
        $kycVerification->document_type   = $request->document_type;
        $kycVerification->document_number = $documentNumber;
        $kycVerification->documents       = $documents;
        $kycVerification->status          = KycVerification::STATUS_PENDING;
        $kycVerification->save();

        event(new KycVerificationPending($kycVerification));

        $docs = [];
        foreach ($documents as $docKey => $docPath) {
            if ($docPath) {
                $docs[$docKey] = [
                    'path' => $docPath,
                    'url'  => route('api.v1.user.kyc.document', ['document' => $docKey]),
                ];
            }
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Your documents have been submitted successfully and are pending review.',
            'kyc_status' => 1, // Pending
            'submission' => [
                'id'                 => $kycVerification->id,
                'document_type'      => $kycVerification->document_type,
                'document_type_name' => $kycVerification->getDocumentTypeOptions()[$kycVerification->document_type] ?? ucfirst(str_replace('_', ' ', $kycVerification->document_type)),
                'document_number'    => $kycVerification->document_number,
                'status'             => (int) $kycVerification->status,
                'status_name'        => $kycVerification->getStatusName(),
                'documents'          => $docs,
                'created_at'         => $kycVerification->created_at ? $kycVerification->created_at->toISOString() : null,
                'updated_at'         => $kycVerification->updated_at ? $kycVerification->updated_at->toISOString() : null,
            ],
        ], 201);
    }

    /**
     * View/stream submitted KYC document securely for authenticated user.
     */
    public function kycDocument(Request $request, $document)
    {
        $user = $request->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        $allowedDocuments = ['front_of_id', 'back_of_id', 'passport', 'selfie'];
        if (!in_array($document, $allowedDocuments)) {
            return response()->json(['success' => false, 'message' => 'Invalid document type.'], 404);
        }

        $latestKyc = $user->kycVerifications()->latest()->first();
        if (!$latestKyc || empty($latestKyc->documents)) {
            return response()->json(['success' => false, 'message' => 'No KYC submissions found.'], 404);
        }

        $docs = (object) $latestKyc->documents;
        if (!isset($docs->$document) || empty($docs->$document)) {
            return response()->json(['success' => false, 'message' => 'Document not found.'], 404);
        }

        $filePath = $docs->$document;
        if (!Storage::disk('local')->exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File not found on storage.'], 404);
        }

        try {
            $file = Storage::disk('local')->get($filePath);
            $mimeType = Storage::disk('local')->mimeType($filePath);
            return response($file, 200)->header('Content-Type', $mimeType);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to read document file.'], 500);
        }
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

