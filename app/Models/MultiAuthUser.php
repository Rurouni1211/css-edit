<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultiAuthUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_type',
        'customer_id',
        'admin_id',
        'shop_id',
        'artisan_id',
    ];

    protected $appends = [
        'user',
    ];

    // Relationship
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id', 'admin_id');
    }

    public function artisan()
    {
        return $this->hasOne(Artisan::class, 'id', 'artisan_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }

    // Accessor
    public function getUserAttribute()
    {
        $users = [
            UserType::Admin->value => $this->admin,
            UserType::Artisan->value => $this->artisan,
            UserType::Customer->value => $this->customer,
            UserType::Shop->value => $this->shop,
        ];

        return data_get($users, $this->user_type, null);
    }

    public function getUserTypeLabelAttribute()
    {
        return UserType::from($this->user_type)->getLabel() ?? '';
    }
}
