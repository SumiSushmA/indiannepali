@extends('layouts.customer')

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Offers & specials</div>
    <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Deals going on now</h1>
    <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">Combo meals, spend-and-save perks, and dine-in rewards — updated by our team.</p>
</div>

<div style="max-width:1100px;margin:0 auto;padding:44px 32px 110px">
    @if(session('error'))
        <div class="cust-card" style="margin-bottom:20px;border-color:var(--spice-600);color:var(--spice-400);padding:14px 18px">{{ session('error') }}</div>
    @endif

    @if(empty($promos))
        <div class="cust-card" style="padding:48px 32px;text-align:center">
            <x-icon name="tag" :size="36" color="var(--muted)" />
            <h3 style="font-size:26px;margin:16px 0 8px">No active offers right now</h3>
            <p style="color:var(--muted);margin-bottom:22px">Check back soon — or browse the full menu anytime.</p>
            <a href="{{ route('menu') }}" class="btn btn-gold">Browse menu</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:20px">
            @foreach($promos as $i => $p)
                <div class="cust-promo cust-card {{ $i % 2 ? 'cust-promo--reverse' : '' }}">
                    <div class="cust-promo-media">
                        <x-ph :label="$p['title']" :src="$p['image']" style="border:none;border-radius:0" />
                    </div>
                    <div class="cust-promo-body">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;flex-wrap:wrap">
                            <span style="background:{{ $p['accent'] === 'spice' ? 'rgba(156,59,37,.18)' : ($p['accent'] === 'leaf' ? 'rgba(90,143,82,.15)' : 'var(--gold-glow)') }};color:{{ $p['accent'] === 'spice' ? 'var(--spice-500)' : ($p['accent'] === 'leaf' ? '#9fd195' : 'var(--gold-400)') }};border:1px solid {{ $p['accent'] === 'spice' ? 'var(--spice-700)' : ($p['accent'] === 'leaf' ? 'rgba(90,143,82,.35)' : 'var(--gold-700)') }};font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:5px 12px;border-radius:999px">{{ $p['badge'] }}</span>
                            <span style="font-size:12px;color:var(--muted);font-weight:600">{{ $p['offer_type_label'] }}</span>
                            <span style="font-family:var(--serif);font-size:30px;font-weight:600;color:var(--gold-400);margin-left:auto">{{ $p['price'] }}</span>
                        </div>
                        <h3 style="font-size:30px;line-height:1.1">{{ $p['title'] }}</h3>
                        <p style="color:var(--sand);font-size:16px;line-height:1.6;margin-top:12px">{{ $p['detail'] }}</p>

                        @if($p['min_order_amount'])
                            <p style="color:var(--cream-2);font-size:14px;margin-top:10px">Minimum order: ${{ number_format($p['min_order_amount'], 0) }}</p>
                        @endif
                        @if($p['min_party_size'])
                            <p style="color:var(--cream-2);font-size:14px;margin-top:10px">For parties of {{ $p['min_party_size'] }} or more</p>
                        @endif
                        @if($p['starts_at'] || $p['ends_at'])
                            <p style="color:var(--muted);font-size:13px;margin-top:8px">
                                @if($p['starts_at'] && $p['ends_at'])
                                    Valid {{ $p['starts_at'] }} – {{ $p['ends_at'] }}
                                @elseif($p['ends_at'])
                                    Through {{ $p['ends_at'] }}
                                @else
                                    Starts {{ $p['starts_at'] }}
                                @endif
                            </p>
                        @endif
                        @if($p['terms'])
                            <p style="color:var(--muted);font-size:13px;margin-top:8px;font-style:italic">{{ $p['terms'] }}</p>
                        @endif

                        <div style="margin-top:22px">
                            @if($p['order_item'])
                                <form action="{{ route('promos.order', $p['id']) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-gold btn-sm">{{ $p['cta_label'] }}</button>
                                </form>
                            @else
                                <a href="{{ $p['cta_href'] }}" class="btn btn-gold btn-sm">{{ $p['cta_label'] }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="cust-card" style="margin-top:24px;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap">
        <div>
            <h3 style="font-size:26px">Join the table</h3>
            <p style="color:var(--muted);font-size:15px;margin-top:6px">Get new offers and seasonal menus first. No spam, ever.</p>
        </div>
        <form action="{{ route('newsletter.store') }}" method="POST" style="display:flex;gap:10px;flex:1;min-width:260px;max-width:420px">
            @csrf
            <input class="cust-inp" name="email" placeholder="you@email.com" type="email" required value="{{ old('email') }}">
            <button type="submit" class="btn btn-gold">Subscribe</button>
        </form>
    </div>
</div>
@endsection
