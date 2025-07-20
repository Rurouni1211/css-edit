<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_unique_id' => $this->faker->unique()->uuid(),
            'customer_id' => $this->faker->numberBetween(1, 10),
            'status' => OrderStatus::Pending->value,
            'order_type' => OrderType::Product->value, // デフォルトはカスタマイズ品
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'total_amount' => 0,
        ];
    }

    /**
     * カスタマイズ品注文を設定
     */
    public function product(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'order_type' => OrderType::Product->value,
                'product_id' => $this->faker->numberBetween(1, 10),
                'product_name' => $this->faker->words(3, true),
                'item_id' => null,
                'item_name' => null,
            ];
        });
    }

    /**
     * 完成品注文を設定
     */
    public function item(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'order_type' => OrderType::Item->value,
                'product_id' => null,
                'product_name' => null,
                'item_id' => $this->faker->numberBetween(1, 10),
                'item_name' => $this->faker->words(3, true),
            ];
        });
    }
}
