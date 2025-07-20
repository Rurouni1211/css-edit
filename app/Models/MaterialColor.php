<?php

namespace App\Models;

use App\Events\MaterialColorDeleted;
use App\Events\MaterialColorSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MaterialColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'name',
        'color_code',
        'original_texture_filename',
        'texture_filename',
    ];
    protected $dispatchesEvents = [
        'saved' => MaterialColorSaved::class,
        'deleted' => MaterialColorDeleted::class,
    ];
    protected $appends = [
        'texture_url',
        'texture_small_image_url',
    ];

    // Relationship
    public function product_component_material_colors()
    {
        return $this->hasMany(ProductComponentMaterialColor::class, 'material_color_id', 'id');
    }

    // Accessor
    public function getTexturePathAttribute()
    {
        return (Str::length($this->texture_filename) > 0)
            ? $this->getPath($this->texture_filename)
            : null;
    }

    public function getTextureUrlAttribute()
    {
        return (Str::length($this->texture_filename) > 0)
            ? url('storage/materials/textures/'. $this->material_id .'/'. $this->texture_filename)
            : null;
    }

    public function getSmallTextureUrlAttribute()
    {
        return (Str::length($this->texture_filename) > 0)
            ? route('store.product_image.texture_small_image', [
                'material_id' => $this->material_id,
                'filename' => $this->texture_filename
            ])
            : null;
    }

    public function getTextureSmallImageUrlAttribute()
    {
        return (Str::length($this->texture_filename) > 0)
            ? route('store.product_image.texture_small_image', [
                'material_id' => $this->material_id,
                'filename' => $this->texture_filename
            ])
            : null;
    }

    // Others
    public function getPath($filename)
    {
        return storage_path('app/public/materials/textures/'. $this->material_id .'/'. $filename);
    }
}
