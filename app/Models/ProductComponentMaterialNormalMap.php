<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductComponentMaterialNormalMap extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship
    public function normal_map()
    {
        return $this->belongsTo(MaterialNormalMap::class, 'material_normal_map_id');
    }
}
