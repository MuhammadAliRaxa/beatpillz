<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class TicketCreated
{
    use SerializesModels;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }
}
