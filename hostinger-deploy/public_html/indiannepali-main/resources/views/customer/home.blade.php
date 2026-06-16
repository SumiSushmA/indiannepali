@extends('layouts.customer')

@push('styles')
    <link rel="stylesheet" href="/css/home-hero.css">
@endpush

@section('content')

{{-- Hero — glassmorphism full-bleed --}}
<section class="ink-hero">
    <div class="ink-hero__bg" aria-hidden="true">
        <img src="{{ $heroImage }}" alt="">
    </div>
    <div class="ink-hero__glow" aria-hidden="true"></div>

    <div class="ink-hero__body">
        <div class="ink-hero__center fade-up">
            <a href="{{ route('menu') }}" class="ink-hero__pill">
                <span class="ink-hero__pill-dot"></span>
                Open for delivery · {{ $site['city'] ?? 'Seattle' }}
                <x-icon name="arrow" :size="14" color="rgba(255,255,255,0.5)" />
            </a>

            <h1 class="ink-hero__headline">
                <span class="ink-hero__line">Flavors that</span>
                <span class="ink-hero__line"><em>grow</em> with every visit.</span>
            </h1>

            <p class="ink-hero__sub">
                {{ $content['Hero subtext'] ?? 'Hand-pleated momo, charcoal-fired kebabs, and curries ground fresh each morning — served in a warm, candle-lit room.' }}
            </p>

            <div class="ink-hero__actions">
                <a href="{{ route('menu') }}" class="ink-hero__cta">
                    Order Online <x-icon name="arrow" :size="18" />
                </a>
                <a href="{{ route('reserve') }}" class="ink-hero__cta ink-hero__cta--ghost">
                    Reserve a table
                </a>
            </div>
        </div>

        <a href="#story" class="ink-hero__scroll" aria-label="Scroll to explore">
            <span>Explore</span>
            <x-icon name="down" :size="16" color="rgba(255,255,255,0.45)" />
        </a>
    </div>

    @php $words = ['Momo', 'Tandoor', 'Thali', 'Biryani', 'Jhol', 'Sekuwa', 'Naan', 'Masala', 'Sukuti', 'Dal']; @endphp
    <div class="ink-hero__marquee cust-marquee">
        <div class="cust-marquee-track">
            @for($r = 0; $r < 2; $r++)
                <div class="cust-marquee-row">
                    @foreach($words as $w)
                        <a href="{{ route('menu', ['q' => $w]) }}" class="cust-marquee-word">{{ $w }} <span style="color:var(--brand-400)">◆</span></a>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>
</section>

{{-- Story --}}
<section id="story" class="cust-section" style="padding:110px 32px;scroll-margin-top:90px">
    <div style="text-align:center;max-width:640px;margin:0 auto 64px">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:18px">{{ $content['About story'] ?? 'Indian & Nepali cuisine' }}</div>
        <h2 style="font-size:clamp(34px,4.5vw,54px);line-height:1.05">{{ $content['Home story title'] ?? 'Come for the momos, stay for everything else' }}</h2>
        <p style="color:var(--sand);font-size:17px;line-height:1.7;margin-top:20px">{{ $content['Home story text'] ?? '' }}</p>
        <a href="{{ route('about') }}" class="btn btn-ghost btn-sm" style="margin-top:22px">Read our story <x-icon name="arrow" :size="16" /></a>
    </div>
    <div class="cust-story-grid">
        <a href="{{ route('menu') }}" class="cust-click-card">
            <x-ph label="Tandoori Chicken Momos" :h="360" :r="16" style="margin-bottom:24px;border:none" />
            <div class="eyebrow" style="margin-bottom:14px">Tandoori</div>
            <h3 style="font-size:30px;margin-bottom:12px">Clay oven favorites</h3>
            <p style="color:var(--sand);font-size:16px;line-height:1.7">Tandoori chicken, lamb boti, paneer tikka, and tandoori momos — marinated in yogurt and spices, roasted in our clay oven.</p>
        </a>
        <a href="{{ route('menu', ['q' => 'momo']) }}" class="cust-click-card">
            <x-ph label="Jhol Momo" :h="360" :r="16" style="margin-bottom:24px;border:none" />
            <div class="eyebrow" style="margin-bottom:14px">Momo</div>
            <h3 style="font-size:30px;margin-bottom:12px">Seattle's momo destination</h3>
            <p style="color:var(--sand);font-size:16px;line-height:1.7">Steamed, fried, jhol, sandheko, chili, butter masala, and tandoori momo — including the famous combo with four styles in one order.</p>
        </a>
    </div>
