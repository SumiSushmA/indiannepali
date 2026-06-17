<?php

namespace App\Support;

use App\Models\EmailPreference;
use App\Models\NewsletterSubscriber;

class EmailPreferences
{
    public static function forEmail(string $email): EmailPreference
    {
        return EmailPreference::forEmail($email);
    }

    public static function isUnsubscribed(string $email): bool
    {
        $email = EmailPreference::normalizeEmail($email);

        return EmailPreference::query()
            ->where('email', $email)
            ->whereNotNull('unsubscribed_at')
            ->exists();
    }

    public static function unsubscribeUrl(string $email): string
    {
        $preference = static::forEmail($email);

        return route('unsubscribe.email', ['token' => $preference->token], absolute: true);
    }

    public static function oneClickUrl(string $token): string
    {
        return route('unsubscribe.one-click', ['token' => $token], absolute: true);
    }

    public static function unsubscribe(string $email): void
    {
        $preference = static::forEmail($email);
        $preference->update(['unsubscribed_at' => now()]);

        NewsletterSubscriber::query()
            ->where('email', $preference->email)
            ->delete();
    }

    public static function resubscribe(string $email): void
    {
        $preference = static::forEmail($email);
        $preference->update(['unsubscribed_at' => null]);
    }
}
