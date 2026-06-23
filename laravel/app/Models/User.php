<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'admin_permissions',
        'status',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_active_at' => 'datetime',
            'admin_permissions' => 'array',
        ];
    }

    public function toLegacy(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'email' => $this->email,
            'status' => ucfirst($this->status),
            'last' => $this->last_active_at
                ? $this->last_active_at->diffForHumans(short: true)
                : '—',
            'permissions' => $this->admin_permissions ?? [],
        ];
    }

    public static function adminAreas(): array
    {
        return [
            'dashboard',
            'orders',
            'reservations',
            'catering',
            'inquiries',
            'menu',
            'promos',
            'reviews',
            'content',
            'about',
            'gallery',
            'giftcards',
            'users',
            'settings',
            'profile',
        ];
    }

    public function isSubAdmin(): bool
    {
        return strtolower((string) $this->role) === 'sub-admin';
    }

    public function hasAdminAccess(string $area): bool
    {
        if (strtolower((string) $this->role) === 'owner') {
            return true;
        }

        $allowed = $this->admin_permissions ?? [];

        return in_array($area, $allowed, true);
    }
}
