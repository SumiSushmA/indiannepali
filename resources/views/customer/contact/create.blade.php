@extends('layouts.customer')

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Visit & contact</div>
    <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Come find us</h1>
    <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">In the heart of the Riverside District — street parking and a lot around back.</p>
</div>

<div style="max-width:1100px;margin:0 auto;padding:44px 32px 110px">
    @if($submitted)
        <div class="cust-card" style="text-align:center;margin-bottom:28px;border-color:var(--gold-700)">
            <x-icon name="check" :size="32" color="var(--gold-400)" />
            <p style="margin-top:12px;color:var(--cream-2)">Message sent — we'll get back to you soon.</p>
        </div>
    @endif

    <div class="cust-contact-grid">
        @php
        $info = [
            ['pin', 'Visit', [$site['address'] ?? '418 Saffron Lane', $site['city'] ?? 'Riverside District, CA 94100']],
            ['phone', 'Call', [$site['phone'] ?? '(415) 555-0192', 'Reservations & takeout']],
            ['clock', 'Hours', [$site['hours'] ?? 'Tue–Sun · 11:30 – 22:00', $site['closed_days'] ?? 'Closed Mondays']],
            ['mail', 'Email', [$site['email'] ?? 'hello@indiannepali.kitchen', 'Catering: events@indiannepali.kitchen']],
        ];
        @endphp
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            @foreach($info as $b)
                <div class="cust-card">
                    <div style="width:44px;height:44px;border-radius:11px;background:var(--gold-glow);border:1px solid var(--gold-700);display:grid;place-items:center;color:var(--gold-400);margin-bottom:14px">
                        <x-icon :name="$b[0]" :size="20" />
                    </div>
                    <h4 style="font-size:19px;margin-bottom:8px">{{ $b[1] }}</h4>
                    @foreach($b[2] as $j => $line)
                        <div style="color:{{ $j === 0 ? 'var(--cream-2)' : 'var(--muted)' }};font-size:14.5px;line-height:1.6">{{ $line }}</div>
                    @endforeach
                </div>
            @endforeach
        </div>
        <form action="{{ route('contact.store') }}" method="POST" class="cust-card">
            @csrf
            <h3 style="font-size:26px;margin-bottom:18px">Send a message</h3>
            <div style="display:grid;gap:14px">
                <label class="cust-field"><span>Name</span><input class="cust-inp" name="name" placeholder="Your name" required value="{{ old('name') }}"></label>
                <label class="cust-field"><span>Email</span><input class="cust-inp" name="email" type="email" placeholder="you@email.com" required value="{{ old('email') }}"></label>
                <label class="cust-field"><span>Message</span><textarea class="cust-inp" name="message" placeholder="How can we help?" required style="min-height:120px;resize:vertical">{{ old('message') }}</textarea></label>
            </div>
            <button type="submit" class="btn btn-gold" style="margin-top:18px">Send message</button>
        </form>
    </div>
    <x-ph label="Map — {{ $site['address'] ?? '418 Saffron Lane' }}" :h="320" :r="18" style="margin-top:24px" />
</div>
@endsection
