<?php

namespace App\Mail;

use App\Mail\Concerns\HasListUnsubscribe;
use App\Models\CustomerVerificationCode;
use App\Services\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerVerificationMail extends Mailable
{
    use HasListUnsubscribe, Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $purpose,
        public string $email,
    ) {}

    public function envelope(): Envelope
    {
        $site = SiteSettings::all();
        $name = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';

        $subject = $this->purpose === CustomerVerificationCode::PURPOSE_PASSWORD_RESET
            ? "{$name} — Password reset code"
            : "{$name} — Verify your email";

        return new Envelope(
            subject: $subject,
            headers: $this->listUnsubscribeHeaders($this->email),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-verification',
            with: [
                'code' => $this->code,
                'purpose' => $this->purpose,
                'site' => SiteSettings::all(),
                'recipientEmail' => $this->email,
            ],
        );
    }
}
