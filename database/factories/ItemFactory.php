<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'item_code' => $this->faker->unique()->numberBetween(1000, 9999),
            'price' => $this->faker->numberBetween(100, 10000),
            'description' => $this->faker->sentence,
            'category_id' => $this->faker->numberBetween(1, 4),
            'shop_id' => $this->faker->numberBetween(1, 4),
            'sort_number' => $this->faker->numberBetween(1, 10),
        ];
    }
}
