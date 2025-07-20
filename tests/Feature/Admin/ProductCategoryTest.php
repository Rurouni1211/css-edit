<?php

namespace Feature\Admin;

use App\Models\Admin;
use Database\Factories\ProductCategoryFactory;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::first();
    }

    public function test_can_add_category(): void
    {
        $this->actingAs($this->admin, 'admin');

        $url = route('admin.product_category.store');
        $data = [
            'name' => 'テストカテゴリ名'. Str::random(),
            'sort_number' => 1,
            'is_active' => true,
        ];
        $this->post($url, $data)->assertStatus(200);

        $this->assertDatabaseHas('product_categories', $data);
    }

    public function test_can_refuse_adding_shop(): void
    {
        $this->actingAs($this->admin, 'admin');

        $url = route('admin.product_category.store');
        $data = [
            'name' => '',
            'sort_number' => Str::random(), // invalid number
            'is_active' => null,
        ];
        $this->json('post', $url, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'sort_number',
                'is_active',
            ]);
    }

    public function test_can_update_category(): void
    {
        $this->actingAs($this->admin, 'admin');

        $category = ProductCategoryFactory::new()->create();

        $url = route('admin.product_category.update', $category);
        $data = [
            'name' => 'テストカテゴリ名'. Str::random(),
            'sort_number' => 1,
            'is_active' => true,
        ];
        $this->put($url, $data)
            ->assertStatus(200);
        $this->assertDatabaseHas('product_categories', $data);
    }

    public function test_can_refuse_updating_shop(): void
    {
        $this->actingAs($this->admin, 'admin');

        $shop = ProductCategoryFactory::new()->create();

        $url = route('admin.product_category.update', $shop);
        $data = [
            'name' => '',
            'sort_number' => Str::random(), // invalid number
            'is_active' => null,
        ];
        $this->json('put', $url, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'sort_number',
                'is_active',
            ]);
    }

    public function test_can_delete_category(): void
    {
        $this->actingAs($this->admin, 'admin');

        $category = ProductCategoryFactory::new()->create();

        $url = route('admin.product_category.destroy', $category);
        $this->delete($url)
            ->assertStatus(200);
        $this->assertSoftDeleted('product_categories', ['id' => $category->id]);
    }
}
