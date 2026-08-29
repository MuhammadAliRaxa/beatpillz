<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class KycVerificationPending
{
    use SerializesModels;

    public $kycVerification;

    public function __construct($kycVerification)
    {
        $this->kycVerification = $kycVerification;
    }

}
