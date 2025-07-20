<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\UserType;
use App\Events\OrderSaved;
use App\Events\OrderCreating;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $dispatchesEvents = [
        'saved' => OrderSaved::class,
        'creating' => OrderCreating::class,
    ];

    // Relationship
    public function components()
    {
        return $this->hasMany(OrderComponent::class, 'order_id', 'id');
    }

    public function status_histories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    // Accessor
    public function getTotalAmountIncludingTaxAttribute()
    {
        return $this->total_amount + $this->consumption_tax;
    }

    public function getStatusLabelAttribute()
    {
        return OrderStatus::from($this->status)->getLabel() ?? '';
    }

    public function getOrderTypeLabelAttribute($value)
    {
        $orderTypeValue = $this->order_type;
        $orderType = OrderType::tryFrom($orderTypeValue);

        return ! is_null($orderType) ? $orderType->getLabel() : '';
    }

    public function getProductNameAttribute()
    {
        if ($this->order_type === OrderType::Product->value) {
            return $this->product->name;
        }
        return $this->item_name;
    }

    // Local scope
    public function scopeWhereSearch($query, $request)
    {
        $query
            ->when($request->customer_name, function($query, $name) {
                return $query->where('customer_name', 'like', '%'. $name .'%');
            })
            ->when($request->order_unique_id, function($query, $id) {
                return $query->where('order_unique_id', 'like', '%'. $id .'%');
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->order_type, function($query, $type) {
                return $query->where('order_type', $type);
            })
            ->when($request->product_name, function($query, $name) {
                return $query->where(function($q) use ($name) {
                    $q->where('product_name', 'like', '%'. $name .'%')
                      ->orWhere('item_name', 'like', '%'. $name .'%');
                });
            });

        $multi_auth_guard = multi_auth_guard();
        $user_type = UserType::tryFrom($multi_auth_guard);

        if($user_type === UserType::Shop) {
            $shop = auth($multi_auth_guard)->user();
            $query->where('shop_id', $shop->id);
        }
    }

    // Others
    public function saveTotalAmountForOrder()
    {
        if ($this->order_type === OrderType::Product->value) {
            // カスタマイズ品の場合は構成パーツから合計金額を計算
            $all_components = $this->load('components');
            $total_amount = $all_components->components->sum('amount');
        }
        else if ($this->order_type === OrderType::Item->value && $this->item_id) {
            // 完成品の場合はitem_idから金額を取得
            $item = $this->item;
            $total_amount = $item ? $item->price : 0;
        } else {
            throw new \Exception('Invalid order type or item ID not set.');
        }

        $tax_rate = $this->getConsumptionTaxRate();

        $this->total_amount = $total_amount;
        $this->consumption_tax_rate = $tax_rate;
        $this->consumption_tax = floor($total_amount * $tax_rate * 0.01);

        $this->saveQuietly();
    }

    private function getConsumptionTaxRate()
    {
        $dt_for_10_percent = Carbon::create(2019, 10, 1, 0, 0, 0);

        // 注文があった日時で判別する

        // 消費税が上がったらここに追加してください
        if($this->created_at->gte($dt_for_10_percent)) {
            return 10;
        }

        return 10;
    }
}
