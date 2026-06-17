<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutStory extends Model
{
    protected $fillable = [
        'paragraph',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function toLegacy(): string
    {
        return $this->paragraph;
    }
}
