<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'subscription_id',
        'item_id',
        'name',
        'percentage',
        'price',
        'author_earning',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}