<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class WithdrawalSubmitted
{
    use SerializesModels;

    public $withdrawal;

    public function __construct($withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }
}
