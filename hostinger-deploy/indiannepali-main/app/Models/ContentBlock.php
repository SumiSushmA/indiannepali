<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    public function getRouteKeyName(): string
    {
        return 'section';
    }

    protected $fillable = [
        'section',
        'value',
        'type',
    ];

    public function toLegacy(): array
    {
        return [
            'section' => $this->section,
            'value' => $this->value,
            'type' => $this->type,
            'updated' => $this->updated_at->diffForHumans(short: true),
        ];
    }
}
