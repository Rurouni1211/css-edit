<?php

namespace App\Models;

use App\Events\ArtisanCreated;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artisan extends MultiAuthenticatable
{
    use SoftDeletes;

    protected $dispatchesEvents = [
        'created' => ArtisanCreated::class,
    ];

    // Relationship
    public function multi_auth_user()
    {
        return $this->belongsTo(MultiAuthUser::class, 'id', 'artisan_id');
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
