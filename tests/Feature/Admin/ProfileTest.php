<?php

namespace Feature\Admin;

use App\Enums\UserType;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\MultiAuthTestTrait;

class ProfileTest extends TestCase
{
    use MultiAuthTestTrait;

    private $user_types = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->user_types = UserType::cases();
    }

    public function test_profile_page_is_displayed(): void
    {
        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create([
                'email_verified_at' => now(),
            ]);

            $url = route($guard . '.profile.edit');
            $response = $this
                ->actingAs($user, $guard)
                ->get($url);

            $response->assertOk();

            // 別の guard にはアクセスできないことを確認
            foreach ($this->user_types as $other_user_type) {

                if ($guard !== $other_user_type->value) {

                    $other_guard = $other_user_type->value;
                    $other_guard_url = route($other_guard . '.profile.edit');
                    $response = $this->get($other_guard_url);
                    $response->assertStatus(302);

                }

            }

            $this->logout($guard);

        }
    }

    public function test_profile_information_can_be_updated(): void
    {
        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create([
                'email_verified_at' => now(),
            ]);

            $name =  Str::random();
            $email = Str::uuid() .'@example.com';
            $url = route($guard . '.profile.update');
            $response = $this
                ->actingAs($user, $guard)
                ->patch($url, [
                    'name' => $name,
                    'email' => $email,
                ]);

            $redirect_url = route($guard . '.profile.edit');
            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect($redirect_url);

            $user->refresh();

            $this->assertSame($name, $user->name);
            $this->assertSame($email, $user->email);
            $this->assertNull($user->email_verified_at);

        }
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        foreach ($this->user_types as $user_type) {

            $guard = $user_type->value;
            $model = $user_type->getModel();
            $user = $model::factory()->create([
                'email_verified_at' => now(),
            ]);

            $url = route($guard . '.profile.update');
            $response = $this
                ->actingAs($user, $guard)
                ->patch($url, [
                    'name' => 'Test User',
                    'email' => $user->email,
                ]);

            $redirect_url = route($guard . '.profile.edit');
            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect($redirect_url);

            $this->assertNotNull($user->refresh()->email_verified_at);

        }
    }

    // 以降は customer のみ

    public function test_user_can_delete_their_account(): void
    {
        $user = Customer::factory()->create([
            'email_verified_at' => now(),
        ]);

        $url = route('customer.profile.destroy');
        $response = $this
            ->actingAs($user)
            ->delete($url, [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNotNull($user->refresh()->deleted_at);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = Customer::factory()->create([
            'email_verified_at' => now(),
        ]);

        $from_url = route('customer.profile.edit');
        $to_url = route('customer.profile.destroy');
        $response = $this
            ->actingAs($user, 'customer')
            ->from($from_url)
            ->delete($to_url, [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect($from_url);

        $this->assertNotNull($user->fresh());
    }
}
