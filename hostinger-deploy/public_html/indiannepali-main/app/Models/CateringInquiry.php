<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CateringInquiry extends Model
{
    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    protected $fillable = [
        'customer_id',
        'reference',
        'customer_name',
        'customer_email',
        'customer_phone',
        'event_type',
        'event_date',
        'guest_count',
        'message',
        'status',
        'quoted_value',
        'catering_package_id',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'guest_count' => 'integer',
            'quoted_value' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(CateringPackage::class, 'catering_package_id');
    }

    public function toLegacy(): array
    {
        return [
            'id' => $this->reference,
            'name' => $this->customer_name,
            'event' => $this->event_type,
            'guests' => $this->guest_count,
            'date' => $this->event_date->format('Y-m-d'),
            'status' => $this->status,
            'value' => (int) round($this->quoted_value ?? 0),
            'quoted_value' => $this->quoted_value,
            'days' => (int) $this->created_at->diffInDays(now()),
        ];
    }
}
