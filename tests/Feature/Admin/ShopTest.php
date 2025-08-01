<?php

namespace Feature\Admin;

use App\Mail\ShopProfileSaved;
use App\Models\Admin;
use Database\Factories\ShopFactory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShopTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::first();
    }

    public function test_can_add_shop(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');

        $url = route('admin.shop.store');
        $data = [
            'name' => 'テスト商品名'. Str::random(),
            'email' => Str::random() . '@gmail.com',
            'password' => 'MyPAsSW0rd123',
            'password_confirmation' => 'MyPAsSW0rd123',
            'has_password' => true,
            'has_notification' => true,
        ];
        $this->post($url, $data)->assertStatus(200);

        Mail::assertSent(ShopProfileSaved::class);
    }

    public function test_can_refuse_adding_shop(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');

        $url = route('admin.shop.store');
        $data = [
            'name' => '',
            'email' => Str::random(), // invalid email
            'password' => null,
            'password_confirmation' => '',
            'has_password' => true,
            'has_notification' => true,
        ];
        $this->json('post', $url, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password',
            ]);

        Mail::assertNotSent(ShopProfileSaved::class);
    }

    public function test_can_update_shop(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');

        $shop = ShopFactory::new()->create();

        $url = route('admin.shop.update', $shop);
        $data = [
            'name' => 'テスト商品名'. Str::random(),
            'email' => Str::random() . '@gmail.com',
            'password' => 'MyPAsSW0rd123',
            'password_confirmation' => 'MyPAsSW0rd123',
            'has_password' => true,
            'has_notification' => true,
        ];
        $this->put($url, $data)
            ->assertStatus(200);
        $this->assertDatabaseHas('shops', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        Mail::assertSent(ShopProfileSaved::class);
    }

    public function test_can_refuse_updating_shop(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');

        $shop = ShopFactory::new()->create();

        $url = route('admin.shop.update', $shop);
        $data = [
            'name' => '',
            'email' => Str::random(), // invalid email
            'password' => null,
            'password_confirmation' => '',
            'has_password' => true,
            'has_notification' => true,
        ];
        $this->json('put', $url, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password',
            ]);

        Mail::assertNotSent(ShopProfileSaved::class);
    }

    public function test_can_delete_shop(): void
    {
        $this->actingAs($this->admin, 'admin');

        $shop = ShopFactory::new()->create();

        $url = route('admin.shop.destroy', $shop);
        $this->delete($url)
            ->assertStatus(200);
        $this->assertSoftDeleted('shops', ['id' => $shop->id]);
    }
}
