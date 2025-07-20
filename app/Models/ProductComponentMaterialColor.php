<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductComponentMaterialColor extends Model
{
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship
    public function color()
    {
        return $this->belongsTo(MaterialColor::class, 'material_color_id');
    }
}
