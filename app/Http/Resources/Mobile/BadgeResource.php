<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class BadgeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        $badge = $this->badge ?? $this;

        $imageLink = null;
        if (is_object($badge)) {
            if (method_exists($badge, 'getImageLink')) {
                $imageLink = $badge->getImageLink();
            } elseif (!empty($badge->image)) {
                $imageLink = asset($badge->image);
            }
        }

        $fullTitle = null;
        if (is_object($badge)) {
            if (method_exists($badge, 'getFullTitle')) {
                $fullTitle = $badge->getFullTitle();
            } else {
                $fullTitle = !empty($badge->title) ? "{$badge->name}: {$badge->title}" : ($badge->name ?? '');
            }
        }

        return [
            'id'         => (int) $this->id, // UserBadge ID used for sorting
            'badge_id'   => (int) ($this->badge_id ?? $badge->id ?? 0),
            'name'       => $badge->name ?? null,
            'alias'      => $badge->alias ?? null,
            'title'      => $badge->title ?? null,
            'full_title' => $fullTitle,
            'image'      => $imageLink,
            'country'    => $badge->country ?? null,
            'sort_id'    => (int) ($this->sort_id ?? 0),
        ];
    }
}
