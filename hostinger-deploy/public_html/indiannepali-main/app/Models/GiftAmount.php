<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftAmount extends Model
{
    protected $fillable = [
        'amount',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
