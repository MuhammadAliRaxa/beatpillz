<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportEarning extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_REFUNDED = 2;
    const STATUS_CANCELLED = 3;

    public function scopeActive($query)
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function scopeRefunded($query)
    {
        $query->where('status', self::STATUS_REFUNDED);
    }

    public function isRefunded()
    {
        return $this->status == self::STATUS_REFUNDED;
    }

    public function scopeCancelled($query)
    {
        $query->where('status', self::STATUS_CANCELLED);
    }

    public function isCancelled()
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    public function isSupportExpired()
    {
        return Carbon::now()->greaterThan($this->support_expiry_at);
    }

    protected $fillable = [
        'author_id',
        'purchase_id',
        'name',
        'title',
        'days',
        'price',
        'buyer_tax',
        'author_fee',
        'author_tax',
        'author_earning',
        'status',
        'support_expiry_at',
    ];

    protected $casts = [
        'buyer_tax' => 'object',
        'author_tax' => 'object',
        'support_expiry_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}