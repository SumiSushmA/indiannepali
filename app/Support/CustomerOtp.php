<?php

namespace App\Support;

use App\Mail\CustomerVerificationMail;
use App\Models\CustomerVerificationCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerOtp
{
    public const TTL_MINUTES = 10;

    public static function send(string $email, string $purpose): void
    {
        $email = strtolower(trim($email));
        $code = (string) random_int(100000, 999999);

        CustomerVerificationCode::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();

        CustomerVerificationCode::create([
            'email' => $email,
            'code' => Hash::make($code),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(self::TTL_MINUTES),
        ]);

        Mail::to($email)->send(new CustomerVerificationMail($code, $purpose, $email));
    }

    public static function verify(string $email, string $code, string $purpose): bool
    {
        $email = strtolower(trim($email));

        $record = CustomerVerificationCode::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        if (! $record || ! Hash::check($code, $record->code)) {
            return false;
        }

        $record->update(['verified_at' => now()]);

        return true;
    }

    public static function recentlyVerified(string $email, string $purpose, int $withinMinutes = 15): bool
    {
        $email = strtolower(trim($email));

        return CustomerVerificationCode::query()
            ->where('email', $email)
            ->where('purpose', $purpose)
            ->whereNotNull('verified_at')
            ->where('verified_at', '>=', now()->subMinutes($withinMinutes))
            ->exists();
    }
}
