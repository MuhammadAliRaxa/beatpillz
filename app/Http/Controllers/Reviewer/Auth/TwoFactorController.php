<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class TwoFactorController extends Controller
{
    public function show2FaVerifyForm()
    {
        if (!authReviewer()->google2fa_status ||
            session()->has('reviewer_2fa') && session('reviewer_2fa') == encrypt(authReviewer()->id)) {
            return redirect()->route('reviewer.dashboard');
        }
        return view('reviewer.auth.2fa');
    }

    public function verify2fa(Request $request)
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
        $valid = $google2fa->verifyKey(authReviewer()->google2fa_secret, $request->otp_code);
        if ($valid == false) {
            toastr()->error(translate('Invalid OTP code'));
            return back();
        }

        session()->put('reviewer_2fa', encrypt(authReviewer()->id));
        return redirect()->route('reviewer.dashboard');
    }
}
