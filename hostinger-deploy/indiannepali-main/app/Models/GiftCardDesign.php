<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftCardDesign extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'subtitle',
        'accent',
        'bg_start',
        'bg_mid',
        'bg_end',
        'text_color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function giftCards(): HasMany
    {
        return $this->hasMany(GiftCard::class);
    }

    public function toLegacy(): array
    {
        $palette = $this->palette();

        return [
            'id' => $this->slug,
            'name' => $this->name,
            'sub' => $this->subtitle,
            'accent' => $this->accent,
            'bg' => "linear-gradient(125deg, {$palette['start']} 0%, {$palette['mid']} 48%, {$palette['end']} 100%)",
            'text' => $palette['text'],
        ];
    }

    public function palette(): array
    {
        $fallback = match ($this->accent) {
            'spice' => ['start' => '#963042', 'mid' => '#d46a62', 'end' => '#fae8e0', 'text' => '#3a1810'],
            'leaf' => ['start' => '#3d7a52', 'mid' => '#6dad82', 'end' => '#e4f5ea', 'text' => '#1a2e20'],
            default => ['start' => '#c9922a', 'mid' => '#e8c56a', 'end' => '#f8e8b8', 'text' => '#3a2810'],
        };

        return [
            'start' => $this->bg_start ?: $fallback['start'],
            'mid' => $this->bg_mid ?: $fallback['mid'],
            'end' => $this->bg_end ?: $fallback['end'],
            'text' => $this->text_color ?: $fallback['text'],
        ];
    }
}
