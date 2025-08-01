<?php

namespace App\Mail;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShopProfileSaved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private Shop $shop, private string $email, private string $password)
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $app_name = config('app.name');

        return new Envelope(
            subject: '【'. $app_name .'】店舗「'. $this->shop->name .'」の新しいログイン情報をお届けします',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.shop_profile_saved',
            with: [
                'email' => $this->email,
                'password' => $this->password,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
