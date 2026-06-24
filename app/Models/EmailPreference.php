<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailPreference extends Model
{
    protected $fillable = [
        'email',
        'token',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function isUnsubscribed(): bool
    {
        return $this->unsubscribed_at !== null;
    }

    public static function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    public static function findByToken(string $token): ?self
    {
        $token = trim($token);

        return $token !== '' ? static::query()->where('token', $token)->first() : null;
    }

    public static function forEmail(string $email): self
    {
        $email = static::normalizeEmail($email);

        return static::query()->firstOrCreate(
            ['email' => $email],
            ['token' => Str::random(48)],
        );
    }
}
