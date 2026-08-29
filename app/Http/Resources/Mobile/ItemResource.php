<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        $hasDiscount = $this->hasDiscount() && $this->discount && $this->discount->isActive();

        $previewAudio = null;
        if ($this->preview_audio) {
            $previewAudio = function_exists('getLinkFromStorageProvider') 
                ? getLinkFromStorageProvider($this->preview_audio) 
                : asset($this->preview_audio);
        }

        $thumbnail = null;
        if ($this->thumbnail || $this->preview_image) {
            $thumbPath = $this->thumbnail ?: $this->preview_image;
            $thumbnail = function_exists('getLinkFromStorageProvider')
                ? getLinkFromStorageProvider($thumbPath)
                : asset($thumbPath);
        }

        $previewImage = null;
        if ($this->preview_image) {
            $previewImage = function_exists('getLinkFromStorageProvider')
                ? getLinkFromStorageProvider($this->preview_image)
                : asset($this->preview_image);
        }

        $isFavorited = false;
        if (auth('sanctum')->check()) {
            $isFavorited = auth('sanctum')->user()->favorites()->where('item_id', $this->id)->exists();
        }

        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'slug'                => $this->slug,
            'description'         => $this->description,
            'preview_type'        => $this->preview_file_type,
            'preview_audio_url'   => $previewAudio,
            'preview_image_url'   => $previewImage,
            'thumbnail_url'       => $thumbnail,
            'price'               => [
                'regular'          => (float) $this->getRegularPrice(),
                'extended'         => (float) $this->getExtendedPrice(),
                'has_discount'     => (bool) $hasDiscount,
                'discount_regular' => $hasDiscount ? (float) $this->discount->getRegularPrice() : null,
                'discount_percent' => $hasDiscount ? (int) $this->discount->percentage : 0,
            ],
            'is_free'             => (bool) $this->is_free,
            'is_premium'          => (bool) $this->is_premium,
            'is_trending'         => (bool) $this->is_trending,
            'is_best_selling'     => (bool) $this->is_best_selling,
            'is_featured'         => (bool) $this->is_featured,
            'is_favorited'        => (bool) $isFavorited,
            'total_sales'         => (int) $this->total_sales,
            'total_reviews'       => (int) $this->total_reviews,
            'avg_reviews'         => (float) $this->avg_reviews,
            'category'            => $this->category ? [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'subcategory'         => $this->subCategory ? [
                'id'   => $this->subCategory->id,
                'name' => $this->subCategory->name,
                'slug' => $this->subCategory->slug,
            ] : null,
            'author'              => $this->author ? [
                'id'        => $this->author->id,
                'name'      => $this->author->getName(),
                'username'  => $this->author->username,
                'avatar'    => $this->author->avatar ? asset($this->author->avatar) : null,
                'is_author' => (bool) $this->author->is_author,
            ] : null,
            'tags'                => $this->tags ? explode(',', $this->tags) : [],
            'created_at'          => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at'          => $this->updated_at ? $this->updated_at->toISOString() : null,
        ];
    }
}
