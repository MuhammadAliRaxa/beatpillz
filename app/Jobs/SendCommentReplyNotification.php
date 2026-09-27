<?php

namespace App\Jobs;

use App\Classes\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCommentReplyNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $commentReply;

    public function __construct($commentReply)
    {
        $this->commentReply = $commentReply;
    }

    public function handle(): void
    {
        $commentReply = $this->commentReply;
        $comment = $commentReply->comment;
        $user = $comment->user;
        $author = $comment->author;
        $item = $comment->item;

        if ($author->id == $commentReply->user->id && $user->id != $author->id) {
            $mailTemplate = 'item_comment_reply';
            $email = $user->email;
        } else {
            $mailTemplate = 'author_item_comment_reply';
            $email = $author->email;
        }

        SendMail::send($email, $mailTemplate, [
            'author_username' => $author->username,
            'user_username' => $user->username,
            'comment_reply' => $commentReply->body,
            'item_name' => $item->name,
            'item_link' => $item->getLink(),
            'comment_link' => $comment->getLink(),
            'website_name' => @settings('general')->site_name,
        ]);
    }
}