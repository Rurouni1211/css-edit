<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\OrderStatusHistoryCreated;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'multi_auth_user_id',
        'status_from',
        'status_to',
    ];

    protected $dispatchesEvents = [
        'created' => OrderStatusHistoryCreated::class,
    ];

    // Relationship
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function multi_auth_user()
    {
        return $this->belongsTo(MultiAuthUser::class, 'multi_auth_user_id', 'id');
    }

    // Accessor
    public function getStatusFromLabelAttribute()
    {
        return OrderStatus::from($this->status_from)->getLabel();
    }

    public function getStatusToLabelAttribute()
    {
        return OrderStatus::from($this->status_to)->getLabel();
    }
}
