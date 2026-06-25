<?php

namespace App\Services\Toast;

class ToastConfiguration
{
    public static function isLive(): bool
    {
        return filled(config('toast.client_id'))
            && filled(config('toast.client_secret'))
            && filled(static::restaurantGuid())
            && filled(config('toast.merchant_uuid'));
    }

    public static function canFetchMenus(): bool
    {
        return filled(config('toast.client_id'))
            && filled(config('toast.client_secret'))
            && filled(static::restaurantGuid());
    }

    public static function restaurantGuid(): ?string
    {
        $guid = trim((string) config('toast.restaurant_guid'));

        if (filled($guid)) {
            return $guid;
        }

        $cateringUrl = trim((string) config('toast.catering_url'));

        if (preg_match('#/restaurants/([0-9a-f-]{36})#i', $cateringUrl, $matches)) {
            return $matches[1];
        }

        return null;
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

    public static function onlineOrderingUrl(): ?string
    {
        $url = trim((string) config('toast.online_ordering_url'));

        return filled($url) ? $url : null;
    }

    public static function giftCardsUrl(): ?string
    {
        $url = trim((string) config('toast.gift_cards_url'));

        return filled($url) ? $url : null;
    }

    public static function cateringUrl(): ?string
    {
        $catering = trim((string) config('toast.catering_url'));

        return filled($catering) ? $catering : null;
    }

    public static function reservationUrl(): ?string
    {
        $url = trim((string) config('toast.reservation_url'));

        return filled($url) ? $url : null;
    }

    /** @return array<string, bool> */
    public static function credentialStatus(): array
    {
        return [
            'client_id' => filled(config('toast.client_id')),
            'client_secret' => filled(config('toast.client_secret')),
            'restaurant_guid' => filled(static::restaurantGuid()),
            'merchant_uuid' => filled(config('toast.merchant_uuid')),
            'card_encryption_key' => filled(config('toast.card_encryption_key')),
            'card_encryption_key_id' => filled(config('toast.card_encryption_key_id')),
            'dining_option_delivery_guid' => filled(config('toast.dining_option_delivery_guid')),
            'dining_option_pickup_guid' => filled(config('toast.dining_option_pickup_guid')),
            'revenue_center_guid' => filled(config('toast.revenue_center_guid')),
        ];
    }
}
