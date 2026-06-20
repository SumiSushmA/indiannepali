<?php

namespace App\Mail;

use App\Mail\Concerns\HasListUnsubscribe;
use App\Models\ContactMessage;
use App\Services\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use HasListUnsubscribe, Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $inquiry,
    ) {}

    public function envelope(): Envelope
    {
        $site = SiteSettings::all();
        $name = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';

        return new Envelope(
            subject: "Re: {$this->inquiry->subject} — {$name}",
        );
    }

    protected function listUnsubscribeRecipientEmail(): ?string
    {
        return $this->inquiry->customer_email;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
            with: [
                'inquiry' => $this->inquiry,
                'site' => SiteSettings::all(),
            ],
        );
    }
}
