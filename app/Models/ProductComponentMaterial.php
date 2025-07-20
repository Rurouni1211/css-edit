<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductComponentMaterial extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }

    public function colors()
    {
        return $this->hasMany(ProductComponentMaterialColor::class, 'product_component_material_id', 'id');
    }

    public function normal_maps()
    {
        return $this->hasMany(ProductComponentMaterialNormalMap::class, 'product_component_material_id', 'id');
    }
}
