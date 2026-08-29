<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    const DEFAULT = 1;
    const NOT_DEFAULT = 0;

    public function scopeDefault($query)
    {
        $query->where('is_default', self::DEFAULT);
    }

    public function isDefault()
    {
        return $this->is_default == self::DEFAULT;
    }

    protected $fillable = [
        'name',
        'min_earnings',
        'fees',
        'is_default',
    ];

    public function badge()
    {
        return $this->hasOne(Badge::class);
    }
}
