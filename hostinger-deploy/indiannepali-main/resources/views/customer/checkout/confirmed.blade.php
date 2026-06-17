@extends('layouts.customer')

@section('content')
<div style="min-height:80vh;display:grid;place-items:center;padding:120px 24px 60px;text-align:center">
    <div class="fade-up" style="max-width:480px">
        <div style="width:88px;height:88px;border-radius:999px;margin:0 auto 26px;background:var(--gold-glow);border:1px solid var(--gold-600);display:grid;place-items:center;color:var(--gold-400)">
            <x-icon name="check" :size="42" />
        </div>
        <h1 style="font-size:46px">Order confirmed</h1>
        <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:16px">
            Thank you! Our kitchen is firing up your order. We'll text you when it's
            {{ $order['mode'] === 'delivery' ? 'on the way' : 'ready for pickup' }}.
        </p>
        <div style="display:inline-flex;align-items:center;gap:10px;margin-top:22px;background:var(--ink-700);border:1px solid var(--line);border-radius:999px;padding:12px 22px">
            <x-icon name="clock" :size="18" color="var(--gold-400)" />
            <span style="font-weight:600">{{ $order['mode'] === 'delivery' ? 'Est. arrival 30–60 min' : 'Ready for pickup' }}</span>
            <span style="color:var(--muted)">· Order #{{ $order['number'] }}</span>
        </div>
        @if(!empty($order['payment_reference']))
        <div style="margin-top:14px;font-size:13px;color:var(--muted)">
            Payment ref: {{ $order['payment_reference'] }}
            @if(!empty($order['payment_provider']))
                · {{ ucfirst($order['payment_provider']) }}
            @endif
        </div>
        @endif
        <div style="margin-top:32px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
            @auth('customer')
                <a href="{{ route('account.index', ['tab' => 'orders']) }}" class="btn btn-gold">View in my account</a>
            @else
                <a href="{{ route('account.register') }}" class="btn btn-gold">Create account to track orders</a>
            @endauth
            <a href="{{ route('home') }}" class="btn btn-ghost">Back to home</a>
        </div>
    </div>
</div>
@endsection
