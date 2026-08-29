<?php

namespace App\Jobs\Author;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuthorItemReviewNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $review;

    public function __construct($review)
    {
        $this->review = $review;
    }

    public function handle()
    {
        $review = $this->review;
        $item = $review->item;
        $author = $review->author;

        SendMail::send($author->email, 'author_item_review', [
            'author_username' => $author->username,
            'user_username' => $review->user->username,
            'stars' => $review->stars,
            'subject' => $review->subject,
            'review' => $review->body,
            'item_name' => $item->name,
            'item_link' => $item->getLink(),
            'review_link' => $review->getLink(),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}
