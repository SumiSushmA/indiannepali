<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CateringPackage extends Model
{
    protected $fillable = [
        'name',
        'guest_range',
        'price_label',
        'items',
        'is_popular',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'is_popular' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(CateringInquiry::class);
    }

    public function toLegacy(): array
    {
        return [
            'name' => $this->name,
            'range' => $this->guest_range,
            'price' => $this->price_label,
            'items' => $this->items,
            'popular' => $this->is_popular,
        ];
    }
}
