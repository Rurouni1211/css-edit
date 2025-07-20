<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Events\OrderSaved;
use App\Models\Order;
use App\Models\OrderComponent;
use App\Models\Product;
use App\Models\Material;
use App\Models\ProductComponent;
use App\Models\ProductComponentMaterial;
use App\Models\Color;
use App\Models\Customer;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_order_with_components()
    {
        // Fake the OrderSaved event to prevent authentication issues in tests
        Event::fake([OrderSaved::class]);
        
        // Create a product category
        $category = ProductCategory::factory()->create();

        // Create a test product with components
        $product = Product::factory()->create([
            'name' => 'テスト商品',
            'category_id' => $category->id,
            'product_code' => 'TEST-001',
            'sketchfab_model_key' => 'test-model-key',
            'material_combination_type' => 1
        ]);

        // Create a test component
        $component = ProductComponent::factory()->create([
            'product_id' => $product->id,
            'name' => 'テストコンポーネント',
            'key' => 'Test_Component'
        ]);

        // Create a test material
        $material = Material::factory()->create([
            'name' => 'テスト素材'
        ]);

        // Create a test color
        $color = Color::factory()->create([
            'name' => 'テストカラー'
        ]);

        // Create product component material with price
        $componentMaterial = ProductComponentMaterial::factory()->create([
            'product_component_id' => $component->id,
            'material_id' => $material->id,
            'amount' => 1000 // 1,000円
        ]);

        // Request data for order creation
        $orderData = [
            'product_id' => $product->id,
            'components' => [
                [
                    'group_key' => 'Test_Group',
                    'material_id' => $material->id,
                    'color_id' => $color->id,
                    'logo_enabled' => false,
                    'component_group_keys' => ['Test_Component']
                ]
            ]
        ];

        // Send the request to create an order
        $response = $this->postJson(route('store.product.store'), $orderData);

        // Assert response is successful
        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        // Assert order was created in database
        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'order_type' => OrderType::Product,
            'status' => OrderStatus::PENDING,
        ]);

        // Get the created order
        $order = Order::where('product_id', $product->id)->first();
        $this->assertNotNull($order);

        // Assert order component was created
        $this->assertDatabaseHas('order_components', [
            'order_id' => $order->id,
            'material_id' => $material->id,
            'component_id' => $component->id,
        ]);
    }

    /** @test */
    public function it_can_view_order_completion_page()
    {
        // Fake the OrderSaved event to prevent authentication issues in tests
        Event::fake([OrderSaved::class]);
        
        // Create a customer for the order
        $customer = Customer::factory()->create();

        // Create a test order with saveQuietly to avoid event issues
        $order = new Order();
        $order->order_unique_id = 'TEST-ORDER-123';
        $order->product_name = 'テスト商品';
        $order->total_amount = 1000;
        $order->consumption_tax = 100;
        $order->consumption_tax_rate = 10;
        $order->customer_id = $customer->id;
        $order->status = OrderStatus::Pending;
        $order->order_type = OrderType::Product;
        $order->saveQuietly();

        // Access the completion page
        $response = $this->get(route('store.order.completed', ['order' => $order->order_unique_id]));

        // Assert response is successful
        $response->assertStatus(200);
    }
}