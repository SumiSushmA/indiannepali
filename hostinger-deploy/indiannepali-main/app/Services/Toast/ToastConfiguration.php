<?php

namespace App\Services\Toast;

class ToastConfiguration
{
    public static function isLive(): bool
    {
        return filled(config('toast.client_id'))
            && filled(config('toast.client_secret'))
            && filled(config('toast.restaurant_guid'))
            && filled(config('toast.merchant_uuid'));
    }

    public static function mode(): string
    {
        return self::isLive() ? 'live' : 'mock';
    }

    public static function label(): string
    {
        return self::isLive()
            ? 'Toast Payments (live)'
            : 'Demo payments (add Toast API keys to .env for live)';
    }

    public static function apiBaseUrl(): string
    {
        return 'https://'.config('toast.api_hostname');
    }

    /** @return array<string, bool> */
    public static function credentialStatus(): array
    {
        return [
            'client_id' => filled(config('toast.client_id')),
            'client_secret' => filled(config('toast.client_secret')),
            'restaurant_guid' => filled(config('toast.restaurant_guid')),
            'merchant_uuid' => filled(config('toast.merchant_uuid')),
            'card_encryption_key' => filled(config('toast.card_encryption_key')),
            'card_encryption_key_id' => filled(config('toast.card_encryption_key_id')),
            'dining_option_delivery_guid' => filled(config('toast.dining_option_delivery_guid')),
            'dining_option_pickup_guid' => filled(config('toast.dining_option_pickup_guid')),
            'revenue_center_guid' => filled(config('toast.revenue_center_guid')),
        ];
    }
}
