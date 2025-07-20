<?php

namespace App\Models;

use App\Events\MaterialNormalMapDeleted;
use App\Events\MaterialNormalMapSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MaterialNormalMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'name',
        'original_filename',
        'filename',
    ];
    protected $dispatchesEvents = [
        'saved' => MaterialNormalMapSaved::class,
        'deleted' => MaterialNormalMapDeleted::class,
    ];
    protected $appends = [
        'url',
    ];

    // Relationship
    public function product_component_material_normal_maps()
    {
        return $this->hasMany(ProductComponentMaterialNormalMap::class, 'material_normal_map_id', 'id');
    }

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
            ? url('storage/materials/normal_maps/'. $this->material_id .'/'. $this->filename)
            : null;
    }

    // Others
    public function getPath($filename)
    {
        return storage_path('app/public/materials/normal_maps/'. $this->material_id .'/'. $filename);
    }
}
