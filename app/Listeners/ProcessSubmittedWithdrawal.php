<?php

namespace App\Listeners;

use App\Events\WithdrawalSubmitted;
use App\Jobs\Admin\SendAdminWithdrawalNotification;
use App\Models\Admin;

class ProcessSubmittedWithdrawal
{
    public function handle(WithdrawalSubmitted $event)
    {
        $withdrawal = $event->withdrawal;

        $admins = Admin::all();
        foreach ($admins as $admin) {
            dispatch(new SendAdminWithdrawalNotification($admin, $withdrawal));
        }

        $title = translate('New Withdrawal Request [#:id]', ['id' => $withdrawal->id]);
        $image = asset('images/notifications/withdrawal.png');
        $link = route('admin.withdrawals.review', $withdrawal->id);
        adminNotify($title, $image, $link);
    }
}
