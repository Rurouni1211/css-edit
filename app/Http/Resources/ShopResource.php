<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
        ];

        if(auth('admin')->check()) { // 管理者のみ有効なデータ

            $data['email'] = $this->email ?? null;

        }

        return $data;
    }
}
