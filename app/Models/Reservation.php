<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    protected $fillable = [
        'reference',
        'party_size',
        'reserved_date',
        'reserved_time',
        'customer_name',
        'customer_email',
        'customer_phone',
        'occasion',
        'notes',
        'status',
        'table_number',
    ];

    protected function casts(): array
    {
        return [
            'party_size' => 'integer',
            'reserved_date' => 'date',
        ];
    }

    public function toLegacy(): array
    {
        return [
            'id' => $this->reference,
            'name' => $this->customer_name,
            'party' => $this->party_size,
            'date' => $this->reserved_date->format('Y-m-d'),
            'time' => $this->reserved_time,
            'status' => $this->status,
            'table' => $this->table_number,
            'occasion' => $this->occasion ?? '—',
            'phone' => $this->customer_phone,
        ];
    }
}
