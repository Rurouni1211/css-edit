<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = Customer::factory()->create([
            'email_verified_at' => null,
        ]);

        $url = route('customer.verification.notice');
        $response = $this->actingAs($user)->get($url);

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        $user = Customer::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'customer.verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        $url = route('customer.dashboard');
        $response->assertRedirect($url);
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = Customer::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'customer.verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
