@php
$navLinks = [
    ['route' => 'menu', 'label' => 'Menu'],
    ['route' => 'catering', 'label' => 'Catering'],
    ['route' => 'gallery', 'label' => 'Gallery'],
    ['route' => 'about', 'label' => 'About'],
    ['route' => 'promos', 'label' => 'Offers'],
    ['route' => 'giftcards', 'label' => 'Gift Cards'],
    ['route' => 'contact', 'label' => 'Contact'],
];
$mobileLinks = array_merge([['route' => 'home', 'label' => 'Home'], ['route' => 'menu', 'label' => 'Menu']], $navLinks, [['route' => 'promos', 'label' => 'Offers'], ['route' => 'reserve', 'label' => 'Reserve'], ['route' => 'account.index', 'label' => 'My Account']]);
@endphp

<header id="cust-header" class="{{ request()->routeIs('home') ? '' : 'solid' }}">
    <div class="cust-inner">
        <x-logo :size="34" :href="route('home')" />

        <nav class="cust-navlinks">
            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}" class="{{ request()->routeIs($link['route']) ? 'active' : '' }}">{{ $link['label'] }}</a>
            @endforeach
        </nav>

        <div class="cust-header-actions">
            @auth('customer')
                <a href="{{ route('account.index') }}" class="btn btn-ghost btn-sm cust-account-btn" aria-label="My account">
                    <x-icon name="user" :size="17" />
                    <span class="cust-account-label">Account</span>
                </a>
            @else
                <a href="{{ route('account.login') }}" class="btn btn-ghost btn-sm cust-account-btn" aria-label="Sign in">
                    <x-icon name="user" :size="17" />
                    <span class="cust-account-label">Sign in</span>
                </a>
            @endauth
            <button type="button" id="cust-cart-btn" aria-label="Cart">
                <x-icon name="bag" :size="19" />
                @if($cartCount > 0)
                    <span id="cust-cart-count">{{ $cartCount }}</span>
                @endif
            </button>
            <a href="{{ route('reserve') }}" class="btn btn-ghost btn-sm cust-reserve-btn">Reserve</a>
            <a href="{{ route('menu') }}" class="btn btn-gold btn-sm">Order Online</a>
            <button type="button" id="cust-burger" class="cust-burger" aria-label="Menu" style="background:none;border:1px solid var(--line);border-radius:10px;width:44px;height:44px;place-items:center;cursor:pointer;color:var(--cream)">
                <x-icon name="menu" :size="20" />
            </button>
        </div>
    </div>
</header>

<div id="cust-mobile-scrim">
    <div id="cust-mobile-sheet" onclick="event.stopPropagation()">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px">
            <x-logo :size="30" />
            <button type="button" id="cust-mobile-close" style="background:none;border:none;color:var(--cream);cursor:pointer">
                <x-icon name="x" :size="24" />
            </button>
        </div>
        @foreach($mobileLinks as $link)
            <a href="{{ route($link['route']) }}" class="{{ request()->routeIs($link['route']) ? 'active' : '' }}">{{ $link['label'] }}</a>
        @endforeach
        <a href="{{ route('menu') }}" class="btn btn-gold" style="margin-top:18px;text-align:center">Order Online</a>
    </div>
</div>
