<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
            'key' => $this->key,
            'glossiness' => $this->glossiness,
            'specular' => $this->specular,
            'button_image_url' => $this->button_image_url,
            'colors' => MaterialColorResource::collection($this->whenLoaded('colors')),
            'normal_maps' => MaterialNormalMapResource::collection($this->whenLoaded('normal_maps')),
            'specular_map' => MaterialSpecularMapResource::make($this->whenLoaded('specular_map')),
        ];
    }
}
