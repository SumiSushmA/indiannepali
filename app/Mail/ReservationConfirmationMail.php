<?php

namespace App\Mail;

use App\Mail\Concerns\HasListUnsubscribe;
use App\Models\Reservation;
use App\Services\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmationMail extends Mailable
{
    use HasListUnsubscribe, Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function envelope(): Envelope
    {
        $site = SiteSettings::all();
        $name = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';

        return new Envelope(
            subject: "Table reserved — {$this->reservation->reference} · {$name}",
        );
    }

    protected function listUnsubscribeRecipientEmail(): ?string
    {
        return $this->reservation->customer_email;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation-confirmation',
            with: [
                'reservation' => $this->reservation,
                'site' => SiteSettings::all(),
            ],
        );
    }
}
