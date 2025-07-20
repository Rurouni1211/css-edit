<?php

namespace App\Models;

use App\Events\ItemImageDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    protected $dispatchesEvents = [
        'deleted' => ItemImageDeleted::class,
    ];

    // Accessor
    public function getUrlAttribute()
    {
        return asset('storage/items/' . $this->item_id .'/'. $this->filename);
    }

    public function getPathAttribute()
    {
        return storage_path('app/public/items/' . $this->item_id .'/'. $this->filename);
    }
}
