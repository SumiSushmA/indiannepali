@extends('layouts.customer')

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Our story</div>
    <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Two kitchens, one family</h1>
    <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">A decade of momo, masala and the kind of welcome that brings people back.</p>
</div>

<div style="max-width:1120px;margin:0 auto;padding:44px 32px 110px">
    <div class="cust-about-hero" style="margin-bottom:90px">
        <div>
            @foreach($about['story'] as $i => $p)
                <p style="font-size:{{ $i === 0 ? '22px' : '16.5px' }};font-family:{{ $i === 0 ? 'var(--serif)' : 'var(--sans)' }};font-style:{{ $i === 0 ? 'italic' : 'normal' }};color:{{ $i === 0 ? 'var(--cream)' : 'var(--sand)' }};line-height:1.6;margin-bottom:20px">{{ $p }}</p>
            @endforeach
        </div>
        <x-ph label="Founders at the pass" :h="420" :r="18" />
    </div>

    <div class="cust-stat-band" style="padding:40px 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line);margin-bottom:90px">
        @foreach($about['stats'] as $s)
            <div>
                <div style="font-family:var(--serif);font-size:48px;font-weight:600;color:var(--gold-400);line-height:1">{{ $s[0] }}</div>
                <div style="color:var(--sand);font-size:14.5px;margin-top:10px">{{ $s[1] }}</div>
            </div>
        @endforeach
    </div>

    <div style="text-align:center;margin-bottom:44px">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">What we stand by</div>
        <h2 style="font-size:clamp(30px,4vw,46px)">How we cook</h2>
    </div>
    <div class="cust-val-grid" style="margin-bottom:90px">
        @foreach($about['values'] as $v)
            <div class="cust-card" style="text-align:center">
                <div style="width:56px;height:56px;border-radius:14px;background:var(--gold-glow);border:1px solid var(--gold-700);display:grid;place-items:center;color:var(--gold-400);margin:0 auto 18px">
                    <x-icon :name="$v['icon']" :size="26" />
                </div>
                <h4 style="font-size:22px;margin-bottom:10px">{{ $v['title'] }}</h4>
                <p style="color:var(--muted);font-size:15px;line-height:1.6">{{ $v['text'] }}</p>
            </div>
        @endforeach
    </div>

    <div style="text-align:center;margin-bottom:44px">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">The people</div>
        <h2 style="font-size:clamp(30px,4vw,46px)">Meet the kitchen</h2>
    </div>
    <div class="cust-team-grid" style="margin-bottom:80px">
        @foreach($about['team'] as $t)
            <div>
                <x-ph :label="$t['name']" :h="240" :r="16" style="margin-bottom:16px" />
                <div class="eyebrow" style="margin-bottom:8px;font-size:10.5px">{{ $t['tag'] }}</div>
                <h4 style="font-size:21px">{{ $t['name'] }}</h4>
                <p style="color:var(--muted);font-size:14px;margin-top:4px;line-height:1.5">{{ $t['role'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="cust-card" style="text-align:center;padding:56px 28px;background:var(--ink-850)">
        <div style="color:var(--gold-600);font-size:30px;margin-bottom:10px">◆</div>
        <h2 style="font-size:clamp(28px,3.5vw,42px);max-width:560px;margin:0 auto">Come taste a decade of practice</h2>
        <div style="display:flex;gap:14px;justify-content:center;margin-top:28px;flex-wrap:wrap">
            <a href="{{ route('reserve') }}" class="btn btn-gold"><x-icon name="cal" :size="18" /> Reserve a Table</a>
            <a href="{{ route('menu') }}" class="btn btn-ghost">See the menu</a>
        </div>
    </div>
</div>
@endsection
