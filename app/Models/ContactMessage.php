<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    protected $fillable = [
        'reference',
        'customer_name',
        'customer_email',
        'subject',
        'message',
        'status',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function toLegacy(): array
    {
        return [
            'id' => $this->reference,
            'name' => $this->customer_name,
            'email' => $this->customer_email,
            'subject' => $this->subject,
            'status' => $this->status,
            'days' => (int) $this->created_at->diffInDays(now()),
            'preview' => mb_substr($this->message, 0, 80),
        ];
    }
}
