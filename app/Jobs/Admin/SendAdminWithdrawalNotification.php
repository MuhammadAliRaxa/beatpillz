<?php

namespace App\Jobs\Admin;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminWithdrawalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $admin;
    public $withdrawal;

    public function __construct($admin, $withdrawal)
    {
        $this->admin = $admin;
        $this->withdrawal = $withdrawal;
    }

    public function handle()
    {
        $admin = $this->admin;
        $withdrawal = $this->withdrawal;

        SendMail::send($admin->email, 'admin_withdrawal_pending', [
            'author_username' => $withdrawal->author->username,
            'request_id' => $withdrawal->id,
            'amount' => getAmount($withdrawal->amount),
            'method' => $withdrawal->method,
            'account' => $withdrawal->account,
            'status' => $withdrawal->getStatusName(),
            'date' => dateFormat($withdrawal->created_at),
            'review_link' => route('admin.withdrawals.review', $withdrawal->id),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}