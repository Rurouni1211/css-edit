<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SketchfabModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this['name'],
            'uid' => $this['uid'],
            'uri' => $this['uri'],
            'viewer_url' => $this['viewerUrl'],
            'description' => $this['description'],
            'is_private' => $this['isPrivate'],
        ];
    }
}
