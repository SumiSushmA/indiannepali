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
        'admin_reply',
        'status',
        'read_at',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
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
            'has_reply' => filled($this->admin_reply),
            'replied_at' => $this->replied_at?->format('M j, Y g:i A'),
        ];
    }
}
