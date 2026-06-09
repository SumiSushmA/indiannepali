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
        return [
            'id' => $this->slug,
            'name' => $this->name,
            'sub' => $this->subtitle,
            'accent' => $this->accent,
        ];
    }
}
