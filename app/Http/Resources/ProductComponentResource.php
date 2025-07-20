<?php

namespace App\Http\Resources;

use App\Enums\ComponentGroupType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductComponentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $groupKey = ComponentGroupType::fromComponentKey($this->key)->value;

        return [
            'id' => $this->id ?? null,
            'product_id' => $this->product_id ?? null,
            'key' => $this->key ?? null,
            'group_key' => $groupKey,
            'name' => $this->component_group_name ?? null,
            'is_active' => $this->is_active ?? null,
            'materials' => $this->whenLoaded('materials', function () {

                return ProductComponentMaterialResource::collection($this->materials);

            }),
            'from' => app()->environment('local') ? 'ProductComponentResource': null,
        ];
    }
}
