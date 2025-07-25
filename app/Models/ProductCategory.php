<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'sort_number' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationship
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    // Local Scope
    public function scopeWhereSearch($query, $request)
    {
        $query->when($request->q, function ($query, $keyword) {

            $query->where('name', 'like', '%' . $keyword . '%');

        });
    }
}
