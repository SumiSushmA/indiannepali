<?php

namespace App\Services;

use App\Models\ContentBlock;
use App\Models\Setting;

class SiteSettings
{
    public static function all(): array
    {
        return Setting::allCached();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }

    public static function content(string $section, mixed $default = null): mixed
    {
        $blocks = static::blocks();

        return $blocks->has($section)
            ? $blocks->get($section)->value
            : $default;
    }

    public static function blocks()
    {
        return ContentBlock::query()
            ->get()
            ->keyBy('section');
    }
}
