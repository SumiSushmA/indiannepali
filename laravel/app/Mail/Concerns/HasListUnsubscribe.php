<?php

namespace App\Mail\Concerns;

use App\Support\EmailPreferences;
use Illuminate\Mail\Mailables\Headers;

trait HasListUnsubscribe
{
    protected function listUnsubscribeRecipientEmail(): ?string
    {
        return null;
    }

    public function headers(): Headers
    {
        $recipientEmail = $this->listUnsubscribeRecipientEmail();

        if (! $recipientEmail) {
            return new Headers;
        }

        $preference = EmailPreferences::forEmail($recipientEmail);
        $url = EmailPreferences::oneClickUrl($preference->token);

        return new Headers(text: [
            'List-Unsubscribe' => "<{$url}>",
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]);
    }
}
