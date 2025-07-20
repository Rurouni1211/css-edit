<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $base_data = [
            'id' => $this->id,
            'order_unique_id' => $this->order_unique_id,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'order_type' => $this->order_type,
            'order_type_label' => $this->order_type_label,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'item_id' => $this->item_id,
            'item_name' => $this->item_name,
            'created_date' => $this->created_at->format('Y-m-d'),
            'created_data_time' => $this->created_at->format('Y-m-d H:i:s'),
            'components' => OrderComponentResource::collection($this->whenLoaded('components')),
        ];
        $user_data = $this->getUserData();

        return [
            ...$base_data,
            ...$user_data,
        ];
    }

    private function getUserData()
    {
        $user_data = [];

        if(auth('admin')->check()) {

            $amount_data = $this->getAmountData();
            $shop_data = $this->getShopData();
            $user_data = [
                ...$user_data,
                'component' => $amount_data,
                'shop' => $shop_data,
            ];

        } else if(auth('shop')->check()) {

            $amount_data = $this->getAmountData();
            $user_data = [
                ...$user_data,
                'component' => $amount_data,
            ];

        } else if(auth('artisan')->check()) {

            $shop_data = $this->getShopData();
            $user_data = [
                ...$user_data,
                'shop' => $shop_data,
            ];

        } else {

            // 必要となったら追加してください

        }

        return $user_data;
    }

    private function getAmountData()
    {
        // ローディングされていない場合はロードする
        if (!$this->relationLoaded('components')) {
            $this->load('components');
        }

        $total_amount = $this->total_amount ?? $this->components->sum('amount');

        return [
            'total_amount' => $total_amount,
            'total_amount_text' => number_format($total_amount),
            'total_amount_including_tax' => $this->total_amount_including_tax,
            'total_amount_including_tax_text' => number_format($this->total_amount_including_tax),
            'consumption_tax_rate' => $this->consumption_tax_rate,
            'consumption_tax' => $this->consumption_tax,
            'consumption_tax_text' => number_format($this->consumption_tax),
        ];
    }

    private function getShopData()
    {
        $shop = $this->shop;

        if (!$shop) {
            return [
                'id' => null,
                'name' => null,
            ];
        }

        return [
            'id' => $shop->id,
            'name' => $shop->name,
        ];
    }
}
