@extends('layouts.customer')

@section('content')
<div class="cust-page-head cust-pad cust-page-head--contact">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Visit & contact</div>
    <h1 class="cust-page-head__title">Come find us</h1>
    <p class="cust-page-head__lead">13754 Aurora Ave N, Suite D — in Seattle's Haller Lake neighborhood on Aurora Avenue.</p>
</div>

<div class="cust-contact-wrap">
    @if($submitted)
        <div class="cust-card cust-contact-success">
            <x-icon name="check" :size="32" color="var(--gold-400)" />
            <p>Message sent — we'll get back to you soon.</p>
            <p class="cust-contact-success__hint"><a href="{{ route('account.login') }}">Sign in to your account</a> with the same email to see our reply when it's ready.</p>
        </div>
    @endif

    <div class="cust-contact-grid">
        <div class="cust-contact-cards">
            @php
            $phone = $site['phone'] ?? '(206) 397-3211';
            $email = $site['email'] ?? 'hello@indiannepalikitchen.com';
            $links = [
                ['pin', 'Visit', [$site['address'] ?? '13754 Aurora Ave N, Suite D', $site['city'] ?? 'Seattle, WA 98133'], 'https://maps.google.com/?q=13754+Aurora+Ave+N+Seattle+WA+98133'],
                ['phone', 'Call', [$phone, 'Reservations & takeout'], 'tel:'.preg_replace('/[^\d+]/', '', $phone)],
                ['clock', 'Hours', [$site['hours'] ?? 'Daily · 10:00 AM – 9:30 PM', $site['closed_days'] ?? 'Walk-ins welcome'], route('reserve')],
                ['mail', 'Email', [$email, 'Catering inquiries welcome'], 'mailto:'.$email],
            ];
            @endphp
            @foreach($links as $b)
                <a href="{{ $b[3] }}" class="cust-click-card cust-card cust-contact-card">
                    <div class="cust-contact-card__icon">
                        <x-icon :name="$b[0]" :size="20" />
                    </div>
                    <h4 class="cust-contact-card__title">{{ $b[1] }}</h4>
                    @foreach($b[2] as $j => $line)
                        <div @class(['cust-contact-card__line', 'cust-contact-card__line--muted' => $j > 0])>{{ $line }}</div>
                    @endforeach
                </a>
            @endforeach
        </div>
        <form action="{{ route('contact.store') }}" method="POST" class="cust-card cust-contact-form">
            @csrf
            <h3>Send a message</h3>
            <div class="cust-contact-form__fields">
                <label class="cust-field"><span>Name</span><input class="cust-inp" name="name" placeholder="Your name" required value="{{ old('name') }}"></label>
                <label class="cust-field"><span>Email</span><input class="cust-inp" name="email" type="email" placeholder="you@email.com" required value="{{ old('email') }}"></label>
                <label class="cust-field"><span>Message</span><textarea class="cust-inp" name="message" placeholder="How can we help?" required>{{ old('message') }}</textarea></label>
            </div>
            <button type="submit" class="btn btn-gold cust-contact-form__submit">Send message</button>
        </form>
    </div>
    <x-map-embed :h="480" :r="18" class="cust-contact-map" />
</div>
@endsection
