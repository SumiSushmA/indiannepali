<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class StockImages
{
    /** @var array<string, string> */
    private const MAP = [
        'hero' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?auto=format&fit=crop&w=1600&q=80',
        'chicken momo' => 'https://images.unsplash.com/photo-1496116218417-78811b11711f?auto=format&fit=crop&w=800&q=80',
        'veg momo' => 'https://images.unsplash.com/photo-1496116218417-78811b11711f?auto=format&fit=crop&w=800&q=80',
        'jhol momo' => 'https://images.unsplash.com/photo-1496116218417-78811b11711f?auto=format&fit=crop&w=800&q=80',
        'kothey momo' => 'https://images.unsplash.com/photo-1496116218417-78811b11711f?auto=format&fit=crop&w=800&q=80',
        'momo' => 'https://images.unsplash.com/photo-1496116218417-78811b11711f?auto=format&fit=crop&w=800&q=80',
        'tandoori' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=800&q=80',
        'tandoori platter' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=800&q=80',
        'momo basket' => 'https://images.unsplash.com/photo-1496116218417-78811b11711f?auto=format&fit=crop&w=800&q=80',
        'butter chicken' => 'https://images.unsplash.com/photo-1603894584373-5ac82b364633?auto=format&fit=crop&w=800&q=80',
        'biryani' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=800&q=80',
        'thali' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?auto=format&fit=crop&w=800&q=80',
        'nepali thali' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?auto=format&fit=crop&w=800&q=80',
        'curry' => 'https://images.unsplash.com/photo-1588166524941-3bf61a837340?auto=format&fit=crop&w=800&q=80',
        'goat curry' => 'https://images.unsplash.com/photo-1588166524941-3bf61a837340?auto=format&fit=crop&w=800&q=80',
        'dal' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?auto=format&fit=crop&w=800&q=80',
        'naan' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?auto=format&fit=crop&w=800&q=80',
        'samosa' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
        'kebab' => 'https://images.unsplash.com/photo-1529042410759-befb1204b568?auto=format&fit=crop&w=800&q=80',
        'seekh' => 'https://images.unsplash.com/photo-1529042410759-befb1204b568?auto=format&fit=crop&w=800&q=80',
        'dessert' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=800&q=80',
        'gulab jamun' => 'https://images.unsplash.com/photo-1582878826629-29ae7adfe9c2?auto=format&fit=crop&w=800&q=80',
        'delivery' => 'https://images.unsplash.com/photo-1526367790999-015a68a20945?auto=format&fit=crop&w=800&q=80',
        'packed delivery' => 'https://images.unsplash.com/photo-1526367790999-015a68a20945?auto=format&fit=crop&w=800&q=80',
        'dining room' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80',
        'candle-lit' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=800&q=80',
        'catering' => 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=800&q=80',
        'catering spread' => 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=800&q=80',
        'restaurant' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80',
        'bar' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=800&q=80',
        'tandoor station' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=800&q=80',
        'wedding' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=800&q=80',
        'founder' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=800&q=80',
        'chef' => 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=800&q=80',
        'promo' => 'https://images.unsplash.com/photo-1603894584373-5ac82b364633?auto=format&fit=crop&w=800&q=80',
        'chaat' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=800&q=80',
        'grill' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=800&q=80',
        'spices' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?auto=format&fit=crop&w=800&q=80',
    ];

    private const DEFAULT = 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?auto=format&fit=crop&w=800&q=80';

    public static function forLabel(?string $label): string
    {
        if (! $label) {
            return self::DEFAULT;
        }

        $key = strtolower(trim($label));

        if (isset(self::MAP[$key])) {
            return self::MAP[$key];
        }

        foreach (self::MAP as $needle => $url) {
            if (str_contains($key, $needle) || str_contains($needle, $key)) {
                return $url;
            }
        }

        return self::DEFAULT;
    }

    public static function hero(): string
    {
        return self::MAP['hero'];
    }

    public static function resolve(?string $label, ?string $imagePath = null): string
    {
        if ($imagePath) {
            if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                return $imagePath;
            }

            return Storage::url($imagePath);
        }

        return self::forLabel($label);
    }
}
