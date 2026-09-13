<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->resource) {
            return [];
        }

        $createdAt = null;
        if ($this->created_at) {
            $createdAt = (is_object($this->created_at) && method_exists($this->created_at, 'toISOString'))
                ? $this->created_at->toISOString()
                : (string) $this->created_at;
        }

        $replyCreatedAt = null;
        if ($this->reply && $this->reply->created_at) {
            $replyCreatedAt = (is_object($this->reply->created_at) && method_exists($this->reply->created_at, 'toISOString'))
                ? $this->reply->created_at->toISOString()
                : (string) $this->reply->created_at;
        }

        return [
            'id'         => (int) $this->id,
            'stars'      => (int) ($this->stars ?? $this->rating ?? 0),
            'rating'     => (int) ($this->stars ?? $this->rating ?? 0),
            'subject'    => $this->subject,
            'body'       => $this->body,
            'user'       => $this->user ? [
                'id'        => (int) $this->user->id,
                'username'  => $this->user->username,
                'firstname' => $this->user->firstname,
                'lastname'  => $this->user->lastname,
                'fullname'  => method_exists($this->user, 'getName') ? $this->user->getName() : trim(($this->user->firstname ?? '') . ' ' . ($this->user->lastname ?? '')),
                'avatar'    => $this->user->avatar ? asset($this->user->avatar) : null,
            ] : null,
            'item'       => $this->item ? [
                'id'            => (int) $this->item->id,
                'name'          => $this->item->name,
                'slug'          => $this->item->slug,
                'thumbnail_url' => method_exists($this->item, 'getThumbnailLink') ? $this->item->getThumbnailLink() : ($this->item->thumbnail ? asset($this->item->thumbnail) : null),
                'regular_price' => (float) $this->item->regular_price,
            ] : null,
            'reply'      => $this->reply ? [
                'id'         => (int) $this->reply->id,
                'body'       => $this->reply->body,
                'created_at' => $replyCreatedAt,
                'author'     => $this->reply->user ? [
                    'id'        => (int) $this->reply->user->id,
                    'username'  => $this->reply->user->username,
                    'fullname'  => method_exists($this->reply->user, 'getName') ? $this->reply->user->getName() : trim(($this->reply->user->firstname ?? '') . ' ' . ($this->reply->user->lastname ?? '')),
                    'avatar'    => $this->reply->user->avatar ? asset($this->reply->user->avatar) : null,
                ] : null,
            ] : null,
            'created_at' => $createdAt,
        ];
    }
}
