<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Support\Facades\Auth;

class CustomerSession
{
    public static function user()
    {
        return Auth::guard('customer')->user();
    }

    public static function prefill(): array
    {
        $customer = self::user();

        if (! $customer) {
            return [
                'name' => old('name'),
                'email' => old('email'),
                'phone' => old('phone'),
            ];
        }

        return [
            'name' => old('name', $customer->name),
            'email' => old('email', $customer->email),
            'phone' => old('phone', $customer->phone),
        ];
    }

    public static function customerId(): ?int
    {
        return self::user()?->id;
    }
}
