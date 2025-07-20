<?php

namespace App\Models;

use App\Events\MaterialSpecularMapDeleted;
use App\Events\MaterialSpecularMapSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MaterialSpecularMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'filename',
        'original_filename',
    ];
    protected $appends = [
        'url',
    ];
    protected $dispatchesEvents = [
        'saved' => MaterialSpecularMapSaved::class,
        'deleted' => MaterialSpecularMapDeleted::class,
    ];

    // Accessor
    public function getPathAttribute()
    {
        return (Str::length($this->filename) > 0)
            ? $this->getPath($this->filename)
            : null;
    }

    public function getUrlAttribute()
    {
        return (Str::length($this->filename) > 0)
            ? url('storage/materials/specular_maps/'. $this->material_id .'/'. $this->filename)
            : null;
    }

    // Others
    public function getPath($filename)
    {
        return storage_path('app/public/materials/specular_maps/'. $this->material_id .'/'. $filename);
    }
}
