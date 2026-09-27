<?php

namespace App\Listeners;

use App\Events\ItemResubmitted;
use App\Jobs\Admin\SendAdminItemResubmittedNotification;
use App\Jobs\Reviewer\SendReviewerItemResubmittedNotification;
use App\Models\Admin;
use App\Models\Category;

class ProcessResubmittedItem
{
    public function handle(ItemResubmitted $event)
    {
        $item = $event->item;

        if ($item->isResubmitted()) {
            $reviewers = Category::where('id', $item->category->id)->first()->reviewers;
            foreach ($reviewers as $reviewer) {
                dispatch(new SendReviewerItemResubmittedNotification($reviewer, $item));
            }

            $admins = Admin::all();
            foreach ($admins as $admin) {
                dispatch(new SendAdminItemResubmittedNotification($admin, $item));
            }

            $title = translate('Item Resubmitted (:item_name)', ['item_name' => $item->name]);
            $image = $item->getThumbnailLink();
            $link = route('admin.items.show', $item->id);
            adminNotify($title, $image, $link);
        }
    }
}