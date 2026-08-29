<?php

namespace App\Jobs\Admin;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminNewTicketNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $admin;
    public $ticket;

    public function __construct($admin, $ticket)
    {
        $this->admin = $admin;
        $this->ticket = $ticket;
    }

    public function handle(): void
    {
        $admin = $this->admin;
        $ticket = $this->ticket;

        SendMail::send($admin->email, 'admin_new_ticket', [
            'username' => $ticket->user->username,
            'ticket_id' => $ticket->id,
            'subject' => $ticket->subject,
            'category' => $ticket->category->name,
            'link' => route('admin.tickets.show', $ticket->id),
            'date' => dateFormat($ticket->created_at),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
