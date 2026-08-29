<?php

namespace App\Jobs\Author;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuthorItemCommentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;
    public $commentReply;

    public function __construct($comment, $commentReply)
    {
        $this->comment = $comment;
        $this->commentReply = $commentReply;
    }

    public function handle()
    {
        $comment = $this->comment;
        $commentReply = $this->commentReply;
        $author = $comment->author;

        SendMail::send($author->email, 'author_item_comment', [
            'author_username' => $author->username,
            'user_username' => $commentReply->user->username,
            'comment' => $commentReply->body,
            'item_name' => $comment->item->name,
            'item_link' => $comment->item->getLink(),
            'comment_link' => $comment->getLink(),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}