<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemReview extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            self::updateItemAndAuthorReviews($review->author, $review->item);
        });

        static::updated(function ($review) {
            self::updateItemAndAuthorReviews($review->author, $review->item);
        });

        static::deleted(function ($review) {
            self::updateItemAndAuthorReviews($review->author, $review->item);
        });
    }

    protected static function updateItemAndAuthorReviews($author, $item)
    {
        $item->total_reviews = $item->reviews->count();
        $item->avg_reviews = $item->reviews->count() > 0 ? $item->reviews->avg('stars') : 0;
        $item->update();

        $author->total_reviews = $author->reviews->count();
        $author->avg_reviews = $author->reviews->count() > 0 ? $author->reviews->avg('stars') : 0;
        $author->update();
    }

    protected $fillable = [
        'user_id',
        'author_id',
        'item_id',
        'stars',
        'subject',
        'body',
        'created_at',
        'updated_at',
    ];

    public function getLink()
    {
        return route('items.review', [
            $this->item->slug,
            $this->item->id,
            $this->id,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function reply()
    {
        return $this->hasOne(ItemReviewReply::class, 'item_review_id');
    }
}
