<?php

namespace App\Models;

use App\Methods\Gravatar;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Reviewer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'reviewers';

    public function hasCategory($categoryId)
    {
        return $this->categories->contains('id', $categoryId);
    }

    public function has2fa()
    {
        return $this->google2fa_status == 1;
    }

    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'avatar',
        'password',
        'google2fa_status',
        'google2fa_secret',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getName()
    {
        if ($this->firstname && $this->lastname) {
            return $this->firstname . ' ' . $this->lastname;
        } elseif ($this->username) {
            return $this->username;
        } elseif ($this->email) {
            $emailUsername = explode('@', $this->email);
            return $emailUsername[0];
        }
    }

    public function getAvatar()
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }
        return Gravatar::get($this->email);
    }

    public function getGoogle2faSecretAttribute($value)
    {
        return decrypt($value);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, 'reviewer.password.reset'));
    }
}