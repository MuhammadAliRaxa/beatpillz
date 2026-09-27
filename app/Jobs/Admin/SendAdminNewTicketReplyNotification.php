<?php

namespace App\Jobs\Admin;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminNewTicketReplyNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $admin;
    public $ticketReply;

    public function __construct($admin, $ticketReply)
    {
        $this->admin = $admin;
        $this->ticketReply = $ticketReply;
    }

    public function handle()
    {
        $admin = $this->admin;
        $ticketReply = $this->ticketReply;
        $ticket = $ticketReply->ticket;

        SendMail::send($admin->email, 'admin_new_ticket_reply', [
            'username' => $ticket->user->username,
            'ticket_id' => $ticket->id,
            'reply_message' => $ticketReply->body,
            'link' => route('admin.tickets.show', $ticket->id),
            'date' => dateFormat($ticket->created_at),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
