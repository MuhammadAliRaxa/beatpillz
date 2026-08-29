<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    const STATUS_UNREAD = 0;
    const STATUS_READ = 1;

    public function scopeUnread($query)
    {
        $query->where('status', self::STATUS_UNREAD);
    }

    public function isUnread()
    {
        return $this->status == self::STATUS_UNREAD;
    }

    public function scopeRead($query)
    {
        $query->where('status', self::STATUS_READ);
    }

    public function isRead()
    {
        return $this->status == self::STATUS_READ;
    }

    protected $fillable = [
        'title',
        'image',
        'link',
        'status',
    ];
}
