<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderComponentResource extends JsonResource
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
            'order_unique_id' => $this->order_unique_id,
            'key' => $this->key,
            'key_name' => $this->key_name,
            'component_group_type' => $this->component_group_type,
            'amount' => $this->amount,
            'parameters' => $this->parameter_json,
        ];
    }
}
