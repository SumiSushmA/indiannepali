<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'slug',
        'badge',
        'title',
        'detail',
        'price_label',
        'accent',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function toLegacy(): array
    {
        return [
            'id' => $this->slug,
            'badge' => $this->badge,
            'title' => $this->title,
            'detail' => $this->detail,
            'price' => $this->price_label,
            'accent' => $this->accent,
        ];
    }
}
