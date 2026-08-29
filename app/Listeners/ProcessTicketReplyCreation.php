<?php

namespace App\Listeners;

use App\Events\TicketReplyCreated;
use App\Jobs\Admin\SendAdminNewTicketReplyNotification;
use App\Models\Admin;

class ProcessTicketReplyCreation
{
    public function handle(TicketReplyCreated $event)
    {
        $ticketReply = $event->ticketReply;
        $ticket = $ticketReply->ticket;

        $admins = Admin::all();
        foreach ($admins as $admin) {
            dispatch(new SendAdminNewTicketReplyNotification($admin, $ticketReply));
        }

        $title = translate('New Ticket Reply [#:id]', ['id' => $ticket->id]);
        $image = asset('images/notifications/reply.png');
        $link = route('admin.tickets.show', $ticket->id);
        adminNotify($title, $image, $link);
    }
}
