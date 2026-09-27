<?php

namespace App\Jobs\Admin;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminKycPendingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $admin;
    public $kycVerification;

    public function __construct($admin, $kycVerification)
    {
        $this->admin = $admin;
        $this->kycVerification = $kycVerification;
    }

    public function handle()
    {
        $admin = $this->admin;
        $kycVerification = $this->kycVerification;

        SendMail::send($admin->email, 'admin_kyc_pending', [
            "username" => $kycVerification->user->username,
            "kyc_verification_id" => $kycVerification->id,
            "review_link" => route('admin.kyc-verifications.review', $kycVerification->id),
            "website_name" => @settings('general')->site_name,
        ]);
    }
}
