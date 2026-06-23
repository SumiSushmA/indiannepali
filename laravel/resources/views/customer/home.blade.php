@extends('layouts.customer')

@push('styles')
    <link rel="stylesheet" href="/css/home-hero.css?v={{ filemtime(public_path('css/home-hero.css')) }}">
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
<section id="story" class="cust-section cust-home-block" style="scroll-margin-top:90px">
    <div style="text-align:center;max-width:640px;margin:0 auto 64px">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:18px">{{ $content['About story'] ?? 'Indian & Nepali cuisine' }}</div>
        <h2>{{ $content['Home story title'] ?? 'Come for the momos, stay for everything else' }}</h2>
        <p class="cust-text-sand" style="margin-top:20px">{{ $content['Home story text'] ?? '' }}</p>
        <a href="{{ route('about') }}" class="btn btn-ghost btn-sm" style="margin-top:22px">Read our story <x-icon name="arrow" :size="16" /></a>
    </div>
    <div class="cust-story-grid">
        <a href="{{ route('menu') }}" class="cust-click-card">
            <x-ph label="Goat curry platter" :src="\App\Support\StockImages::scene('clay oven favorites')" :h="360" :r="16" style="margin-bottom:24px;border:none" />
            <div class="eyebrow" style="margin-bottom:14px">Curries</div>
            <h3 style="margin-bottom:12px">Goat curry &amp; classics</h3>
            <p class="cust-text-sand">Goat curry, butter chicken, paneer tikka, and tandoori momos — slow-simmered gravies and roasted favorites from our kitchen.</p>
        </a>
        <a href="{{ route('menu', ['q' => 'momo']) }}" class="cust-click-card">
            <x-ph label="Combo Momo" :src="\App\Support\StockImages::scene('momo destination')" :h="360" :r="16" style="margin-bottom:24px;border:none" />
            <div class="eyebrow" style="margin-bottom:14px">Momo</div>
            <h3 style="margin-bottom:12px">Seattle's momo destination</h3>
            <p class="cust-text-sand">Steamed, fried, jhol, sandheko, chili, butter masala, and tandoori momo — including the famous combo with four styles in one order.</p>
        </a>
    </div>
</section>

{{-- Signatures --}}
<div class="cust-home-wrap" style="background:var(--ink-850);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
    <section class="cust-section">
        <div class="cust-home-head">
            <div class="eyebrow" style="margin-bottom:16px">Most loved</div>
            <div class="cust-home-head__row">
                <h2>Signature dishes</h2>
                <a href="{{ route('menu') }}" class="btn btn-ghost btn-sm">See full menu <x-icon name="arrow" :size="18" /></a>
            </div>
        </div>
        <div class="cust-dish-grid">
            @foreach($popularItems as $item)
                <x-dish-card :item="$item" />
            @endforeach
        </div>
    </section>
</div>

{{-- Bands --}}
<section class="cust-section cust-home-block cust-home-bands">
    <div class="cust-band">
        <div class="cust-band__eyebrow eyebrow">Order online</div>
        <a href="{{ route('menu') }}" class="cust-band__media cust-click-card">
            <x-ph label="Indian-Nepali delivery spread" :src="\App\Support\StockImages::scene('packed delivery bags')" :h="380" :r="18" style="border:none" />
        </a>
        <div class="cust-band__body">
            <h2>Delivery & pickup, ready when you are</h2>
            <p class="cust-text-sand" style="margin-top:18px;max-width:440px">{{ $content['Delivery blurb'] ?? 'Order online for pickup or delivery from our Aurora Avenue kitchen.' }}</p>
        </div>
        <div class="cust-band__action">
            <a href="{{ route('menu') }}" class="btn btn-gold"><x-icon name="bag" :size="18" /> Start an order</a>
        </div>
    </div>
    <div class="cust-band cust-band--reverse">
        <div class="cust-band__eyebrow eyebrow">Reservations</div>
        <a href="{{ route('reserve') }}" class="cust-band__media cust-click-card">
            <x-ph label="Nepali thali" :src="\App\Support\StockImages::scene('dining room on aurora ave')" :h="380" :r="18" style="border:none" />
        </a>
        <div class="cust-band__body">
            <h2>Save your table for the evening</h2>
            <p class="cust-text-sand" style="margin-top:18px;max-width:440px">Walk in or reserve a table in our cozy black-and-red dining room on Aurora Avenue — peaceful even at peak hours.</p>
        </div>
        <div class="cust-band__action">
            <a href="{{ route('reserve') }}" class="btn btn-gold"><x-icon name="cal" :size="18" /> Reserve a table</a>
        </div>
    </div>
