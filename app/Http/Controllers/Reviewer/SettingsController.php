<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Validator;

class SettingsController extends Controller
{
    public function index()
    {
        $qrCode = null;
        if (!$this->reviewer()->google2fa_status) {
            $google2fa = app('pragmarx.google2fa');
            $secretKey = encrypt($google2fa->generateSecretKey());
            $this->reviewer()->update(['google2fa_secret' => $secretKey]);
            $qrCode = $google2fa->getQRCodeInline(@settings('general')->site_name, $this->reviewer()->email, $this->reviewer()->google2fa_secret);
        }
        return view('reviewer.settings', [
            'reviewer' => $this->reviewer(),
            'qrCode' => $qrCode,
        ]);
    }

    public function updateDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'block_patterns', 'max:255'],
            'lastname' => ['required', 'string', 'block_patterns', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'username' => ['required', 'string', 'min:5', 'alpha_dash', 'block_patterns', 'unique:reviewers,username,' . $this->reviewer()->id],
            'email' => ['required', 'string', 'email', 'indisposable', 'block_patterns', 'unique:reviewers,email,' . $this->reviewer()->id],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if ($request->has('avatar')) {
            $avatar = imageUpload($request->file('avatar'), 'images/avatars/reviewers/', '120x120', null, $this->reviewer()->avatar);
        } else {
            $avatar = $this->reviewer()->avatar;
        }
        $update = $this->reviewer()->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => strtolower($request->username),
            'email' => $request->email,
            'avatar' => $avatar,
        ]);
        if ($update) {
            toastr()->success(translate('Updated Successfully'));
            return back();
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current-password' => ['required'],
            'new-password' => ['required', 'string', 'min:6', 'confirmed'],
            'new-password_confirmation' => ['required'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if (!(Hash::check($request->get('current-password'), $this->reviewer()->password))) {
            toastr()->error(translate('Your current password does not matches with the password you provided.'));
            return back();
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            toastr()->error(translate('New Password cannot be same as your current password. Please choose a different password.'));
            return back();
        }
        $update = $this->reviewer()->update([
            'password' => bcrypt($request->get('new-password')),
        ]);
        if ($update) {
            toastr()->success(translate('Updated Successfully'));
            return back();
        }
    }

    public function enable2FA(Request $request)
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
        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($this->reviewer()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(translate('Invalid OTP code'));
            return back();
        }
        $update2FaStatus = $this->reviewer()->update(['google2fa_status' => true]);
        if ($update2FaStatus) {
            session()->put('reviewer_2fa', encrypt($this->reviewer()->id));
            toastr()->success(translate('2FA Authentication has been enabled successfully'));
            return back();
        }
    }

    public function disable2FA(Request $request)
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
        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($this->reviewer()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(translate('Invalid OTP code'));
            return back();
        }
        $update2FaStatus = $this->reviewer()->update(['google2fa_status' => false]);
        if ($update2FaStatus) {
            if ($request->session()->has('reviewer_2fa')) {
                session()->forget('reviewer_2fa');
            }
            toastr()->success(translate('2FA Authentication has been disabled successfully'));
            return back();
        }
    }

    private function reviewer()
    {
        return authReviewer();
    }
}
