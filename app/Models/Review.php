<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
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
            'stars' => 'integer',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
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
