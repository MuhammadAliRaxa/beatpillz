<?php

namespace App\Jobs\Author;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuthorItemHardRejectedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function handle()
    {
        $item = $this->item;
        $author = $item->author;

        SendMail::send($author->email, 'author_item_hard_rejected', [
            'author_username' => $author->username,
            'item_name' => $item->name,
            'item_preview_image' => '<img src="' . $item->getImageLink() . '" width="100%"/>',
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
