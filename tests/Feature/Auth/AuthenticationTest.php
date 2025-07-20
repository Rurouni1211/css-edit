<?php

namespace Tests\Feature\Auth;

use App\Enums\UserType;
use Tests\TestCase;
use Tests\Traits\MultiAuthTestTrait;

class AuthenticationTest extends TestCase
{
    use MultiAuthTestTrait;

    private $user_types = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->user_types = UserType::cases();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        foreach ($this->user_types as $user_type) {

            $url = route($user_type->value . '.login');
            $response = $this->get($url);
            $response->assertStatus(200);

        }
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create();

            $url = route($guard .'.login.post');
            $response = $this->post($url, [
                'email' => $user->email,
                'password' => 'password',
            ]);

            $redirect_url = route($guard .'.dashboard');

            $this->assertAuthenticated($guard);
            $response->assertRedirect($redirect_url);

            // 別の guard にはアクセスできないことを確認
            foreach ($this->user_types as $other_user_type) {

                if ($guard !== $other_user_type->value) {

                    $other_guard = $other_user_type->value;
                    $other_guard_url = route($other_guard . '.dashboard');
                    $response = $this->get($other_guard_url);
                    $response->assertStatus(302);

                }

            }

            $this->logout($guard);
        }
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        foreach ($this->user_types as $user_type) {

            $model = $user_type->getModel();
            $user = $model::factory()->create();

            $url = route($user_type->value . '.login.post');
            $this->post($url, [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);

            $this->assertGuest();

        }
    }

    public function test_users_can_logout(): void
    {
        foreach ($this->user_types as $user_type) {

            $model = $user_type->getModel();
            $user = $model::factory()->create();

            $url = route($user_type->value . '.logout');
            $response = $this->actingAs($user, $user_type->value)->post($url);

            $this->assertGuest();
            $response->assertRedirect('/');

        }
    }
}
