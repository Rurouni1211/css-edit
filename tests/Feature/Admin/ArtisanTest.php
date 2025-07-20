<?php

namespace Feature\Admin;

use App\Mail\ArtisanProfileSaved;
use App\Models\Admin;
use Database\Factories\ArtisanFactory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ArtisanTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::first();
    }

    public function test_can_add_artisan(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');

        $url = route('admin.artisan.store');
        $data = [
            'name' => 'テスト商品名'. Str::random(),
            'email' => Str::random() . '@gmail.com',
            'password' => 'MyPAsSW0rd123',
            'password_confirmation' => 'MyPAsSW0rd123',
            'has_password' => true,
            'has_notification' => true,
        ];
        $this->post($url, $data)->assertStatus(200);

        Mail::assertSent(ArtisanProfileSaved::class);
    }

    public function test_can_refuse_adding_artisan(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');

        $url = route('admin.artisan.store');
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

        Mail::assertNotSent(ArtisanProfileSaved::class);
    }

    public function test_can_update_artisan(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');

        $artisan = ArtisanFactory::new()->create();

        $url = route('admin.artisan.update', $artisan);
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
        $this->assertDatabaseHas('artisans', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        Mail::assertSent(ArtisanProfileSaved::class);
    }

    public function test_can_refuse_updating_artisan(): void
    {
        Mail::fake();

        $this->actingAs($this->admin, 'admin');

        $artisan = ArtisanFactory::new()->create();

        $url = route('admin.artisan.update', $artisan);
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

        Mail::assertNotSent(ArtisanProfileSaved::class);
    }

    public function test_can_delete_artisan(): void
    {
        $this->actingAs($this->admin, 'admin');

        $artisan = ArtisanFactory::new()->create();

        $url = route('admin.artisan.destroy', $artisan);
        $this->delete($url)
            ->assertStatus(200);
        $this->assertSoftDeleted('artisans', ['id' => $artisan->id]);
    }
}
