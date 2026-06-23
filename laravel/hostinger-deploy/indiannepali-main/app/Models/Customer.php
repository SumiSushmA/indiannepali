<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function cateringInquiries(): HasMany
    {
        return $this->hasMany(CateringInquiry::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function linkedOrders()
    {
        return Order::query()
            ->where(function ($query) {
                $query->where('customer_id', $this->id)
                    ->orWhereRaw('LOWER(customer_email) = ?', [strtolower($this->email)]);
            })
            ->orderByDesc('placed_at');
    }

    public function linkedReservations()
    {
        return Reservation::query()
            ->where(function ($query) {
                $query->where('customer_id', $this->id)
                    ->orWhereRaw('LOWER(customer_email) = ?', [strtolower($this->email)]);
            })
            ->orderByDesc('reserved_date')
            ->orderByDesc('reserved_time');
    }

    public function linkedCateringInquiries()
    {
        return CateringInquiry::query()
            ->where(function ($query) {
                $query->where('customer_id', $this->id)
                    ->orWhereRaw('LOWER(customer_email) = ?', [strtolower($this->email)]);
            })
            ->orderByDesc('created_at');
    }

    public function linkedContactMessages()
    {
        return ContactMessage::query()
            ->whereRaw('LOWER(customer_email) = ?', [strtolower($this->email)])
            ->orderByDesc('created_at');
    }
}
