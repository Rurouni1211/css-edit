<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    public function test_registration_screen_can_be_rendered(): void
    {
        $url = route('customer.register');
        $response = $this->get($url);

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $url = route('customer.register.post');
        $response = $this->post($url, [
            'name' => 'Test User',
            'email' => Str::random() .'@example.com',
            'password' => 'MyPAsSW0rd123',
            'password_confirmation' => 'MyPAsSW0rd123',
        ]);

        $redirect_url = route('customer.dashboard');

        $this->assertAuthenticated('customer');
        $response->assertRedirect($redirect_url);
    }
}
