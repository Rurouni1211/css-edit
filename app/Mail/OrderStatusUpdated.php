<?php

namespace App\Mail;

use App\Models\OrderStatusHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private OrderStatusHistory $history)
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '注文ステータスが変更されました',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $history = $this->history;
        $order = $history->order;

        return new Content(
            view: 'emails.order_status_updated',
            with: [
                'history' => $history,
                'order' => $order,
                'multi_auth_user' => $this->history->multi_auth_user,
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
