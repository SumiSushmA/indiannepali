@extends('layouts.customer')

@section('content')

{{-- Hero --}}
<div class="cust-hero">
    <x-ph label="hero photo — tandoor & thali, dim warm light" class="cust-hero-bg" />
    <div class="cust-hero-overlay"></div>
    <div class="cust-hero-glow"></div>
    <section class="cust-section" style="position:relative;z-index:2;padding-top:100px;padding-bottom:60px">
        <div class="fade-up" style="max-width:720px">
            <div class="eyebrow" style="margin-bottom:24px">Indian & Nepali · Est. in the Riverside District</div>
            <h1 style="font-size:clamp(46px,7vw,92px);line-height:.98;font-weight:600;letter-spacing:-.01em">
                {{ $content['Hero headline'] ?? 'Where the Himalayas meet the tandoor.' }}
            </h1>
            <p style="font-size:clamp(17px,2vw,20px);line-height:1.6;color:var(--cream-2);max-width:540px;margin-top:26px">
                {{ $content['Hero subtext'] ?? 'Hand-pleated momo, charcoal-fired kebabs, and curries ground fresh each morning — served in a warm, candle-lit room.' }}
            </p>
            <div style="display:flex;gap:14px;margin-top:38px;flex-wrap:wrap">
                <a href="{{ route('menu') }}" class="btn btn-gold btn-lg"><x-icon name="bag" :size="19" /> Order Online</a>
                <a href="{{ route('reserve') }}" class="btn btn-ghost btn-lg"><x-icon name="cal" :size="19" /> Reserve a Table</a>
            </div>
            <div style="display:flex;gap:30px;margin-top:46px;flex-wrap:wrap;align-items:center">
                <div style="display:flex;align-items:center;gap:10px">
                    <x-stars :value="5" :size="16" />
                    <span style="font-size:14px;color:var(--sand)">4.9 · 1,200+ reviews</span>
                </div>
                <div style="width:1px;height:26px;background:var(--line)"></div>
                <div style="display:flex;align-items:center;gap:9px;font-size:14px;color:var(--sand)">
                    <x-icon name="clock" :size="16" color="var(--gold-500)" /> Open today till 10pm
                </div>
                <div style="width:1px;height:26px;background:var(--line)"></div>
                <div style="display:flex;align-items:center;gap:9px;font-size:14px;color:var(--sand)">
                    <x-icon name="truck" :size="16" color="var(--gold-500)" /> Delivery in 35–45 min
                </div>
            </div>
        </div>
    </section>
    <div style="position:absolute;bottom:26px;left:50%;transform:translateX(-50%);z-index:2;color:var(--muted);display:flex;flex-direction:column;align-items:center;gap:6px;font-size:11px;letter-spacing:.2em;text-transform:uppercase">
        Scroll <x-icon name="down" :size="16" />
    </div>
</div>

{{-- Marquee --}}
@php $words = ['Momo', 'Tandoor', 'Thali', 'Biryani', 'Jhol', 'Sekuwa', 'Naan', 'Masala', 'Sukuti', 'Dal']; @endphp
<div class="cust-marquee">
    <div class="cust-marquee-track">
        @for($r = 0; $r < 2; $r++)
            <div class="cust-marquee-row">
                @foreach($words as $w)
                    <span class="cust-marquee-word">{{ $w }} <span style="color:var(--gold-700)">◆</span></span>
                @endforeach
            </div>
        @endfor
    </div>
</div>

{{-- Story --}}
<section class="cust-section" style="padding:110px 32px">
    <div style="text-align:center;max-width:640px;margin:0 auto 64px">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:18px">Two kitchens, one table</div>
        <h2 style="font-size:clamp(34px,4.5vw,54px);line-height:1.05">A menu that travels from Delhi's lanes to the foothills of Everest</h2>
        <p style="color:var(--sand);font-size:17px;line-height:1.7;margin-top:20px">Our chefs trained on both sides of the border. Every plate honors the technique, spice, and generosity of its home.</p>
    </div>
    <div class="cust-story-grid">
        <div>
            <x-ph label="Tandoori platter" :h="360" :r="16" style="margin-bottom:24px" />
            <div class="eyebrow" style="margin-bottom:14px">The Subcontinent</div>
            <h3 style="font-size:30px;margin-bottom:12px">Tandoor & Curry</h3>
            <p style="color:var(--sand);font-size:16px;line-height:1.7">Overnight marinades fired in a 480°C charcoal tandoor; gravies built from whole spices toasted and ground every morning.</p>
        </div>
        <div>
            <x-ph label="Momo basket" :h="360" :r="16" style="margin-bottom:24px" />
            <div class="eyebrow" style="margin-bottom:14px">The Himalayas</div>
            <h3 style="font-size:30px;margin-bottom:12px">Momo & Sekuwa</h3>
            <p style="color:var(--sand);font-size:16px;line-height:1.7">Dumplings pleated by hand to order, sukuti dried in-house, and the warm sesame jhol that made us a neighborhood ritual.</p>
        </div>
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
            <p style="color:var(--sand);font-size:16.5;line-height:1.7;margin-top:18px;max-width:440px">Build your order in minutes — fresh from our kitchen, packed to travel, tracked from pan to door. Free delivery over $40 within 4 miles.</p>
            <a href="{{ route('menu') }}" class="btn btn-gold" style="margin-top:28px"><x-icon name="bag" :size="18" /> Start an order</a>
        </div>
        <x-ph label="Packed delivery bags" :h="380" :r="18" />
    </div>
    <div class="cust-band">
        <x-ph label="Candle-lit dining room" :h="380" :r="18" />
        <div>
            <div class="eyebrow" style="margin-bottom:16px">Reservations</div>
            <h2 style="font-size:clamp(30px,3.6vw,44px);line-height:1.06">Save your table for the evening</h2>
            <p style="color:var(--sand);font-size:16.5;line-height:1.7;margin-top:18px;max-width:440px">Book a candle-lit table for two or a long table for the whole family. Instant confirmation, special-occasion notes, and a hold on our chef's tasting counter.</p>
            <a href="{{ route('reserve') }}" class="btn btn-gold" style="margin-top:28px"><x-icon name="cal" :size="18" /> Reserve a table</a>
        </div>
    </div>
