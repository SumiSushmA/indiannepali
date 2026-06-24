<?php

namespace App\Mail\Concerns;

use App\Support\EmailPreferences;
use Illuminate\Mail\Mailables\Headers;

trait HasListUnsubscribe
{
    protected function listUnsubscribeHeaders(?string $recipientEmail): ?Headers
    {
        if (! $recipientEmail) {
            return null;
        }

        $preference = EmailPreferences::forEmail($recipientEmail);
        $url = EmailPreferences::oneClickUrl($preference->token);

        return new Headers(text: [
            'List-Unsubscribe' => "<{$url}>",
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]);
    }
}
