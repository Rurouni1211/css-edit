<?php

namespace Feature\Admin;

use App\Enums\ContactSubjectType;
use App\Mail\Contacted;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContactTest extends TestCase
{
    private $contact_subject_types, $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contact_subject_types = ContactSubjectType::getValues();
        $this->customer = Customer::first();
    }

    public function test_can_send_contact_email_with_valid_data_for_about_order(): void
    {
        $keys = ['without_login', 'with_login'];

        foreach ($keys as $key) {

            Mail::fake();

            $url = route('contact.store');
            $data = [
                'email' => Str::random() . '@gmail.com',
                'name' => Str::random(),
                'contact_subject_type' => ContactSubjectType::About_Order->value,
                'order_id' => Str::random(),
                'subject' => Str::random(),
                'body' => Str::random(),
                'email_settings' => 'true',
                'confirmed' => 'true',
            ];

            if($key === 'with_login') {  // ログインしてるとき

                $this->actingAs($this->customer, 'customer');

            }

            $this->post($url, $data)->assertStatus(200);
            $this->assertDatabaseHas('contacts', [
                'customer_id' => $key === 'with_login'
                    ? $this->customer->id
                    : null,
                'email' => $data['email'],
                'name' => $data['name'],
                'contact_subject_type' => $data['contact_subject_type'],
                'order_id' => $data['order_id'],
                'subject' => $data['subject'],
                'body' => $data['body'],
                'email_settings' => true,
                'confirmed' => true,
            ]);

            Mail::assertSent(Contacted::class);

        }

    }

    public function test_can_refuse_contact_email_with_invalid_data_for_about_order(): void
    {
        Mail::fake();

        $url = route('contact.store');
        $data = [
            'email' => Str::random(), // invalid email
            'name' => '',
            'contact_subject_type' => ContactSubjectType::About_Order->value,
            'order_id' => null,
            'subject' => '',
            'body' => null,
        ];
        // as ajax
        $this->json('post', $url, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
                'name',
                'order_id',
                'subject',
                'body',
                'email_settings',
                'confirmed',
            ]);

        Mail::assertNotSent(Contacted::class);
    }

    public function test_can_send_contact_email_with_valid_data_for_about_product(): void
    {
        Mail::fake();

        $url = route('contact.store');
        $data = [
            'email' => Str::random() . '@gmail.com',
            'name' => Str::random(),
            'contact_subject_type' => ContactSubjectType::About_Product->value,
            'subject' => Str::random(),
            'body' => Str::random(),
            'email_settings' => true,
            'confirmed' => true,
        ];
        $this->post($url, $data)->assertStatus(200);

        Mail::assertSent(Contacted::class);
    }

    public function test_can_refuse_contact_email_with_invalid_data_for_about_product(): void
    {
        Mail::fake();

        $url = route('contact.store');
        $data = [
            'email' => Str::random(), // invalid email
            'name' => '',
            'contact_subject_type' => ContactSubjectType::About_Product->value,
            'subject' => '',
            'body' => null,
        ];
        // as ajax
        $this->json('post', $url, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
                'name',
                'subject',
                'body',
                'email_settings',
                'confirmed',
            ]);

        Mail::assertNotSent(Contacted::class);
    }
}
