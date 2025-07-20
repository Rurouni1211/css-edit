<?php

namespace App\Events;

use App\Models\MaterialSpecularMap;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class MaterialSpecularMapSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(MaterialSpecularMap $material_specular_map)
    {
        if(app()->runningInConsole()) {
            return;
        }

        $old_filename = $material_specular_map->getOriginal('filename');

        if($old_filename !== $material_specular_map->filename) {

            $path = $material_specular_map->getPath($old_filename);
            File::delete($path);

        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