</section>

{{-- Catering --}}
<div style="position:relative;overflow:hidden;background:var(--ink-900);border-top:1px solid var(--line)">
    <x-ph label="catering spread photo" :src="\App\Support\StockImages::scene('catering spread photo')" style="position:absolute;inset:0;border:none;height:100%;min-height:100%" />
    <div style="position:absolute;inset:0;background:linear-gradient(0deg,rgba(10,10,10,.96),rgba(10,10,10,.78))"></div>
    <section class="cust-section cust-home-block" style="position:relative;z-index:2;text-align:center">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:18px">Catering & events</div>
        <h2 style="max-width:760px;margin:0 auto">Bring the feast to your table</h2>
        <p class="cust-text-sand" style="max-width:560px;margin:20px auto 0">{{ $content['Catering blurb'] ?? 'Catering for groups of 20 or more.' }}</p>
        <div class="cust-cater-grid" style="margin:48px 0">
            @foreach([['box', 'Family-size trays', 'Half & full pans of every curry, biryani and momo'], ['users', 'Events 20+ guests', 'Weddings, office lunches, pujas & celebrations — minimum 20 guests'], ['fork', 'Live momo station', 'A chef pleating & steaming on-site, on request']] as [$icon, $title, $text])
                <a href="{{ route('catering') }}" class="cust-click-card" style="background:var(--ink-700);border:1px solid var(--line);border-radius:16px;padding:26px">
                    <div style="width:46px;height:46px;border-radius:12px;background:var(--brand-glow);border:1px solid var(--brand-700);display:grid;place-items:center;color:var(--brand-400);margin-bottom:16px">
                        <x-icon :name="$icon" :size="22" />
                    </div>
                    <h4 style="margin-bottom:6px">{{ $title }}</h4>
                    <p class="cust-text-muted">{{ $text }}</p>
                </a>
            @endforeach
        </div>
        <a href="{{ route('catering') }}" class="btn btn-gold btn-lg">Order catering <x-icon name="arrow" :size="18" /></a>
    </section>
</div>

{{-- Gallery strip --}}
<section class="cust-section cust-home-block">
    <div class="cust-home-head">
        <div class="eyebrow" style="margin-bottom:16px">The room & the food</div>
        <div class="cust-home-head__row">
            <h2>A look inside</h2>
            <a href="{{ route('gallery') }}" class="btn btn-ghost btn-sm">Full gallery <x-icon name="arrow" :size="18" /></a>
        </div>
    </div>
    <div class="cust-gallery-strip">
        @foreach($galleryPreview as $i => $g)
            <a href="{{ route('gallery') }}" class="cust-click-card cust-gallery-tile {{ $i === 0 ? 'large' : '' }}">
                @if($g['url'])
                    <img src="{{ $g['url'] }}" alt="{{ $g['label'] }}" loading="lazy">
                @else
                    <div class="ph cust-gallery-ph"><span>{{ $g['label'] }}</span></div>
                @endif
                <span class="cust-gallery-cap">{{ $g['label'] }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- Reviews --}}
<div class="cust-home-wrap cust-home-reviews" style="background:var(--ink-850);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
    <section class="cust-section">
        <div style="text-align:center;margin-bottom:56px">
            <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Guest love</div>
            <h2>What the neighborhood says</h2>
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
