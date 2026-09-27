<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    const VERIFIED_ACCOUNT_BADGE_ALIAS = "verified_account";
    const COUNTRY_BADGE_ALIAS = "country";
    const AUTHOR_LEVEL_BADGE_ALIAS = "author_level";
    const MEMBERSHIP_YEARS_BADGE_ALIAS = "membership_years";
    const EXCLUSIVE_AUTHOR_BADGE_ALIAS = "exclusive_author";
    const TREND_MASTER_BADGE_ALIAS = "trend_master";
    const FEATURED_AUTHOR_BADGE_ALIAS = "featured_author";
    const FEATURED_ITEM_BADGE_ALIAS = "featured_item";
    const REFERRER_BADGE_ALIAS = "referrer";
    const DISCOUNT_MASTER_BADGE_ALIAS = "discount_master";
    const PREMIUMER_ALIAS = "premiumer";
    const PREMIUM_MEMBERSHIP_ALIAS = "premium_membership";

    public function scopeCountryBadge($query)
    {
        $query->where('alias', self::COUNTRY_BADGE_ALIAS);
    }

    public function isCountryBadge()
    {
        return $this->alias == self::COUNTRY_BADGE_ALIAS;
    }

    public function scopeAuthorLevelBadge($query)
    {
        $query->where('alias', self::AUTHOR_LEVEL_BADGE_ALIAS);
    }

    public function isAuthorLevelBadge()
    {
        return $this->alias == self::AUTHOR_LEVEL_BADGE_ALIAS;
    }

    public function scopeMembershipYearsBadge($query)
    {
        $query->where('alias', self::MEMBERSHIP_YEARS_BADGE_ALIAS);
    }

    public function isMembershipYearsBadge()
    {
        return $this->alias == self::MEMBERSHIP_YEARS_BADGE_ALIAS;
    }

    public function scopeExclusiveAuthorBadge($query)
    {
        $query->where('alias', self::EXCLUSIVE_AUTHOR_BADGE_ALIAS);
    }

    protected $fillable = [
        'name',
        'alias',
        'title',
        'image',
        'country',
        'level_id',
        'membership_years',
        'is_permanent',
    ];

    public function getImageLink()
    {
        return asset($this->image);
    }

    public function getFullTitle()
    {
        $fullTitle = $this->name;
        if ($this->title) {
            $fullTitle = $this->name . ': ' . $this->title;
        }
        return $fullTitle;
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}