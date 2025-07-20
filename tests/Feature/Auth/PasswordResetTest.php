<?php

namespace Tests\Feature\Auth;

use App\Enums\UserType;
use App\Notifications\ResetPasswordForMultiAuth;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    private $user_types = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->user_types = UserType::cases();
    }

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        foreach ($this->user_types as $user_type) {

            $url = route($user_type->value . '.password.request');
            $response = $this->get($url);
            $response->assertStatus(200);

        }
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create();

            $url = route($guard . '.password.email');
            $this->post($url, ['email' => $user->email]);

            Notification::assertSentTo($user, ResetPasswordForMultiAuth::class);

        }
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create();

            $url = route($guard . '.password.email');
            $this->post($url, ['email' => $user->email]);

            Notification::assertSentTo($user, ResetPasswordForMultiAuth::class, function ($notification) use($guard) {

                $url = route($guard . '.password.reset', ['token' => $notification->token]);
                $response = $this->get($url);
                $response->assertStatus(200);

                return true;
            });

        }
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create();

            $url = route($guard . '.password.email');
            $this->post($url, ['email' => $user->email]);

            Notification::assertSentTo($user, ResetPasswordForMultiAuth::class, function ($notification) use($guard, $user) {

                $url = route($guard . '.password.reset', ['token' => $notification->token]);
                $response = $this->post($url, [
                    'token' => $notification->token,
                    'email' => $user->email,
                    'password' => 'password',
                    'password_confirmation' => 'password',
                ]);

                $response->assertSessionHasNoErrors();

                return true;
            });

        }
    }
}
