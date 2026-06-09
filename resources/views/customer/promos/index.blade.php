@extends('layouts.customer')

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Offers & specials</div>
    <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">This season at the Kitchen</h1>
    <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">A little something extra — refreshed regularly. Available in-house and online.</p>
</div>

<div style="max-width:1100px;margin:0 auto;padding:44px 32px 110px">
    <div style="display:flex;flex-direction:column;gap:20px">
        @foreach($promos as $i => $p)
            <div class="cust-promo cust-card" style="padding:0;overflow:hidden">
                <x-ph label="Promo image" style="min-height:220px;order:{{ $i % 2 ? 2 : 1 }}" />
                <div style="padding:36px;display:flex;flex-direction:column;justify-content:center;order:{{ $i % 2 ? 1 : 2 }}">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
                        <span style="background:{{ $p['accent'] === 'spice' ? 'rgba(156,59,37,.18)' : 'var(--gold-glow)' }};color:{{ $p['accent'] === 'spice' ? 'var(--spice-500)' : 'var(--gold-400)' }};border:1px solid {{ $p['accent'] === 'spice' ? 'var(--spice-700)' : 'var(--gold-700)' }};font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:5px 12px;border-radius:999px">{{ $p['badge'] }}</span>
                        <span style="font-family:var(--serif);font-size:30px;font-weight:600;color:var(--gold-400)">{{ $p['price'] }}</span>
                    </div>
                    <h3 style="font-size:30px;line-height:1.1">{{ $p['title'] }}</h3>
                    <p style="color:var(--sand);font-size:16px;line-height:1.6;margin-top:12px">{{ $p['detail'] }}</p>
                    <div style="display:flex;gap:12px;margin-top:22px">
                        <a href="{{ route('menu') }}" class="btn btn-gold btn-sm">Order now</a>
                        <a href="{{ route('reserve') }}" class="btn btn-ghost btn-sm">Reserve</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

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
