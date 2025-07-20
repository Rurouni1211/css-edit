<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductComponentMaterialNormalMapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $normal_map_values = $this->whenLoaded('normal_map', function () {

            return $this->normal_map;

        }, []);

        return [
            'id' => $this->id,
            'material_normal_map_id' => $this->material_normal_map_id,
            'product_id' => $this->product_id,
            'product_component_material_id' => $this->product_component_material_id,
            'is_active' => $this->is_active,
            'name' => $normal_map_values['name'] ?? null,
            'from' => app()->environment('local') ? 'ProductComponentMaterialNormalMapResource': null,
        ];
    }
}