</section>

{{-- Catering --}}
<div style="position:relative;overflow:hidden;background:var(--ink-900);border-top:1px solid var(--line)">
    <x-ph label="catering spread photo" style="position:absolute;inset:0;border:none" />
    <div style="position:absolute;inset:0;background:linear-gradient(0deg,rgba(13,10,8,.96),rgba(13,10,8,.82))"></div>
    <section class="cust-section" style="position:relative;z-index:2;padding:110px 32px;text-align:center">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:18px">Catering & events</div>
        <h2 style="font-size:clamp(34px,4.6vw,56px);max-width:760px;margin:0 auto;line-height:1.05">Bring the feast to your table</h2>
        <p style="color:var(--sand);font-size:17px;line-height:1.7;max-width:560px;margin:20px auto 0">From an intimate dinner party to a 300-guest wedding mandap, our catering team plans, cooks and serves a spread your guests will remember.</p>
        <div class="cust-cater-grid" style="margin:48px 0">
            @foreach([['box', 'Family-size trays', 'Half & full pans of every curry, biryani and momo'], ['users', 'Events 20–300', 'Weddings, office lunches, pujas & celebrations'], ['fork', 'Live momo station', 'A chef pleating & steaming on-site, on request']] as [$icon, $title, $text])
                <div style="background:var(--ink-700);border:1px solid var(--line);border-radius:16px;padding:26px">
                    <div style="width:46px;height:46px;border-radius:12px;background:var(--gold-glow);border:1px solid var(--gold-700);display:grid;place-items:center;color:var(--gold-400);margin-bottom:16px">
                        <x-icon :name="$icon" :size="22" />
                    </div>
                    <h4 style="font-size:21px;margin-bottom:6px">{{ $title }}</h4>
                    <p style="color:var(--muted);font-size:14.5;line-height:1.6">{{ $text }}</p>
                </div>
            @endforeach
        </div>
        <a href="{{ route('catering') }}" class="btn btn-gold btn-lg">Request a catering quote <x-icon name="arrow" :size="18" /></a>
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
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;grid-template-rows:180px 180px;gap:14px">
        @foreach($galleryPreview as $i => $label)
            <x-ph :label="$label" :r="14" style="{{ $i === 0 ? 'grid-row:1/3' : '' }}" />
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
        <div class="cust-rev-grid">
            @foreach($reviews as $review)
                <div style="background:var(--ink-700);border:1px solid var(--line);border-radius:18px;padding:30px">
                    <x-stars :value="$review['stars']" :size="16" />
                    <p style="font-family:var(--serif);font-style:italic;font-size:21px;line-height:1.45;color:var(--cream);margin:18px 0 22px">"{{ $review['text'] }}"</p>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-weight:600">{{ $review['name'] }}</span>
                        <span style="font-size:12px;color:var(--muted);letter-spacing:.1em;text-transform:uppercase">{{ $review['tag'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

{{-- Closing CTA --}}
<section class="cust-section" style="padding:120px 32px;text-align:center">
    <div style="color:var(--gold-600);font-size:40px;margin-bottom:10px">◆</div>
    <h2 style="font-size:clamp(36px,5vw,64px);line-height:1.04;max-width:760px;margin:0 auto">Pull up a chair. Dinner's on the fire.</h2>
    <div style="display:flex;gap:14px;justify-content:center;margin-top:36px;flex-wrap:wrap">
        <a href="{{ route('menu') }}" class="btn btn-gold btn-lg"><x-icon name="bag" :size="19" /> Order Online</a>
        <a href="{{ route('reserve') }}" class="btn btn-ghost btn-lg"><x-icon name="cal" :size="19" /> Reserve a Table</a>
    </div>
</section>
@endsection
