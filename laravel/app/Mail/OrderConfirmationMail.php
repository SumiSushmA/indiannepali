<?php

namespace App\Mail;

use App\Mail\Concerns\HasListUnsubscribe;
use App\Models\Order;
use App\Services\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use HasListUnsubscribe, Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        $site = SiteSettings::all();
        $name = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';

        return new Envelope(
            subject: "Order confirmed — {$this->order->order_number} · {$name}",
        );
    }

    protected function listUnsubscribeRecipientEmail(): ?string
    {
        return $this->order->customer_email;
    }

    public function content(): Content
    {
        $this->order->loadMissing('items');

        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'site' => SiteSettings::all(),
            ],
        );
    }
}
