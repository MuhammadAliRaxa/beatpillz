<?php

namespace App\Jobs\Author;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuthorItemSoftRejectedNotification implements ShouldQueue
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

        SendMail::send($author->email, 'author_item_soft_rejected', [
            'author_username' => $author->username,
            'item_id' => $item->id,
            'item_name' => $item->name,
            'item_preview_image' => '<img src="' . $item->getImageLink() . '" width="100%"/>',
            'item_edit_link' => route('workspace.items.edit', $item->id),
            'rejection_reason' => $itemHistory->body,
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
