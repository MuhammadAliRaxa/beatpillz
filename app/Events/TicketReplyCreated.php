<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class TicketReplyCreated
{
    use SerializesModels;

    public $ticketReply;

    public function __construct($ticketReply)
    {
        $this->ticketReply = $ticketReply;
    }
}
