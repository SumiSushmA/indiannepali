<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'tag',
        'image_path',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function toLegacy(): array
    {
        return [
            'name' => $this->name,
            'role' => $this->role,
            'tag' => $this->tag,
            'image_path' => $this->image_path,
        ];
    }
}
