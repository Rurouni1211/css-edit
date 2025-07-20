<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductComponentMaterialResource extends JsonResource
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
            'material_id' => $this->material_id,
            'button_image_url' => $this->whenLoaded('material', function () {

                return $this->material->button_image_url;

            }),
            'amount' => $this->amount,
            'is_active' => $this->is_active,
            'colors' => $this->whenLoaded('colors', function () {

                return ProductComponentMaterialColorResource::collection($this->colors);

            }),
            'normal_maps' => $this->whenLoaded('normal_maps', function () {

                return ProductComponentMaterialNormalMapResource::collection($this->normal_maps);

            }),
            'from' => app()->environment('local') ? 'ProductComponentMaterialResource': null,
        ];
    }
}
