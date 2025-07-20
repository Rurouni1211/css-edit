<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialColorResource extends JsonResource
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
            'name' => $this->name,
            'color_code' => $this->color_code,
            'original_texture_filename' => $this->original_texture_filename,
            'texture_filename' => $this->texture_filename,
            'texture_url' => $this->texture_url,
        ];
    }
}
