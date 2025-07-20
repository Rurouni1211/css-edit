<?php

namespace App\Models;

use App\Events\AdminCreated;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends MultiAuthenticatable
{
    use SoftDeletes;

    protected $dispatchesEvents = [
        'created' => AdminCreated::class,
    ];

    // Relationship
    public function multi_auth_user()
    {
        return $this->belongsTo(MultiAuthUser::class, 'id', 'admin_id');
    }
}
