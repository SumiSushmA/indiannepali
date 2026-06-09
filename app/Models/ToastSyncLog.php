<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToastSyncLog extends Model
{
    protected $fillable = [
        'logged_at',
        'message',
        'is_success',
    ];

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
            'is_success' => 'boolean',
        ];
    }

    public function toLegacy(): array
    {
        return [
            't' => $this->logged_at->format('H:i'),
            'm' => $this->message,
            'ok' => $this->is_success,
        ];
    }
}
