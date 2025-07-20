<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    public function test_confirm_password_screen_can_be_rendered(): void
    {
        $user = Customer::factory()->create([
            'email_verified_at' => now(),
        ]);

        $url = route('customer.password.confirm');
        $response = $this->actingAs($user, 'customer')->get($url);

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed(): void
    {
        $user = Customer::factory()->create([
            'email_verified_at' => now(),
        ]);

        $url = route('customer.password.confirm');
        $response = $this->actingAs($user, 'customer')->post($url, [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password(): void
    {
        $user = Customer::factory()->create([
            'email_verified_at' => now(),
        ]);

        $url = route('customer.password.confirm');
        $response = $this->actingAs($user, 'customer')->post($url, [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
