<?php

namespace App\Listeners;

use App\Events\TransactionPending;
use App\Jobs\Admin\SendAdminTrxPendingNotification;
use App\Models\Admin;

class ProcessPendingTransaction
{
    public function handle(TransactionPending $event)
    {
        $transaction = $event->transaction;

        if ($transaction->isPending()) {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                dispatch(new SendAdminTrxPendingNotification($admin, $transaction));
            }

            $title = translate('New Pending Transaction [#:id]', ['id' => $transaction->id]);
            $image = asset('images/notifications/transaction.png');
            $link = route('admin.transactions.review', $transaction->id);
            adminNotify($title, $image, $link);
        }
    }
}
