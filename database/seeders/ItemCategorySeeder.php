<?php

namespace Database\Seeders;

use Database\Factories\ItemCategoryFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'バッグ', 'sort_number' => 100, 'is_active' => true],
            ['name' => '財布', 'sort_number' => 200, 'is_active' => true],
            ['name' => 'アクセサリー', 'sort_number' => 300, 'is_active' => true],
            ['name' => 'その他', 'sort_number' => 400, 'is_active' => true],
        ];

        foreach ($categories as $category) {

            ItemCategoryFactory::new()->create($category);

        }
    }
}
