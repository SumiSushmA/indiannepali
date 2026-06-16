<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutValue extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'body',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function toLegacy(): array
    {
        return [
            'icon' => $this->icon,
            'title' => $this->title,
            'text' => $this->body,
        ];
    }
}
