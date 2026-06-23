<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->status !== 'active') {
            return redirect()->route('admin.login');
        }

        $area = $this->resolveAreaFromRoute($request);
        if ($area && ! $user->hasAdminAccess($area)) {
            abort(403, 'You do not have access to this section.');
        }

        return $next($request);
    }

    private function resolveAreaFromRoute(Request $request): ?string
    {
        $name = $request->route()?->getName();
        if (! is_string($name)) {
            return null;
        }

        return match (true) {
            str_starts_with($name, 'admin.orders.') => 'orders',
            str_starts_with($name, 'admin.reservations.') => 'reservations',
            str_starts_with($name, 'admin.catering.') => 'catering',
            str_starts_with($name, 'admin.inquiries.') => 'inquiries',
            str_starts_with($name, 'admin.menu.') => 'menu',
            str_starts_with($name, 'admin.promos.') => 'promos',
            str_starts_with($name, 'admin.reviews.') => 'reviews',
            str_starts_with($name, 'admin.content.') => 'content',
            str_starts_with($name, 'admin.about.') => 'about',
            str_starts_with($name, 'admin.gallery.') => 'gallery',
            str_starts_with($name, 'admin.gift-cards.') || str_starts_with($name, 'admin.gift-amounts.') => 'giftcards',
            str_starts_with($name, 'admin.toast.') => 'toast',
            str_starts_with($name, 'admin.users.') => 'users',
            str_starts_with($name, 'admin.settings.') => 'settings',
            str_starts_with($name, 'admin.profile.') => 'profile',
            $name === 'admin.dashboard' => 'dashboard',
            default => null,
        };
    }
}
