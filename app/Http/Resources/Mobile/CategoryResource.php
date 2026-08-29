<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'slug'           => $this->slug,
            'description'    => $this->description,
            'icon'           => $this->icon,
            'items_count'    => (int) ($this->items_count ?? ($this->items ? $this->items()->approved()->count() : 0)),
            'subcategories'  => $this->subCategories ? $this->subCategories->map(function ($sub) {
                return [
                    'id'          => $sub->id,
                    'name'        => $sub->name,
                    'slug'        => $sub->slug,
                    'items_count' => (int) ($sub->items_count ?? ($sub->items ? $sub->items()->approved()->count() : 0)),
                ];
            }) : [],
        ];
    }
}
