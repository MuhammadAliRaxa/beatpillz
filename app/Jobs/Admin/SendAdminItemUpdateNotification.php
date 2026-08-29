<?php

namespace App\Jobs\Admin;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminItemUpdateNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $admin;
    public $itemUpdate;

    public function __construct($admin, $itemUpdate)
    {
        $this->admin = $admin;
        $this->itemUpdate = $itemUpdate;
    }

    public function handle()
    {
        $admin = $this->admin;
        $itemUpdate = $this->itemUpdate;
        $item = $itemUpdate->item;

        SendMail::send($admin->email, 'admin_item_update', [
            'author_username' => $item->author->username,
            'item_id' => $item->id,
            'item_name' => $itemUpdate->name,
            'item_preview_image' => '<img src="' . $itemUpdate->getImageLink() . '" width="100%"/>',
            'review_link' => route('admin.items.updated.show', $itemUpdate->id),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
