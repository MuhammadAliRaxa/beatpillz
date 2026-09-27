<?php

namespace App\Listeners;

use App\Events\ItemApproved;
use App\Jobs\Author\SendAuthorItemApprovedNotification;
use App\Jobs\SendFollowersNewItemNotification;

class ProcessApprovedItem
{
    public function handle(ItemApproved $event)
    {
        $item = $event->item;

        dispatch(new SendAuthorItemApprovedNotification($item));

        dispatch(new SendFollowersNewItemNotification($item));
    }
}