@php
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
        ['id' => 'users', 'label' => 'Users & roles', 'icon' => 'users', 'route' => 'admin.users.index'],
        ['id' => 'settings', 'label' => 'Settings', 'icon' => 'settings', 'route' => 'admin.settings.index'],
    ]],
];
@endphp

<div id="adm-scrim" class="adm-scrim"></div>
<aside id="adm-sidebar" class="adm-sidebar">
    <div style="padding:22px 22px 18px;border-bottom:1px solid var(--line);">
        <div style="display:flex;align-items:center;gap:9px;">
            <svg width="30" height="30" viewBox="0 0 48 48" style="flex-shrink:0">
                <circle cx="24" cy="24" r="22" fill="none" stroke="#d4a24e" stroke-width="1.4" opacity=".55"/>
                <circle cx="24" cy="24" r="16.5" fill="none" stroke="#d4a24e" stroke-width="1"/>
                <path d="M24 11 L33 24 L24 37 L15 24 Z" fill="none" stroke="#d4a24e" stroke-width="1.4"/>
                <circle cx="24" cy="24" r="4.4" fill="#d4a24e"/>
            </svg>
            <div style="line-height:1;">
                @php
                    $brand = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';
                    $brandParts = preg_split('/\s+/', $brand, 2);
                @endphp
                <div style="font-family:var(--serif);font-weight:600;font-size:15px;color:var(--cream);">{{ $brandParts[0] ?? $brand }}</div>
                @if(!empty($brandParts[1]))
                <div style="font-family:var(--sans);font-weight:600;font-size:7px;letter-spacing:.42em;text-transform:uppercase;color:#d4a24e;margin-top:3px;padding-left:2px;">{{ $brandParts[1] }}</div>
                @endif
            </div>
        </div>
    </div>
    <div style="flex:1;overflow-y:auto;padding:18px 16px;">
        @foreach($nav as $group)
            <div style="margin-bottom:22px;">
                <div style="font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--faint);padding:0 12px 10px;">{{ $group['group'] }}</div>
                @foreach($group['items'] as $item)
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
