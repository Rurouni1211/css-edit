<?php

namespace Feature\Admin;

use App\Enums\ComponentGroup;
use App\Enums\MaterialCombinationType;
use App\Models\Admin;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::first();
    }

    public function test_can_search_product()
    {
        $this->actingAs($this->admin, 'admin');

        $product = Product::query()
            ->inRandomOrder()
            ->first();

        $url = route('admin.product.list') .'?q='. urlencode($product->name);
        $response = $this->get($url)->assertStatus(200);
        $count = count($response->json());
        $this->assertGreaterThan(0, $count);
    }

    public function test_can_add_product(): void
    {
        $this->actingAs($this->admin, 'admin');

        $shop = Shop::query()
            ->inRandomOrder()
            ->first();
        $material = Material::query()
            ->with('colors', 'normal_maps')
            ->inRandomOrder()
            ->has('colors')
            ->has('normal_maps')
            ->first();
        $material_combination_type_keys = array_map(function ($item) {

            return $item['value'];

        }, MaterialCombinationType::getCollection());
        $component_group_keys = array_map(function ($item) {

            return $item['value'];

        }, ComponentGroup::getCollection());

        $category = ProductCategory::query()
            ->inRandomOrder()
            ->first();

        $url = route('admin.product.store');
        $components = [
            [
                'is_active' => true,
                'key' => Arr::random($component_group_keys),
                'materials' => [
                    [
                        'is_active' => true,
                        'amount' => Arr::random([10000, 20000, 30000]),
                        'material_id' => $material->id,
                        'colors' => [
                            [
                                'is_active' => true,
                                'material_color_id' => $material->colors->first()->id,
                            ]
                        ],
                        'normal_maps' => [
                            [
                                'is_active' => true,
                                'material_normal_map_id' => $material->normal_maps->first()->id,
                            ]
                        ],
                    ]
                ]
            ]
        ];
        $data = [
            'product' => [
                'name' => 'テスト商品'. Str::random(),
                'product_code' => Str::random(),
                'shop_id' => $shop->id,
                'sort_number' => Arr::random([1, 2, 3, 50, 100]),
                'sketchfab_model_key' => Str::random(),
                'material_combination_type' => Arr::random($material_combination_type_keys),
                'category_id' => $category->id,
            ],
            'components' => $components,
        ];
        $response = $this->post($url, $data);
        $response->assertStatus(200);
    }

    public function test_can_update_product()
    {
        $this->actingAs($this->admin, 'admin');

        $shop = Shop::query()
            ->inRandomOrder()
            ->first();
        $product = Product::query()
            ->inRandomOrder()
            ->first();
        $material = Material::query()
            ->with('colors', 'normal_maps')
            ->inRandomOrder()
            ->has('colors')
            ->has('normal_maps')
            ->first();
        $material_combination_type_keys = array_map(function ($item) {

            return $item['value'];

        }, MaterialCombinationType::getCollection());
        $component_group_keys = array_map(function ($item) {

            return $item['value'];

        }, ComponentGroup::getCollection());

        $category = ProductCategory::query()
            ->inRandomOrder()
            ->first();

        $url = route('admin.product.store');
        $components = [
            [
                'is_active' => true,
                'key' => Arr::random($component_group_keys),
                'materials' => [
                    [
                        'is_active' => true,
                        'amount' => Arr::random([10000, 20000, 30000]),
                        'material_id' => $material->id,
                        'colors' => [
                            [
                                'is_active' => true,
                                'material_color_id' => $material->colors->first()->id,
                            ]
                        ],
                        'normal_maps' => [
                            [
                                'is_active' => true,
                                'material_normal_map_id' => $material->normal_maps->first()->id,
                            ]
                        ],
                    ]
                ]
            ]
        ];
        $data = [
            'product' => [
                'id' => $product->id,
                'name' => 'テスト商品'. Str::random(),
                'product_code' => Str::random(),
                'shop_id' => $shop->id,
                'sort_number' => Arr::random([1, 2, 3, 50, 100]),
                'sketchfab_model_key' => Str::random(),
                'material_combination_type' => Arr::random($material_combination_type_keys),
                'category_id' => $category->id,
            ],
            'components' => $components,
        ];
        $response = $this->post($url, $data);
        $response->assertStatus(200);
    }

    public function test_can_delete_product()
    {
        $this->actingAs($this->admin, 'admin');

        $product = Product::query()
            ->inRandomOrder()
            ->first();

        $url = route('admin.product.destroy', $product);
        $this->delete($url)->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
