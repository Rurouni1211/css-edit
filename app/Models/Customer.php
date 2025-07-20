<?php

namespace App\Models;

use App\Events\CustomerCreated;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends MultiAuthenticatable implements MustVerifyEmail
{
    use SoftDeletes;

    protected $dispatchesEvents = [
        'created' => CustomerCreated::class,
    ];

    // Relationship
    public function multi_auth_user()
    {
        return $this->belongsTo(MultiAuthUser::class, 'id', 'customer_id');
    }
}