</section>

{{-- Signatures --}}
<div style="background:var(--ink-850);border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:110px 0">
    <section class="cust-section">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:20px;margin-bottom:52px">
            <div>
                <div class="eyebrow" style="margin-bottom:16px">Most loved</div>
                <h2 style="font-size:clamp(32px,4vw,48px)">Signature dishes</h2>
            </div>
            <a href="{{ route('menu') }}" class="btn btn-ghost">See full menu <x-icon name="arrow" :size="18" /></a>
        </div>
        <div class="cust-dish-grid">
            @foreach($popularItems as $item)
                <x-dish-card :item="$item" />
            @endforeach
        </div>
    </section>
</div>

{{-- Bands --}}
<section class="cust-section" style="padding:110px 32px;display:flex;flex-direction:column;gap:110px">
    <div class="cust-band">
        <div>
            <div class="eyebrow" style="margin-bottom:16px">Order online</div>
            <h2 style="font-size:clamp(30px,3.6vw,44px);line-height:1.06">Delivery & pickup, ready when you are</h2>
            <p style="color:var(--sand);font-size:16.5;line-height:1.7;margin-top:18px;max-width:440px">{{ $content['Delivery blurb'] ?? 'Order online for pickup or delivery from our Aurora Avenue kitchen.' }}</p>
            <a href="{{ route('menu') }}" class="btn btn-gold" style="margin-top:28px"><x-icon name="bag" :size="18" /> Start an order</a>
        </div>
        <a href="{{ route('menu') }}" class="cust-click-card">
            <x-ph label="Packed delivery bags" :h="380" :r="18" style="border:none" />
        </a>
    </div>
    <div class="cust-band">
        <a href="{{ route('reserve') }}" class="cust-click-card">
            <x-ph label="Dining room on Aurora Ave" :h="380" :r="18" style="border:none" />
        </a>
        <div>
            <div class="eyebrow" style="margin-bottom:16px">Reservations</div>
            <h2 style="font-size:clamp(30px,3.6vw,44px);line-height:1.06">Save your table for the evening</h2>
            <p style="color:var(--sand);font-size:16.5;line-height:1.7;margin-top:18px;max-width:440px">Walk in or reserve a table in our cozy black-and-red dining room on Aurora Avenue — peaceful even at peak hours.</p>
            <a href="{{ route('reserve') }}" class="btn btn-gold" style="margin-top:28px"><x-icon name="cal" :size="18" /> Reserve a table</a>
        </div>
    </div>
</section>

