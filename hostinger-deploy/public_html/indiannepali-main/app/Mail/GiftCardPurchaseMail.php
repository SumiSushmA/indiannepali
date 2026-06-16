<?php

namespace App\Mail;

use App\Mail\Concerns\HasListUnsubscribe;
use App\Models\GiftCard;
use App\Models\GiftCardDesign;
use App\Services\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GiftCardPurchaseMail extends Mailable
{
    use HasListUnsubscribe, Queueable, SerializesModels;

    public function __construct(
        public GiftCard $card,
        public GiftCardDesign $design,
        public ?string $recipientEmail = null,
    ) {}

    public function envelope(): Envelope
    {
        $site = SiteSettings::all();
        $name = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';

        return new Envelope(
            subject: "Your {$name} gift card — \${$this->card->face_value}",
            headers: $this->listUnsubscribeHeaders($this->recipientEmail),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.gift-card-purchase',
            with: [
                'card' => $this->card,
                'design' => $this->design,
                'site' => SiteSettings::all(),
                'recipientEmail' => $this->recipientEmail,
            ],
        );
    }
}
