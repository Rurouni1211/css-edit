<?php

namespace App\Models;

use App\Events\ShopCreated;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends MultiAuthenticatable
{
    use SoftDeletes;

    protected $dispatchesEvents = [
        'created' => ShopCreated::class,
    ];

    // Relationship
    public function multi_auth_user()
    {
        return $this->belongsTo(MultiAuthUser::class, 'id', 'shop_id');
    }

    // Local Scope
    public function scopeWhereSearch($query, $request)
    {
        $query->when($request->q, function ($query, $keyword) {

            $query->where('name', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%");

        });
    }
}
