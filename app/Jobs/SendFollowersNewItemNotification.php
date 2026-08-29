<?php

namespace App\Jobs;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFollowersNewItemNotification implements ShouldQueue
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
        $followers = $author->followers;

        foreach ($followers as $follower) {
            $user = $follower->follower;
            SendMail::send($user->email, 'follower_new_item', [
                'follower_username' => $user->username,
                'author_username' => $author->username,
                'item_name' => $item->name,
                'item_preview_image' => '<img src="' . $item->getImageLink() . '" width="100%"/>',
                'item_link' => $item->getLink(),
                'website_name' => @settings('general')->site_name,
            ]);
        }
    }
}
