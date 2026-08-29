<?php

namespace App\Jobs;

use App\Classes\SendMail;
use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBuyersItemUpdateNotification implements ShouldQueue
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
        $purchases = Purchase::where('item_id', $item->id)->active()->get();

        foreach ($purchases as $purchase) {
            $user = $purchase->user;
            SendMail::send($user->email, 'buyer_item_update', [
                'buyer_username' => $user->username,
                'author_username' => $item->author->username,
                'item_name' => $item->name,
                'item_preview_image' => '<img src="' . $item->getImageLink() . '" width="100%"/>',
                'item_link' => $item->getLink(),
                'website_name' => @settings('general')->site_name,
            ]);
        }
    }
}
