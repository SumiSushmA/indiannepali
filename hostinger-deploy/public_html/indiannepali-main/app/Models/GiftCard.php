<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftCard extends Model
{
    protected $fillable = [
        'code',
        'gift_card_design_id',
        'face_value',
        'balance',
        'status',
        'recipient_name',
        'sender_name',
        'message',
        'delivery_method',
        'channel',
        'payment_status',
        'payment_provider',
        'toast_order_guid',
        'toast_payment_guid',
        'payment_reference',
        'card_last4',
        'card_brand',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'face_value' => 'decimal:2',
            'balance' => 'decimal:2',
            'issued_at' => 'datetime',
        ];
    }

    public function design(): BelongsTo
    {
        return $this->belongsTo(GiftCardDesign::class, 'gift_card_design_id');
    }

    public function toLegacy(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'design' => $this->design?->name ?? '',
            'face' => (int) $this->face_value,
            'balance' => (int) $this->balance,
            'status' => $this->status,
            'recipient' => $this->recipient_name,
            'channel' => $this->channel,
            'issued' => ($this->issued_at ?? $this->created_at)->format('Y-m-d'),
        ];
    }
}
