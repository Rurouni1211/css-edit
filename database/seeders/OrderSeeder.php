<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Product;
use App\Models\ProductComponent;
use App\Models\OrderComponent;
use App\Models\Shop;
use Database\Factories\OrderFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::find([1, 2]);
        $shops = Shop::limit(3)->get();
        $products = Product::get();
        $items = Item::get();
        $order_statuses = OrderStatus::getValues(['none']);
        $order_types = OrderType::getValues();

        // 20件の注文を作成
        for($i = 0; $i < 20; $i++) {
            $customer = $customers->random();
            $shop = $shops->random();
            $order_unique_id = Str::uuid();
            // ランダムに注文タイプを決定
            $order_type = Arr::random($order_types);
            
            $order_data = [
                'order_unique_id' => $order_unique_id,
                'customer_id' => $customer->id,
                'shop_id' => $shop->id,
                'status' => Arr::random($order_statuses),
                'order_type' => $order_type,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
            ];

            // 注文タイプに応じて必要な情報を追加
            if ($order_type === OrderType::Product->value) {
                // カスタマイズ品の場合はproductから情報取得
                if ($products->count() > 0) {
                    $product = $products->random();
                    $order_data['product_id'] = $product->id;
                    $order_data['product_name'] = $product->name;
                    $order_data['item_id'] = null;
                    $order_data['item_name'] = null;
                    $order_data['order_type'] = OrderType::Product->value;
                } else {
                    // もしproductsがなければ、スキップ
                    continue;
                }
            } else {
                // 完成品の場合はitemから情報取得
                if ($items->count() > 0) {
                    $item = $items->random();
                    $order_data['item_id'] = $item->id;
                    $order_data['item_name'] = $item->name;
                    $order_data['product_id'] = null;
                    $order_data['product_name'] = null;
                    $order_data['order_type'] = OrderType::Item->value;
                }
            }

            // 注文データを作成
            $order = OrderFactory::new()->create($order_data);

            if ($order_type === OrderType::Product->value) {
                for($component = 1; $component <= 3; $component++) {
                    $orderComponent = new OrderComponent();
                    $orderComponent->order_unique_id = $order->order_unique_id;
                    $orderComponent->order_id = $order->id;
                    $orderComponent->product_id = $order->product_id;
                    $orderComponent->key = 'component_' . $component;
                    $orderComponent->parameter_json = json_encode([
                        'color' => 'red',
                        'size' => 'L',
                    ]);
                    $orderComponent->amount = 1000 * rand(1, 10);
                    $orderComponent->save();
                }
            } else {

                $order->saveTotalAmountForOrder();

            }
        }
    }
}
