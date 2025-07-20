<?php

namespace App\Mail;

use App\Enums\ContactSubjectType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Contacted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private Request $request)
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $app_name = config('app.name');

        return new Envelope(
            subject: '【'. $app_name .'】お問い合わせがありました',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $contact_subject_type = ContactSubjectType::from($this->request->contact_subject_type);
        $customer_id = (auth('customer')->check()) ? auth('customer')->id() : null;

        return new Content(
            view: 'emails.contacted',
            with: [
                'customer_id' => $customer_id,
                'name' => $this->request->name,
                'email' => $this->request->email,
                'contact_subject_type' => $contact_subject_type->label(),
                'order_id' => $this->request->order_id,
                'subject' => $this->request->subject,
                'body' => $this->request->body,
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
        $files = $this->request->file('files');

        return [];
    }
}