{{-- Catering --}}
<div style="position:relative;overflow:hidden;background:var(--ink-900);border-top:1px solid var(--line)">
    <x-ph label="catering spread photo" :src="\App\Support\StockImages::forLabel('catering spread')" style="position:absolute;inset:0;border:none;height:100%;min-height:100%" />
    <div style="position:absolute;inset:0;background:linear-gradient(0deg,rgba(10,10,10,.96),rgba(10,10,10,.78))"></div>
    <section class="cust-section" style="position:relative;z-index:2;padding:110px 32px;text-align:center">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:18px">Catering & events</div>
        <h2 style="font-size:clamp(34px,4.6vw,56px);max-width:760px;margin:0 auto;line-height:1.05">Bring the feast to your table</h2>
        <p style="color:var(--sand);font-size:17px;line-height:1.7;max-width:560px;margin:20px auto 0">{{ $content['Catering blurb'] ?? 'Catering for groups of 20 or more.' }}</p>
        <div class="cust-cater-grid" style="margin:48px 0">
            @foreach([['box', 'Family-size trays', 'Half & full pans of every curry, biryani and momo'], ['users', 'Events 20–300', 'Weddings, office lunches, pujas & celebrations'], ['fork', 'Live momo station', 'A chef pleating & steaming on-site, on request']] as [$icon, $title, $text])
                <a href="{{ route('catering') }}" class="cust-click-card" style="background:var(--ink-700);border:1px solid var(--line);border-radius:16px;padding:26px">
                    <div style="width:46px;height:46px;border-radius:12px;background:var(--brand-glow);border:1px solid var(--brand-700);display:grid;place-items:center;color:var(--brand-400);margin-bottom:16px">
                        <x-icon :name="$icon" :size="22" />
                    </div>
                    <h4 style="font-size:21px;margin-bottom:6px">{{ $title }}</h4>
                    <p style="color:var(--muted);font-size:14.5;line-height:1.6">{{ $text }}</p>
                </a>
            @endforeach
        </div>
        <a href="{{ route('catering') }}" class="btn btn-gold btn-lg">Order catering <x-icon name="arrow" :size="18" /></a>
    </section>
</div>

{{-- Gallery strip --}}
<section class="cust-section" style="padding:110px 32px">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:44px">
        <div>
            <div class="eyebrow" style="margin-bottom:16px">The room & the food</div>
            <h2 style="font-size:clamp(32px,4vw,48px)">A look inside</h2>
        </div>
        <a href="{{ route('gallery') }}" class="btn btn-ghost">Full gallery <x-icon name="arrow" :size="18" /></a>
    </div>
    <div class="cust-gallery-strip">
        @foreach($galleryPreview as $i => $g)
            <a href="{{ route('gallery') }}" class="cust-click-card cust-gallery-tile {{ $i === 0 ? 'large' : '' }}">
                <img src="{{ $g['url'] }}" alt="{{ $g['label'] }}" loading="lazy">
                <span class="cust-gallery-cap">{{ $g['label'] }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- Reviews --}}
<div style="background:var(--ink-850);border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:100px 0">
    <section class="cust-section">
        <div style="text-align:center;margin-bottom:56px">
            <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Guest love</div>
            <h2 style="font-size:clamp(32px,4vw,48px)">What the neighborhood says</h2>
        </div>
        <div class="cust-rev-wrap {{ count($reviews) > 3 ? '' : 'cust-rev-wrap--no-nav' }}">
            @if(count($reviews) > 3)
            <button type="button" class="cust-rev-nav" data-rev-nav="prev" aria-label="Previous reviews">
                <x-icon name="arrowL" :size="18" />
            </button>
            @endif
            <div class="cust-rev-grid" data-rev-track>
            @foreach($reviews as $review)
                <div class="cust-review-card">
                    <div class="cust-review-card__quote" aria-hidden="true">"</div>
                    <div class="cust-review-card__head">
                        <div class="cust-review-card__avatar">{{ strtoupper(substr($review['name'], 0, 1)) }}</div>
                        <div>
                            <div class="cust-review-card__name">{{ $review['name'] }}</div>
                            <x-stars :value="$review['stars']" :size="13" />
                        </div>
                    </div>
                    <p class="cust-review-card__body">"{{ $review['text'] }}"</p>
                    <div class="cust-review-card__foot">
                        <span class="cust-review-card__meta">{{ $review['tag'] }}</span>
                        <div class="cust-review-card__actions" aria-hidden="true">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
            @if(count($reviews) > 3)
            <button type="button" class="cust-rev-nav" data-rev-nav="next" aria-label="Next reviews">
                <x-icon name="arrow" :size="18" />
            </button>
            @endif
        </div>
    </section>
</div>
@endsection
