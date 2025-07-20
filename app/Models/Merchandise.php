<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchandise extends Model
{
    use HasFactory, SoftDeletes;

    // Relationship
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id', 'id');
    }
}
