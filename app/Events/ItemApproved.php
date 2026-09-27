<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ItemApproved
{
    use SerializesModels;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }
}
