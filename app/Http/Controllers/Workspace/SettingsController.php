<?php

namespace App\Http\Controllers\Workspace;

use App\Classes\Country;
use App\Events\KycVerificationPending;
use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\KycVerification;
use App\Models\UserBadge;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        return theme_view('workspace.settings.index', ['user' => authUser()]);
    }

    public function detailsUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'block_patterns', 'max:50'],
            'lastname' => ['required', 'string', 'block_patterns', 'max:50'],
            'email' => ['required', 'string', 'email', 'indisposable', 'block_patterns', 'max:100', 'unique:users,email,' . authUser()->id],
            'address_line_1' => ['required', 'string', 'max:255', 'block_patterns'],
            'address_line_2' => ['nullable', 'string', 'max:255', 'block_patterns'],
            'city' => ['required', 'string', 'max:150', 'block_patterns'],
            'state' => ['required', 'string', 'max:150', 'block_patterns'],
            'zip' => ['required', 'string', 'max:100', 'block_patterns'],
            'country' => ['required', 'string', 'in:' . implode(',', array_keys(Country::all()))],
            'exclusivity' => ['nullable', 'string', 'in:exclusive,non_exclusive'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $user = authUser();

        $verify = (@settings('actions')->email_verification && $user->email != $request->email) ? 1 : 0;

        $country = $request->country ?? null;

        $address = [
            'line_1' => $request->address_line_1,
            'line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $country,
        ];

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->address = $address;
        $user->exclusivity = $request->exclusivity;
        $user->update();

        if ($verify) {
            authUser()->forceFill(['email_verified_at' => null])->save();
            authUser()->sendEmailVerificationNotification();
        }

        $user->addCountryBadge($country);
        $user->addExclusiveAuthorBadge();

        toastr()->success(translate('Account details has been updated successfully'));
        return back();
    }

    public function profile()
    {
        return theme_view('workspace.settings.profile', ['user' => authUser()]);
    }

    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'profile_cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'profile_heading' => ['nullable', 'string', 'block_patterns', 'max:255'],
            'profile_description' => ['nullable', 'string'],
            'profile_contact_email' => ['nullable', 'email', 'block_patterns', 'max:255'],
            'profile_social_links.*' => ['nullable', 'string', 'block_patterns', 'max:50'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        try {

            $user = authUser();

            $profilesPath = 'images/profiles/' . strtolower(hash_encode($user->id)) . '/';

            if ($request->has('avatar')) {
                $avatar = $request->file('avatar');
                if (!checkImageSize($avatar, '120x120')) {
                    toastr()->error(translate('Avatar image must be 120x120px'));
                    return back();
                }
                $avatar = imageUpload($avatar, $profilesPath, '120x120', null, $user->avatar);
            } else {
                $avatar = $user->avatar;
            }

            if ($request->has('profile_cover')) {
                $profileCover = $request->file('profile_cover');
                if (!checkImageSize($profileCover, '1200x500')) {
                    toastr()->error(translate('Profile cover image must be 1200x500px'));
                    return back();
                }
                $profileCover = imageUpload($profileCover, $profilesPath, '1200x500', null, $user->profile_cover);
            } else {
                $profileCover = $user->profile_cover;
            }

            $socialLinks = [];
            foreach ($request->profile_social_links as $key => $socialLink) {
                if (!is_null($socialLink)) {
                    $socialLinks[$key] = $socialLink;
                }
            }

            $profileDescription = purifierClean($request->profile_description);

            $user->avatar = $avatar;
            $user->profile_cover = $profileCover;
            $user->profile_heading = $request->profile_heading;
            $user->profile_description = $profileDescription;
            $user->profile_contact_email = $request->profile_contact_email;
            $user->profile_social_links = count($socialLinks) > 0 ? $request->profile_social_links : null;
            $user->update();

            toastr()->success(translate('Profile details has been updated successfully'));
            return back();

        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }

    }

    public function withdrawal()
    {
        $withdrawalMethods = WithdrawalMethod::active()->get();
        return theme_view('workspace.settings.withdrawal', [
            'user' => authUser(),
            'withdrawalMethods' => $withdrawalMethods,
        ]);
    }

    public function withdrawalUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdrawal_method' => ['nullable', 'integer', 'exists:withdrawal_methods,id'],
            'withdrawal_account' => ['nullable', 'string', 'max:500', 'block_patterns'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        if (!is_null($request->withdrawal_method) && is_null($request->withdrawal_account)) {
            toastr()->error(translate('Withdrawal account cannot be empty'));
            return back();
        }

        if (is_null($request->withdrawal_method)) {
            $request->withdrawal_account = null;
        }

        $user = authUser();
        $user->withdrawal_method_id = $request->withdrawal_method;
        $user->withdrawal_account = $request->withdrawal_account;
        $user->update();

        toastr()->success(translate('Withdrawal details has been updated successfully'));
        return back();
    }

    public function subscription()
    {
        return theme_view('workspace.settings.subscription', ['user' => authUser()]);
    }

    public function subscriptionCancel()
    {
        $user = authUser();
        $subscription = $user->subscription;
        $subscription->delete();

        $badge = Badge::where('alias', Badge::PREMIUM_MEMBERSHIP_ALIAS)->first();
        if ($badge) {
            $user->removeBadge($badge);
        }

        toastr()->success(translate('Your subscription has been cancelled'));
        return back();
    }

    public function apiKey()
    {
        return theme_view('workspace.settings.api-key', ['user' => authUser()]);
    }

    public function apiKeyGenerate()
    {
        $user = authUser();

        $apiKey = hash('sha256', hash_encode($user->id) . Str::random(16) . microtime());

        $user->api_key = $apiKey;
        $user->update();

        toastr()->success(translate('API key has been generated successfully'));
        return back();
    }

    public function badges()
    {
        $userBadges = UserBadge::where('user_id', authUser()->id)->with('badge')->get();
        return theme_view('workspace.settings.badges', [
            'user' => authUser(),
            'userBadges' => $userBadges,
        ]);
    }

    public function badgesSortable(Request $request)
    {
        $user = authUser();
        if (!$request->has('ids') || is_null($request->ids)) {
            return response()->json(['error' => translate('Failed to sort the badges')]);
        }
        $ids = explode(',', $request->ids);
        foreach ($ids as $sortOrder => $id) {
            $userBadge = $user->badges->where('id', $id)->first();
            $userBadge->sort_id = ($sortOrder + 1);
            $userBadge->update();
        }
        return response()->json(['success' => true]);
    }

    public function password()
    {
        return theme_view('workspace.settings.password');
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current-password' => ['required'],
            'new-password' => ['required', 'string', 'min:8', 'confirmed'],
            'new-password_confirmation' => ['required'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $user = authUser();

        if (!(Hash::check($request->get('current-password'), $user->password))) {
            toastr()->error(translate('Your current password does not matches with the password you provided'));
            return back();
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            toastr()->error(translate('New Password cannot be same as your current password. Please choose a different password'));
            return back();
        }

        $user->password = bcrypt($request->get('new-password'));
        $user->update();

        toastr()->success(translate('Account password has been changed successfully'));
        return back();
    }

    public function towFactor()
    {
        $QR_Image = null;
        if (!authUser()->google2fa_status) {
            $google2fa = app('pragmarx.google2fa');
            $secretKey = encrypt($google2fa->generateSecretKey());
            authUser()->update(['google2fa_secret' => $secretKey]);
            $QR_Image = $google2fa->getQRCodeInline(@settings('general')->site_name, authUser()->email, authUser()->google2fa_secret);
        }
        return theme_view('workspace.settings.2fa', ['user' => authUser(), 'QR_Image' => $QR_Image]);
    }

    public function towFactorEnable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_code' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $user = authUser();

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(translate('Invalid OTP code'));
            return back();
        }

        $update2FaStatus = $user->update(['google2fa_status' => true]);
        if ($update2FaStatus) {
            session()->put('user_2fa', hash_encode($user->id));
            toastr()->success(translate('2FA Authentication has been enabled successfully'));
            return back();
        }

    }

    public function towFactorDisable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp_code' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $user = authUser();

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(translate('Invalid OTP code'));
            return back();
        }

        $update2FaStatus = $user->update(['google2fa_status' => false]);
        if ($update2FaStatus) {
            if ($request->session()->has('user_2fa')) {
                session()->forget('user_2fa');
            }
            toastr()->success(translate('2FA Authentication has been disabled successfully'));
            return back();
        }
    }

    public function kyc()
    {
        return theme_view('workspace.settings.kyc', ['user' => authUser()]);
    }

    public function kycStore(Request $request)
    {
        $rules = [
            'document_type' => ['required', 'string', 'in:national_id,passport'],
        ];

        if (@settings('kyc')->selfie_verification) {
            $rules['selfie'] = ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'];
        }

        if ($request->document_type == KycVerification::DOCUMENT_TYPE_NATIONAL_ID) {
            $rules['front_of_id'] = ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'];
            $rules['back_of_id'] = ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'];
            $rules['national_id_number'] = ['required', 'string', 'block_patterns', 'max:30'];
            $documentNumber = $request->national_id_number;
        } elseif ($request->document_type == KycVerification::DOCUMENT_TYPE_PASSPORT) {
            $rules['passport'] = ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'];
            $rules['passport_number'] = ['required', 'string', 'block_patterns', 'max:30'];
            $documentNumber = $request->passport_number;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }

        $user = authUser();

        if ($user->isKycVerified() || $user->isKycPending()) {
            return back();
        }

        $documents = ['front_of_id' => null, 'back_of_id' => null, 'passport' => null, 'selfie' => null];

        $hashId = strtolower(hash_encode($user->id));

        if ($request->document_type == KycVerification::DOCUMENT_TYPE_NATIONAL_ID) {
            $documents['front_of_id'] = storageFileUpload($request->file('front_of_id'), "kyc/docs/{$hashId}/", 'local');
            $documents['back_of_id'] = storageFileUpload($request->file('back_of_id'), "kyc/docs/{$hashId}/", 'local');
        } elseif ($request->document_type == KycVerification::DOCUMENT_TYPE_PASSPORT) {
            $documents['passport'] = storageFileUpload($request->file('passport'), "kyc/docs/{$hashId}/", 'local');
        }

        if (@settings('kyc')->selfie_verification) {
            $documents['selfie'] = storageFileUpload($request->file('selfie'), "kyc/docs/{$hashId}/", 'local');
        }

        $kycVerification = new KycVerification();
        $kycVerification->user_id = $user->id;
        $kycVerification->document_type = $request->document_type;
        $kycVerification->document_number = $documentNumber;
        $kycVerification->documents = $documents;
        $kycVerification->save();

        event(new KycVerificationPending($kycVerification));

        toastr()->success(translate('Your documents has been submitted successfully'));
        return back();
    }

}