<?php

namespace App\Jobs\Author;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuthorNewRefundNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $refund;

    public $refundReply;

    public function __construct($refund, $refundReply)
    {
        $this->refund = $refund;
        $this->refundReply = $refundReply;
    }

    public function handle()
    {
        $refund = $this->refund;

        SendMail::send($refund->author->email, 'author_new_refund_request', [
            'author_username' => $refund->author->username,
            'user_username' => $refund->user->username,
            'refund_id' => $refund->id,
            'refund_item_name' => $refund->purchase->item->name,
            'refund_reason' => $this->refundReply->body,
            'refund_link' => route('workspace.refunds.show', $refund->id),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
