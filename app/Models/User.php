<?php

namespace App\Models;

use App\Classes\BrowserDetector;
use App\Classes\IPLookup;
use App\Classes\OSDetector;
use App\Models\Badge;
use App\Models\KycVerification;
use App\Models\UserBadge;
use App\Models\UserLoginLog;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const STATUS_BANNED = 0;
    const STATUS_ACTIVE = 1;

    const KYC_STATUS_UNVERIFIED = 0;
    const KYC_STATUS_VERIFIED = 1;

    const NOT_AUTHOR = 0;
    const AUTHOR = 1;

    const NOT_FEATURED_AUTHOR = 0;
    const FEATURED_AUTHOR = 1;

    const AUTHOR_NON_EXCLUSIVE = "non_exclusive";
    const AUTHOR_EXCLUSIVE = "exclusive";

    const WAS_NOT_SUBSCRIBED = 0;
    const WAS_SUBSCRIBED = 1;

    public function scopeActive($query)
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function scopeBanned($query)
    {
        $query->where('status', self::STATUS_BANNED);
    }

    public function isBanned()
    {
        return $this->status == self::STATUS_BANNED;
    }

    public function scopeEmailVerified($query)
    {
        $query->whereNotNull('email_verified_at');
    }

    public function scopeEmailUnVerified($query)
    {
        $query->whereNull('email_verified_at');
    }

    public function isEmailVerified()
    {
        return $this->email_verified_at != null;
    }

    public function scopeKycVerified($query)
    {
        $query->where('kyc_status', self::KYC_STATUS_VERIFIED);
    }

    public function isKycVerified()
    {
        return $this->kyc_status == self::KYC_STATUS_VERIFIED;
    }

    public function isKycPending()
    {
        if (!$this->isKycVerified()) {
            $kycVerification = KycVerification::where('user_id', $this->id)->pending()->first();
            if ($kycVerification) {
                return true;
            }
        }
        return false;
    }

    public function isKycRequired()
    {
        if (@settings('kyc')->status && @settings('kyc')->required &&
            !$this->isKycVerified()) {
            return true;
        }
        return false;
    }

    public function scopeKycUnVerified($query)
    {
        $query->where('kyc_status', self::KYC_STATUS_UNVERIFIED);
    }

    public function scopeUser($query)
    {
        $query->where('is_author', self::NOT_AUTHOR);
    }

    public function isUser()
    {
        return $this->is_author == self::NOT_AUTHOR;
    }

    public function scopeAuthor($query)
    {
        $query->where('is_author', self::AUTHOR);
    }

    public function isAuthor()
    {
        return $this->is_author == self::AUTHOR;
    }

    public function scopeFeaturedAuthor($query)
    {
        $query->where('is_featured_author', self::FEATURED_AUTHOR);
    }

    public function isFeaturedAuthor()
    {
        return $this->is_featured_author == self::FEATURED_AUTHOR;
    }

    public function isAuthorOrInReferralProgram()
    {
        return $this->isAuthor() || $this->isInReferralProgram();
    }

    public function isExclusiveAuthor()
    {
        return $this->exclusivity == self::AUTHOR_EXCLUSIVE;
    }

    public function isNonExclusiveAuthor()
    {
        return $this->exclusivity == self::AUTHOR_NON_EXCLUSIVE;
    }

    public function has2fa()
    {
        return $this->google2fa_status == 1;
    }

    public function hasWithdrawalAccount()
    {
        return $this->withdrawal_method_id && $this->withdrawal_account;
    }

    public function scopeWhereDataCompleted($query)
    {
        $query->whereNotNull('firstname')
            ->whereNotNull('lastname')
            ->whereNotNull('username')
            ->whereNotNull('email')
            ->whereNotNull('password');
    }

    public function isDataCompleted()
    {
        if (!$this->firstname || !$this->lastname ||
            !$this->username || !$this->email || !$this->password) {
            return false;
        }
        return true;
    }

    public function isFollowingUser($userId)
    {
        return $this->followings()->where('following_id', $userId)->exists();
    }

    public function hasPurchasedItem($itemID)
    {
        return $this->purchases()->where('item_id', $itemID)->active()->exists();
    }

    public function getItemPurchase($itemID)
    {
        if ($this->hasPurchasedItem($itemID)) {
            $purchase = $this->purchases()->where('item_id', $itemID)->first();
            return $purchase;
        }
    }

    public function hasItemInFavorite($itemID)
    {
        return $this->favorites()->where('item_id', $itemID)->exists();
    }

    public function isSubscribed()
    {
        return !is_null($this->subscription);
    }

    public function subscribedToPlan($planId)
    {
        return $this->subscription && $this->subscription->plan->id == $planId;
    }

    public function wasSubscribed()
    {
        return $this->was_subscribed == self::WAS_SUBSCRIBED;
    }

    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'address',
        'password',
        'api_key',
        'is_author',
        'balance',
        'level_id',
        'exclusivity',
        'total_sales',
        'total_sales_amount',
        'total_referrals_earnings',
        'total_reviews',
        'avg_reviews',
        'total_followers',
        'total_following',
        'avatar',
        'profile_cover',
        'profile_heading',
        'profile_description',
        'profile_contact_email',
        'profile_social_links',
        'facebook_id',
        'google_id',
        'microsoft_id',
        'vkontakte_id',
        'envato_id',
        'github_id',
        'withdrawal_method_id',
        'withdrawal_account',
        'was_subscribed',
        'kyc_status',
        'google2fa_status',
        'google2fa_secret',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'profile_social_links' => 'object',
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

    public function getCountry()
    {
        return @$this->address->country ? countries(@$this->address->country) : null;
    }

    public function getAvatar()
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }
        return asset(@settings('profile')->default_avatar);
    }

    public function getProfileCover()
    {
        if ($this->profile_cover) {
            return asset($this->profile_cover);
        }
        return asset(@settings('profile')->default_cover);
    }

    public function getProfileLink()
    {
        if ($this->username) {
            return route('profile.index', strtolower($this->username));
        }
        return route('home');
    }

    public function getPortfolioLink()
    {
        if ($this->username) {
            return route('profile.portfolio', strtolower($this->username));
        }
        return route('home');
    }

    public function getReferralLink()
    {
        if ($this->username) {
            return route('home', 'ref=' . strtolower($this->username));
        }
        return route('home');
    }

    public function getReferredBy()
    {
        return $this->referred_by ? $this->referred_by->referring_user : null;
    }

    public function getGoogle2faSecretAttribute($value)
    {
        return decrypt($value);
    }

    public function registerLoginLog()
    {
        $ip = getIp();
        $ipLookup = app(IPLookup::class)->lookup($ip);
        $loginLog = UserLoginLog::where('user_id', $this->id)->where('ip', $ip)->first();
        if (!$loginLog) {
            $loginLog = new UserLoginLog();
            $loginLog->user_id = $this->id;
            $loginLog->ip = $ipLookup->ip;
        }
        $loginLog->country = $ipLookup->country;
        $loginLog->country_code = $ipLookup->country_code;
        $loginLog->timezone = $ipLookup->timezone;
        $loginLog->location = $ipLookup->location;
        $loginLog->latitude = $ipLookup->latitude;
        $loginLog->longitude = $ipLookup->longitude;
        $loginLog->browser = BrowserDetector::get();
        $loginLog->os = OSDetector::get();
        $loginLog->save();
    }

    public function addBadge($badge)
    {
        if ($badge) {
            if (!$this->badges()->where('badge_id', $badge->id)->exists()) {
                $userBadge = $this->badges()->where('badge_alias', $badge->alias)->first();
                if (!$userBadge) {
                    $userBadge = new UserBadge();
                    $userBadge->sort_id = (UserBadge::count() + 1);
                }
                $userBadge->user_id = $this->id;
                $userBadge->badge_id = $badge->id;
                $userBadge->badge_alias = $badge->alias;
                $userBadge->save();
            }
        }
    }

    public function addCountryBadge($country = null)
    {
        $badge = Badge::where('country', $country)->countryBadge()->first();
        if ($badge) {
            $this->addBadge($badge);
        } else {
            $badge = Badge::where('country', null)->countryBadge()->first();
            $this->addBadge($badge);
        }
    }

    public function addExclusiveAuthorBadge()
    {
        if ($this->isAuthor()) {
            $badge = Badge::exclusiveAuthorBadge()->first();
            if ($badge) {
                if ($this->isExclusiveAuthor()) {
                    $this->addBadge($badge);
                } else {
                    $this->removeBadge($badge);
                }
            }
        }
    }

    public function removeBadge($badge)
    {
        if ($badge) {
            $userBadge = $this->badges()->where('badge_id', $badge->id)->first();
            if ($userBadge) {
                $userBadge->delete();
            }
        }
    }

    public function emptyCart()
    {
        $cartItems = $this->cartItems;
        foreach ($cartItems as $cartItem) {
            $cartItem->delete();
        }
    }

    public function deleteResources()
    {
        $followers = $this->followers;
        if ($followers->count() > 0) {
            foreach ($followers as $follower) {
                $follower->delete();
            }
        }

        $followings = $this->followings;
        if ($followings->count() > 0) {
            foreach ($followings as $following) {
                $following->delete();
            }
        }

        $kycVerifications = $this->kycVerifications;
        if ($kycVerifications->count() > 0) {
            foreach ($kycVerifications as $kycVerification) {
                $kycVerification->delete();
            }
        }

        $itemComments = $this->itemComments;
        if ($itemComments->count() > 0) {
            foreach ($itemComments as $itemComment) {
                $itemComment->delete();
            }
        }

        $itemReviews = $this->itemReviews;
        if ($itemReviews->count() > 0) {
            foreach ($itemReviews as $itemReview) {
                $itemReview->delete();
            }
        }

        $items = $this->items;
        if ($items->count() > 0) {
            foreach ($items as $item) {
                $item->delete();
            }
        }

        $transactions = $this->transactions;
        if ($transactions->count() > 0) {
            foreach ($transactions as $transaction) {
                $transaction->delete();
            }
        }

        $tickets = $this->tickets;
        if ($tickets->count() > 0) {
            foreach ($tickets as $ticket) {
                $ticket->delete();
            }
        }

        removeFile($this->avatar);
        removeFile($this->profile_cover);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, 'password.reset'));
    }

    public function sendEmailVerificationNotification()
    {
        if (@settings('actions')->email_verification) {
            $this->notify(new VerifyEmailNotification());
        }
    }

    public function kycVerifications()
    {
        return $this->hasMany(KycVerification::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'following_id');
    }

    public function followings()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'author_id');
    }

    public function itemComments()
    {
        return $this->hasMany(ItemComment::class);
    }

    public function itemReviews()
    {
        return $this->hasMany(ItemReview::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function badges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'author_id');
    }

    public function referral()
    {
        return $this->hasOne(Referral::class, 'user_id');
    }

    public function withdrawalMethod()
    {
        return $this->belongsTo(WithdrawalMethod::class);
    }

    public function reviews()
    {
        return $this->hasMany(ItemReview::class, 'author_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'author_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}