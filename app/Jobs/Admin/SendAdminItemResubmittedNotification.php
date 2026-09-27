<?php

namespace App\Jobs\Admin;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminItemResubmittedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $admin;
    public $item;

    public function __construct($admin, $item)
    {
        $this->admin = $admin;
        $this->item = $item;
    }

    public function handle()
    {
        $admin = $this->admin;
        $item = $this->item;

        SendMail::send($admin->email, 'admin_item_resubmitted', [
            'author_username' => $item->author->username,
            'item_id' => $item->id,
            'item_name' => $item->name,
            'item_preview_image' => '<img src="' . $item->getImageLink() . '" width="100%"/>',
            'review_link' => route('admin.items.show', $item->id),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
