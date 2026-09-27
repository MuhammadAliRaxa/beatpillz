<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($follower) {
            $follower->follower->increment('total_following');
            $follower->following->increment('total_followers');
        });

        static::deleted(function ($follower) {
            $follower->follower->decrement('total_following');
            $follower->following->decrement('total_followers');
        });
    }

    protected $fillable = [
        'follower_id',
        'following_id',
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
