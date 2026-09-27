<?php

namespace App\Jobs\Reviewer;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReviewerItemUpdateNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reviewer;
    public $itemUpdate;

    public function __construct($reviewer, $itemUpdate)
    {
        $this->reviewer = $reviewer;
        $this->itemUpdate = $itemUpdate;
    }

    public function handle()
    {
        $reviewer = $this->reviewer;
        $itemUpdate = $this->itemUpdate;
        $item = $itemUpdate->item;

        SendMail::send($reviewer->email, 'reviewer_item_update', [
            'author_username' => $item->author->username,
            'item_id' => $item->id,
            'item_name' => $itemUpdate->name,
            'item_preview_image' => '<img src="' . $itemUpdate->getImageLink() . '" width="100%"/>',
            'review_link' => route('reviewer.items.updated.review', $itemUpdate->id),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
