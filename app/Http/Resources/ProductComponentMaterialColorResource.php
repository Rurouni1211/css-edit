<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductComponentMaterialColorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $color_values = $this->whenLoaded('color', function () {

            return $this->color;

        }, []);

        return [
            'id' => $this->id,
            'material_color_id' => $this->material_color_id,
            'product_id' => $this->product_id,
            'product_component_material_id' => $this->product_component_material_id,
            'is_active' => $this->is_active,
            'name' => $color_values['name'] ?? null,
            'from' => app()->environment('local') ? 'ProductComponentMaterialColorResource': null,
        ];
    }
}
