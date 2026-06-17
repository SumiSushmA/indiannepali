<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    protected $fillable = [
        'customer_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'fulfillment_type',
        'address',
        'delivery_notes',
        'channel',
        'status',
        'subtotal',
        'tax',
        'delivery_fee',
        'tip',
        'tip_rate',
        'total',
        'payment_status',
        'payment_provider',
        'toast_order_guid',
        'toast_payment_guid',
        'payment_reference',
        'card_last4',
        'card_brand',
        'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'tip' => 'decimal:2',
            'tip_rate' => 'decimal:4',
            'total' => 'decimal:2',
            'placed_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function toLegacy(): array
    {
        $placedAt = $this->placed_at ?? $this->created_at;

        return [
            'id' => $this->order_number,
            'customer' => $this->customer_name,
            'type' => ucfirst($this->fulfillment_type),
            'status' => $this->status,
            'items' => $this->items->map(fn (OrderItem $item) => $item->toLegacyItem())->values()->all(),
            'total' => (int) $this->total,
            'channel' => $this->channel,
            'time' => $placedAt->diffForHumans(short: true),
            'mins' => (int) $placedAt->diffInMinutes(now()),
        ];
    }
}
