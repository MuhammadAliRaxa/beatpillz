<?php

namespace App\Jobs\Author;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuthorWithdrawalStatusUpdateNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $withdrawal;

    public function __construct($withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    public function handle()
    {
        $withdrawal = $this->withdrawal;
        $author = $withdrawal->author;

        SendMail::send($author->email, 'author_withdrawal_status_updated', [
            'author_username' => $author->username,
            'request_id' => $withdrawal->id,
            'amount' => getAmount($withdrawal->amount),
            'method' => $withdrawal->method,
            'account' => $withdrawal->account,
            'status' => $withdrawal->getStatusName(),
            'date' => dateFormat($withdrawal->created_at),
            'link' => route('workspace.withdrawals.index'),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}