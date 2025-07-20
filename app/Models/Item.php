<?php

namespace App\Models;

use App\Events\ItemCreated;
use App\Events\ItemDeleting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $dispatchesEvents = [
        'created' => ItemCreated::class,
        'deleting' => ItemDeleting::class,
    ];

    // Relationship
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class, 'item_id', 'id')
            ->orderBy('sort_number', 'asc');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'id', 'item_id');
    }

    // Accessor
    public function getDetailUrlAttribute()
    {
//        return route('items.show', $this); // TODO: ルーティングの確認
    }

    // Locals scope
    public function scopeWhereSearch($query, Request $request)
    {
        $query->when($request->q, function($query, $keyword) {

            $query->where('name', 'LIKE', '%'. $keyword .'%')
                ->orWhere('item_code', 'LIKE', '%'. $keyword .'%');

        });
    }
}
