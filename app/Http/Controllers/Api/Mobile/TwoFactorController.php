<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TwoFactorController extends Controller
{
    /**
     * Get 2FA status and QR Code setup metadata.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($user->google2fa_status) {
            return response()->json([
                'success'    => true,
                'is_enabled' => true,
                'setup'      => null,
            ], 200);
        }

        try {
            $google2fa = app('pragmarx.google2fa');

            // Generate secret if not present
            if (empty($user->getRawOriginal('google2fa_secret'))) {
                $secretKey = $google2fa->generateSecretKey();
                $user->update(['google2fa_secret' => encrypt($secretKey)]);
            }

            $secret = $user->google2fa_secret;
            $siteName = @settings('general')->site_name ?? 'Beat Pillz';

            $qrCodeInline = $google2fa->getQRCodeInline($siteName, $user->email, $secret);
            $otpAuthUrl = $google2fa->getQRCodeUrl($siteName, $user->email, $secret);

            return response()->json([
                'success'    => true,
                'is_enabled' => false,
                'setup'      => [
                    'secret_key'  => $secret,
                    'qr_code_url' => $qrCodeInline,
                    'otpauth_url' => $otpAuthUrl,
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Enable 2FA authentication by verifying a 6-digit TOTP code.
     */
    public function enable(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'otp_code' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $google2fa = app('pragmarx.google2fa');
            $valid = $google2fa->verifyKey($user->google2fa_secret, $request->otp_code);

            if (!$valid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP code. Please check your Authenticator app and try again.',
                ], 422);
            }

            $user->update(['google2fa_status' => true]);

            return response()->json([
                'success'    => true,
                'message'    => '2FA Authentication has been enabled successfully.',
                'is_enabled' => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Disable 2FA authentication by confirming with a 6-digit TOTP code.
     */
    public function disable(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'otp_code' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $google2fa = app('pragmarx.google2fa');
            $valid = $google2fa->verifyKey($user->google2fa_secret, $request->otp_code);

            if (!$valid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP code.',
                ], 422);
            }

            $user->update(['google2fa_status' => false]);

            return response()->json([
                'success'    => true,
                'message'    => '2FA Authentication has been disabled successfully.',
                'is_enabled' => false,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
