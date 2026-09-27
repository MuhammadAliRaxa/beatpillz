<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ItemUpdated
{
    use SerializesModels;

    public $itemUpdate;

    public function __construct($itemUpdate)
    {
        $this->itemUpdate = $itemUpdate;
    }
}