<?php

namespace App\Http\Resources;

use App\Models\Admin;
use App\Models\Artisan;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if(auth()->check()) {

            $user = $request->user();
            $valid_models = [
                Admin::class,
                Artisan::class,
                Shop::class,
            ];

            if($user && in_array(get_class($user), $valid_models)) {

                return [
                    'id' => $this->id,
                    'path' => $this->path,
                    'url' => $this->url,
                ];

            }

        }

        return [
            'id' => $this->id,
            'url' => $this->url,
        ];
    }
}
