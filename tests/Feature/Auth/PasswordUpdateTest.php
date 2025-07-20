<?php

namespace Tests\Feature\Auth;

use App\Enums\UserType;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\MultiAuthTestTrait;

class PasswordUpdateTest extends TestCase
{
    use MultiAuthTestTrait;

    private $user_types = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->user_types = UserType::cases();
    }

    public function test_password_can_be_updated(): void
    {
        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create([
                'email_verified_at' => now(),
            ]);
            $new_password = 'MyPAsSW0rd123';

            $from_url = route($guard . '.profile.edit');
            $to_url = route($guard . '.password.update');
            $response = $this
                ->actingAs($user, $guard)
                ->from($from_url)
                ->put($to_url, [
                    'current_password' => 'password',
                    'password' => $new_password,
                    'password_confirmation' => $new_password,
                ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect($from_url);

            $this->assertTrue(Hash::check($new_password, $user->refresh()->password));

        }
    }

    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create([
                'email_verified_at' => now(),
            ]);

            $from_url = route($guard . '.profile.edit');
            $to_url = route($guard . '.password.update');
            $response = $this
                ->actingAs($user, $guard)
                ->from($from_url)
                ->put($to_url, [
                    'current_password' => 'wrong-password',
                    'password' => 'new-password',
                    'password_confirmation' => 'new-password',
                ]);

            $response
                ->assertSessionHasErrors('current_password')
                ->assertRedirect($from_url);

        }
    }
}
