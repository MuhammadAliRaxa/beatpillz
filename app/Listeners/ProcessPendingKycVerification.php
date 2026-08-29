<?php

namespace App\Listeners;

use App\Events\KycVerificationPending;
use App\Jobs\Admin\SendAdminKycPendingNotification;
use App\Models\Admin;

class ProcessPendingKycVerification
{
    public function handle(KycVerificationPending $event)
    {
        $kycVerification = $event->kycVerification;

        $admins = Admin::all();
        foreach ($admins as $admin) {
            dispatch(new SendAdminKycPendingNotification($admin, $kycVerification));
        }

        $title = translate('KYC Verification Request [#:id]', ['id' => $kycVerification->id]);
        $image = asset('images/notifications/kyc.png');
        $link = route('admin.kyc-verifications.review', $kycVerification->id);
        adminNotify($title, $image, $link);
    }
}
