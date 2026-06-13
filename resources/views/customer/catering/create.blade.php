@extends('layouts.customer')

@section('content')
@if($submitted)
    <div style="min-height:80vh;display:grid;place-items:center;padding:120px 24px;text-align:center">
        <div class="fade-up" style="max-width:500px">
            <div style="width:88px;height:88px;border-radius:999px;margin:0 auto 26px;background:var(--gold-glow);border:1px solid var(--gold-600);display:grid;place-items:center;color:var(--gold-400)">
                <x-icon name="check" :size="42" />
            </div>
            <h1 style="font-size:46px">Inquiry received</h1>
            <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:16px">Our events team will reach out within one business day with a tailored proposal and tasting invite.</p>
            <a href="{{ route('home') }}" class="btn btn-gold" style="margin-top:28px">Back to home</a>
        </div>
    </div>
@else
    <div class="cust-page-head cust-pad">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Catering & private events</div>
        <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Catering, done generously</h1>
        <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">Weddings, office lunches, pujas and celebrations — we plan, cook and serve a spread to remember. Catering is available for groups of <strong style="color:var(--cream)">20 people or more</strong>.</p>
    </div>

    <div style="max-width:1200px;margin:0 auto;padding:48px 32px 110px">
        <div class="cust-pkg-grid" style="margin-bottom:64px">
            @foreach($packages as $p)
                <div class="cust-card" style="border-color:{{ $p['popular'] ? 'var(--gold-700)' : 'var(--line)' }};position:relative;display:flex;flex-direction:column">
                    @if($p['popular'])
                        <div style="position:absolute;top:-12px;left:28px;background:var(--gold-600);color:#211405;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:5px 12px;border-radius:999px">Most popular</div>
                    @endif
                    <h3 style="font-size:28px">{{ $p['name'] }}</h3>
                    <div style="color:var(--muted);font-size:14px;margin:4px 0 14px">{{ $p['range'] }}</div>
                    <div style="font-family:var(--serif);font-size:26px;color:var(--gold-400);font-weight:600;padding-bottom:18px;border-bottom:1px solid var(--line);margin-bottom:18px">{{ $p['price'] }}</div>
                    <div style="display:flex;flex-direction:column;gap:11px;flex:1">
                        @foreach($p['items'] as $it)
                            <div style="display:flex;gap:10px;font-size:14.5px;color:var(--cream-2)">
                                <x-icon name="check" :size="16" color="var(--gold-500)" style="margin-top:2px;flex-shrink:0" /> {{ $it }}
                            </div>
                        @endforeach
                    </div>
                    <a href="#cater-form" class="btn {{ $p['popular'] ? 'btn-gold' : 'btn-ghost' }}" style="margin-top:22px;width:100%;justify-content:center">Choose {{ explode(' ', $p['name'])[1] }}</a>
                </div>
            @endforeach
        </div>

        <form id="cater-form" action="{{ route('catering.store') }}" method="POST" class="cust-card" style="scroll-margin-top:110px">
            @csrf
            <h2 style="font-size:32px;margin-bottom:6px">Request a quote</h2>
            <p style="color:var(--muted);font-size:15px;margin-bottom:24px">Tell us about your event and we'll build a menu and proposal for you. A minimum of 20 guests is required for catering orders.</p>
            @if($errors->any())
                <div class="cust-card" style="padding:14px 18px;margin-bottom:18px;border-color:var(--spice-600);color:var(--spice-400);font-size:14.5px">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <label class="cust-field"><span>Name</span><input class="cust-inp" name="name" placeholder="Your name" required value="{{ old('name') }}"></label>
                <label class="cust-field"><span>Email</span><input class="cust-inp" name="email" type="email" placeholder="you@email.com" required value="{{ old('email') }}"></label>
                <label class="cust-field"><span>Phone</span><input class="cust-inp" name="phone" type="tel" placeholder="{{ $site['phone'] ?? '(206) 397-3211' }}" required value="{{ old('phone') }}"></label>
                <label class="cust-field"><span>Event type</span>
                    <select class="cust-inp" name="event_type" required>
                        @foreach(['Wedding', 'Corporate', 'Birthday', 'Puja / religious', 'Other'] as $o)
                            <option value="{{ $o }}" {{ old('event_type') === $o ? 'selected' : '' }}>{{ $o }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="cust-field"><span>Event date</span><input class="cust-inp" name="event_date" type="date" required value="{{ old('event_date') }}"></label>
                <label class="cust-field"><span>Guest count <span style="color:var(--muted);font-weight:400">(minimum 20)</span></span><input class="cust-inp" name="guest_count" type="number" min="20" step="1" placeholder="20" required value="{{ old('guest_count') }}"></label>
                <label class="cust-field full"><span>Tell us more</span><textarea class="cust-inp" name="message" placeholder="Venue, dietary needs, service style, budget…" style="min-height:90px;resize:vertical">{{ old('message') }}</textarea></label>
            </div>
            <button type="submit" class="btn btn-gold" style="margin-top:22px">Send inquiry <x-icon name="arrow" :size="18" /></button>
        </form>
    </div>
@endif
@endsection
