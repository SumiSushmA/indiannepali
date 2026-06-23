@extends('layouts.customer')

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Our story</div>
    <h1>Indian & Nepali cuisine on Aurora Avenue</h1>
    <p class="cust-text-sand cust-page-lead">Seattle's destination for momo, curries, tandoori, and Nepali specialties — dine in, pickup, or delivery.</p>
</div>

<div class="cust-about-wrap">
    <div class="cust-about-hero cust-about-block">
        <div class="cust-about-story">
            @foreach($about['story'] as $p)
                <p>{{ $p }}</p>
            @endforeach
        </div>
        <x-ph label="Founders at the pass" :src="$about['hero_image']" :h="420" :r="18" style="border:none" />
    </div>

    <div class="cust-stat-band cust-about-block">
        @foreach($about['stats'] as $s)
            <a href="{{ route('about') }}" class="cust-click-card" style="text-align:center;text-decoration:none;color:inherit;min-width:0;">
                <div class="cust-about-stat-num">{{ $s[0] }}</div>
                <div class="cust-about-stat-label">{{ $s[1] }}</div>
            </a>
        @endforeach
    </div>

    <div class="cust-about-block--heading">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">What we stand by</div>
        <h2>How we cook</h2>
    </div>
    <div class="cust-val-grid cust-about-block">
        @foreach($about['values'] as $v)
            <a href="{{ route('menu') }}" class="cust-click-card cust-card" style="text-align:center;min-width:0;">
                <div style="width:56px;height:56px;border-radius:14px;background:var(--brand-glow);border:1px solid var(--brand-700);display:grid;place-items:center;color:var(--brand-400);margin:0 auto 18px">
                    <x-icon :name="$v['icon']" :size="26" />
                </div>
                <h4 style="margin-bottom:10px">{{ $v['title'] }}</h4>
                <p class="cust-text-muted">{{ $v['text'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="cust-card cust-about-cta">
        <div style="color:var(--gold-600);font-size:30px;margin-bottom:10px">◆</div>
        <h2 style="max-width:560px;margin:0 auto">Visit us on Aurora Avenue</h2>
        <div class="cust-about-cta__actions">
            <a href="{{ route('reserve') }}" class="btn btn-gold"><x-icon name="cal" :size="18" /> Reserve a Table</a>
            <a href="{{ route('menu') }}" class="btn btn-ghost">See the menu</a>
        </div>
    </div>
</div>
@endsection
