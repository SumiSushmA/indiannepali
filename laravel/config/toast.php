<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Toast API credentials
    |--------------------------------------------------------------------------
    |
    | When client_id, client_secret, restaurant_guid, and merchant_uuid are
    | all set, the app automatically uses live Toast payments. Otherwise
    | checkout runs in mock/demo mode — no code changes required.
    |
    */

    'client_id' => env('TOAST_CLIENT_ID'),
    'client_secret' => env('TOAST_CLIENT_SECRET'),
    'restaurant_guid' => env('TOAST_RESTAURANT_GUID'),
    'merchant_uuid' => env('TOAST_MERCHANT_UUID'),
    'api_hostname' => env('TOAST_API_HOSTNAME', 'ws-api.toasttab.com'),

    'card_encryption_key' => env('TOAST_CARD_ENCRYPTION_KEY'),
    'card_encryption_key_id' => env('TOAST_CARD_ENCRYPTION_KEY_ID'),

    'dining_option_delivery_guid' => env('TOAST_DINING_OPTION_DELIVERY_GUID'),
    'dining_option_pickup_guid' => env('TOAST_DINING_OPTION_PICKUP_GUID'),
    'revenue_center_guid' => env('TOAST_REVENUE_CENTER_GUID'),
    'gift_card_menu_item_guid' => env('TOAST_GIFT_CARD_MENU_ITEM_GUID'),

    /*
    |--------------------------------------------------------------------------
    | Toast hosted ordering pages
    |--------------------------------------------------------------------------
    |
    | When set, /menu, /gift-cards, and /catering redirect to Toast's hosted
    | ordering, e-gift card, and catering pages instead of the built-in flow.
    |
    */

    'online_ordering_url' => env('TOAST_ONLINE_ORDERING_URL'),
    'gift_cards_url' => env('TOAST_GIFT_CARDS_URL'),
    'catering_url' => env('TOAST_CATERING_URL'),
    'reservation_url' => env('TOAST_RESERVATION_URL'),

];
