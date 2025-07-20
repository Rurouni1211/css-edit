<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'item_code' => $this->item_code,
            'price' => $this->price,
            'description' => $this->description,
            'shop_id' => $this->shop_id,
            'sort_number' => $this->sort_number,
            'detail_url' => $this->detail_url,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function() {

                return ItemCategoryResource::make($this->category);

            }),
            'shop' => $this->whenLoaded('shop', function() {

                return ShopResource::make($this->shop);

            }),
            'images' => $this->whenLoaded('images', function() {

                return ItemImageResource::collection($this->images);

            }),
        ];
    }
}
