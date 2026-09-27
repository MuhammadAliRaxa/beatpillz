<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    const STATUS_PENDING = 1;
    const STATUS_RETURNED = 2;
    const STATUS_APPROVED = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_CANCELLED = 5;

    public function scopePending($query)
    {
        $query->where('status', self::STATUS_PENDING);
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function scopeReturned($query)
    {
        $query->where('status', self::STATUS_RETURNED);
    }

    public function isReturned()
    {
        return $this->status == self::STATUS_RETURNED;
    }

    public function scopeApproved($query)
    {
        $query->where('status', self::STATUS_APPROVED);
    }

    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }

    public function scopeCompleted($query)
    {
        $query->where('status', self::STATUS_COMPLETED);
    }

    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    public function scopeCancelled($query)
    {
        $query->where('status', self::STATUS_CANCELLED);
    }

    public function isCancelled()
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    protected $fillable = [
        'author_id',
        'amount',
        'method',
        'account',
        'status',
    ];

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => translate('Pending'),
            self::STATUS_RETURNED => translate('Returned'),
            self::STATUS_APPROVED => translate('Approved'),
            self::STATUS_COMPLETED => translate('Completed'),
            self::STATUS_CANCELLED => translate('Cancelled'),
        ];
    }

    public function getStatusName()
    {
        return self::getStatusOptions()[$this->status];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}