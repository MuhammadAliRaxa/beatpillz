<?php

namespace App\Models;

use App\Scopes\SortByIdScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    const POSITION_BEFORE_PRICE = 1;
    const POSITION_AFTER_PRICE = 2;

    protected static function booted()
    {
        static::addGlobalScope(new SortByIdScope);
    }

    public function scopeDefault($query)
    {
        $query->where('code', env('DEFAULT_CURRENCY'));
    }

    public function isDefault()
    {
        return $this->code == env('DEFAULT_CURRENCY');
    }

    public function isBeforePrice()
    {
        $this->position == self::POSITION_BEFORE_PRICE;
    }

    public function isAfterPrice()
    {
        $this->position == self::POSITION_AFTER_PRICE;
    }

    protected $fillable = [
        'code',
        'symbol',
        'position',
        'rate',
        'icon',
        'sort_id',
    ];

    public function getIconLink()
    {
        return asset($this->icon);
    }

    public function getPositionName()
    {
        return self::getCurrencyPositionOptions()[$this->position];
    }

    public static function getCurrencyPositionOptions()
    {
        return [
            self::POSITION_BEFORE_PRICE => translate('Before price'),
            self::POSITION_AFTER_PRICE => translate('After price'),
        ];
    }
}