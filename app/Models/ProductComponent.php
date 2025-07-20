<?php

namespace App\Models;

use App\Enums\ComponentGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductComponent extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function materials()
    {
        return $this->hasMany(ProductComponentMaterial::class, 'product_component_id', 'id');
    }

    // Accessor
    public function getComponentGroupNameAttribute()
    {
        $component_group = ComponentGroup::tryfrom($this->key);

        if(is_null($component_group)) {
            
            return '';

        }

        return $component_group->getLabel();
    }
}
