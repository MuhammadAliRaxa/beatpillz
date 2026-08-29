<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Jobs\Admin\SendAdminNewTicketNotification;
use App\Models\Admin;

class ProcessTicketCreation
{
    public function handle(TicketCreated $event)
    {
        $ticket = $event->ticket;

        $admins = Admin::all();
        foreach ($admins as $admin) {
            dispatch(new SendAdminNewTicketNotification($admin, $ticket));
        }

        $title = translate('New Ticket [#:id]', ['id' => $ticket->id]);
        $image = asset('images/notifications/ticket.png');
        $link = route('admin.tickets.show', $ticket->id);
        adminNotify($title, $image, $link);
    }
}
