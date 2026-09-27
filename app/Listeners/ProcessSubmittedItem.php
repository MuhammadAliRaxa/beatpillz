<?php

namespace App\Listeners;

use App\Events\ItemSubmitted;
use App\Jobs\Admin\SendAdminItemPendingNotification;
use App\Jobs\Reviewer\SendReviewerItemPendingNotification;
use App\Models\Admin;
use App\Models\Category;

class ProcessSubmittedItem
{
    public function handle(ItemSubmitted $event)
    {
        $item = $event->item;

        if ($item->isPending()) {
            $reviewers = Category::where('id', $item->category->id)->first()->reviewers;
            foreach ($reviewers as $reviewer) {
                dispatch(new SendReviewerItemPendingNotification($reviewer, $item));
            }

            $admins = Admin::all();
            foreach ($admins as $admin) {
                dispatch(new SendAdminItemPendingNotification($admin, $item));
            }

            $title = translate('New Pending Item (:item_name)', ['item_name' => $item->name]);
            $image = $item->getThumbnailLink();
            $link = route('admin.items.show', $item->id);
            adminNotify($title, $image, $link);
        }
    }
}