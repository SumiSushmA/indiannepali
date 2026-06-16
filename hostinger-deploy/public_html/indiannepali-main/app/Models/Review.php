<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'customer_id',
        'author_name',
        'stars',
        'body',
        'source_tag',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'customer_id' => 'integer',
            'stars' => 'integer',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function toLegacy(): array
    {
        return [
            'name' => $this->author_name,
            'stars' => $this->stars,
            'text' => $this->body,
            'tag' => $this->source_tag,
        ];
    }
}
