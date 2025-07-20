<?php

namespace Feature\Admin;

use App\Models\Admin;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Shop;
use Illuminate\Support\Str;
use Tests\TestCase;

class ItemTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::first();
    }

    public function test_can_search_item()
    {
        $this->actingAs($this->admin, 'admin');

        $item = Item::query()
            ->inRandomOrder()
            ->first();

        $url = route('admin.item.list') .'?q='. urlencode($item->name);
        $response = $this->get($url)->assertStatus(200);
        $count = count($response->json());
        $this->assertGreaterThan(0, $count);
    }

    public function test_can_add_item(): void
    {
        $this->actingAs($this->admin, 'admin');

        $shop = Shop::query()
            ->inRandomOrder()
            ->first();
        $item_category = ItemCategory::query()
            ->inRandomOrder()
            ->first();
        $item_name = 'Item Name'. Str::random(5);
        $item_code = 'Item Code'. Str::random(5);
        $description = 'Item Description'. Str::random(5);
        $shop_id = $shop->id;
        $sort_number = rand(1, 100);
        $category_id = $item_category->id;
        $price = rand(1000, 10000);
        $params = [
            'name' => $item_name,
            'item_code' => $item_code,
            'description' => $description,
            'shop_id' => $shop_id,
            'sort_number' => $sort_number,
            'category_id' => $category_id,
            'price' => $price,
        ];

        $this->post(route('admin.item.store'), $params)->assertStatus(200);
        $this->assertDatabaseHas('items', $params);
    }

    public function test_can_update_item(): void
    {
        $this->actingAs($this->admin, 'admin');

        $shop = Shop::query()
            ->inRandomOrder()
            ->first();
        $item_category = ItemCategory::query()
            ->inRandomOrder()
            ->first();
        $item_name = 'Item Name'. Str::random(5);
        $item_code = 'Item Code'. Str::random(5);
        $description = 'Item Description'. Str::random(5);
        $shop_id = $shop->id;
        $sort_number = rand(1, 100);
        $category_id = $item_category->id;
        $price = rand(1000, 10000);
        $params = [
            'name' => $item_name,
            'item_code' => $item_code,
            'description' => $description,
            'shop_id' => $shop_id,
            'sort_number' => $sort_number,
            'category_id' => $category_id,
            'price' => $price,
        ];

        $item = Item::query()
            ->inRandomOrder()
            ->first();
        $this->put(route('admin.item.update', $item->id), $params)->assertStatus(200);
        $this->assertDatabaseHas('items', $params);
    }

    public function test_can_delete_item(): void
    {
        $this->actingAs($this->admin, 'admin');

        $item = Item::query()
            ->inRandomOrder()
            ->first();
        $this->delete(route('admin.item.destroy', $item->id))->assertStatus(200);
        $this->assertSoftDeleted('items', ['id' => $item->id]);
    }
}
