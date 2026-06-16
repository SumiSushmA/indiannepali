@php
$authUser = auth()->user();
$nav = [
    ['group' => 'Operations', 'items' => [
        ['id' => 'overview', 'label' => 'Overview', 'icon' => 'grid', 'route' => 'admin.dashboard'],
        ['id' => 'orders', 'label' => 'Orders', 'icon' => 'bag', 'badge' => $badges['orders'] ?? 0, 'route' => 'admin.orders.index'],
        ['id' => 'reservations', 'label' => 'Reservations', 'icon' => 'cal', 'badge' => $badges['reservations'] ?? 0, 'route' => 'admin.reservations.index'],
        ['id' => 'catering', 'label' => 'Catering', 'icon' => 'box', 'badge' => $badges['catering'] ?? 0, 'route' => 'admin.catering.index'],
        ['id' => 'contact', 'label' => 'Inquiries', 'icon' => 'mail', 'badge' => $badges['contact'] ?? 0, 'route' => 'admin.inquiries.index'],
    ]],
    ['group' => 'Content', 'items' => [
        ['id' => 'menu', 'label' => 'Menu', 'icon' => 'fork', 'route' => 'admin.menu.index'],
        ['id' => 'promos', 'label' => 'Promos', 'icon' => 'tag', 'route' => 'admin.promos.index'],
        ['id' => 'reviews', 'label' => 'Reviews', 'icon' => 'star', 'route' => 'admin.reviews.index'],
        ['id' => 'content', 'label' => 'Website content', 'icon' => 'file', 'route' => 'admin.content.index'],
        ['id' => 'about', 'label' => 'About page', 'icon' => 'users', 'route' => 'admin.about.index'],
        ['id' => 'gallery', 'label' => 'Gallery', 'icon' => 'image', 'route' => 'admin.gallery.index'],
        ['id' => 'giftcards', 'label' => 'Gift cards', 'icon' => 'tag', 'route' => 'admin.gift-cards.index'],
    ]],
    ['group' => 'System', 'items' => [
        ['id' => 'toast', 'label' => 'Toast POS', 'icon' => 'refresh', 'route' => 'admin.toast.index'],
        ['id' => 'settings', 'label' => 'Settings', 'icon' => 'settings', 'route' => 'admin.settings.index'],
    ]],
];
@endphp

<div id="adm-scrim" class="adm-scrim"></div>
<aside id="adm-sidebar" class="adm-sidebar">
    <div class="adm-shell-bar adm-sidebar__brand">
        <x-logo :size="34" :href="route('admin.dashboard')" />
    </div>
    <div style="flex:1;overflow-y:auto;padding:18px 16px;">
        @foreach($nav as $group)
            <div style="margin-bottom:22px;">
                <div style="font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--faint);padding:0 12px 10px;">{{ $group['group'] }}</div>
                @foreach($group['items'] as $item)
                    @php
                        $area = match (true) {
                            str_contains($item['route'], 'dashboard') => 'dashboard',
                            str_contains($item['route'], 'orders') => 'orders',
                            str_contains($item['route'], 'reservations') => 'reservations',
                            str_contains($item['route'], 'catering') => 'catering',
                            str_contains($item['route'], 'inquiries') => 'inquiries',
                            str_contains($item['route'], 'menu') => 'menu',
                            str_contains($item['route'], 'promos') => 'promos',
                            str_contains($item['route'], 'reviews') => 'reviews',
                            str_contains($item['route'], 'content') => 'content',
                            str_contains($item['route'], 'about') => 'about',
                            str_contains($item['route'], 'gallery') => 'gallery',
                            str_contains($item['route'], 'gift') => 'giftcards',
                            str_contains($item['route'], 'toast') => 'toast',
                            str_contains($item['route'], 'users') => 'users',
                            str_contains($item['route'], 'settings') => 'settings',
                            str_contains($item['route'], 'profile') => 'profile',
                            default => null,
                        };
                    @endphp
                    @continue($area && $authUser && ! $authUser->hasAdminAccess($area))
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                       class="adm-nav-item {{ ($active ?? '') === $item['id'] ? 'active' : '' }}">
                        <x-icon :name="$item['icon']" :size="18" :stroke="($active ?? '') === $item['id'] ? 2 : 1.7"/>
                        <span style="flex:1;">{{ $item['label'] }}</span>
                        @if(!empty($item['badge']))
                            <span class="adm-nav-badge">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
    @php
        $user = auth()->user();
        $initials = collect(explode(' ', $user->name ?? ''))
            ->filter()
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');
    @endphp
    <div style="padding:16px;border-top:1px solid var(--line);">
        <div style="display:flex;align-items:center;gap:11px;padding:8px 6px;">
            <div style="width:38px;height:38px;border-radius:999px;background:linear-gradient(135deg,var(--gold-600),var(--spice-600));display:grid;place-items:center;color:#fff;font-weight:700;font-size:15px;font-family:var(--serif);">{{ $initials }}</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:14px;font-weight:600;color:var(--cream);">{{ $user->name }}</div>
                <div style="font-size:12px;color:var(--muted);">{{ ucfirst($user->role) }}</div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                @csrf
                <button type="submit" title="Sign out" style="background:none;border:none;color:var(--muted);cursor:pointer;display:grid;place-items:center;">
                    <x-icon name="logout" :size="18"/>
                </button>
            </form>
        </div>
    </div>
</aside>
