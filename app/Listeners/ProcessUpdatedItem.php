<?php

namespace App\Listeners;

use App\Events\ItemUpdated;
use App\Jobs\Admin\SendAdminItemUpdateNotification;
use App\Jobs\Reviewer\SendReviewerItemUpdateNotification;
use App\Models\Admin;
use App\Models\Category;

class ProcessUpdatedItem
{
    public function handle(ItemUpdated $event)
    {
        $itemUpdate = $event->itemUpdate;
        $item = $itemUpdate->item;

        if ($item->isApproved()) {
            $reviewers = Category::where('id', $item->category->id)->first()->reviewers;
            foreach ($reviewers as $reviewer) {
                dispatch(new SendReviewerItemUpdateNotification($reviewer, $itemUpdate));
            }

            $admins = Admin::all();

            foreach ($admins as $admin) {
                dispatch(new SendAdminItemUpdateNotification($admin, $itemUpdate));
            }

            $title = translate('Item Update Request (:item_name)', ['item_name' => $item->name]);
            $image = $itemUpdate->getThumbnailLink();
            $link = route('admin.items.updated.show', $itemUpdate->id);
            adminNotify($title, $image, $link);
        }
    }
}