<?php

namespace App\Jobs\Author;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuthorItemUpdateRejectedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $item;
    public $itemHistory;

    public function __construct($item, $itemHistory)
    {
        $this->item = $item;
        $this->itemHistory = $itemHistory;
    }

    public function handle()
    {
        $item = $this->item;
        $itemHistory = $this->itemHistory;
        $author = $item->author;

        SendMail::send($author->email, 'author_item_update_rejected', [
            'author_username' => $author->username,
            'item_name' => $item->name,
            'item_preview_image' => '<img src="' . $item->getImageLink() . '" width="100%"/>',
            'item_link' => $item->getLink(),
            'rejection_reason' => $itemHistory->body,
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
