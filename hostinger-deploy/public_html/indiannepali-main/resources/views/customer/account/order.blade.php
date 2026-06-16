@extends('layouts.customer')

@php $pageTitle = 'Order '.$order->order_number; @endphp

@push('styles')
<link rel="stylesheet" href="/css/account.css">
@endpush

@section('content')
<div style="max-width:900px;margin:0 auto;padding:120px 32px 110px">
    <a href="{{ route('account.index', ['tab' => 'orders']) }}" class="acct-back">&larr; Back to orders</a>

    <div class="acct-order-head">
        <div>
            <div class="eyebrow" style="margin-bottom:10px">Order details</div>
            <h1 style="font-size:clamp(32px,4vw,48px)">{{ $order->order_number }}</h1>
            <p style="color:var(--sand);margin-top:10px">{{ ($order->placed_at ?? $order->created_at)->format('l, F j, Y · g:i A') }}</p>
        </div>
        <span class="acct-badge acct-badge-{{ strtolower($order->status) }}">{{ $order->status }}</span>
    </div>

    <div class="acct-order-grid">
        <div class="cust-card acct-order-block">
            <h3>Items</h3>
            <div class="acct-order-items">
                @foreach($order->items as $item)
                    <div class="acct-order-line">
                        <span><strong>{{ $item->quantity }}×</strong> {{ $item->item_name }}</span>
                        <span>${{ number_format($item->line_total, 2) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="acct-order-totals">
                <div><span>Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                <div><span>Tax</span><span>${{ number_format($order->tax, 2) }}</span></div>
                @if($order->delivery_fee > 0)
                    <div><span>Delivery</span><span>${{ number_format($order->delivery_fee, 2) }}</span></div>
                @endif
                @if($order->tip > 0)
                    <div><span>Tip</span><span>${{ number_format($order->tip, 2) }}</span></div>
                @endif
                <div class="acct-order-total"><span>Total</span><strong>${{ number_format($order->total, 2) }}</strong></div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:18px">
            <div class="cust-card acct-order-block">
                <h3>{{ ucfirst($order->fulfillment_type) }}</h3>
                @if($order->fulfillment_type === 'delivery' && $order->address)
                    <p style="color:var(--cream-2);line-height:1.6">{{ $order->address }}</p>
                @else
                    <p style="color:var(--cream-2);line-height:1.6">Pickup at the restaurant</p>
                @endif
                @if($order->delivery_notes)
                    <p class="acct-muted" style="margin-top:10px">Notes: {{ $order->delivery_notes }}</p>
                @endif
            </div>

            <div class="cust-card acct-order-block">
                <h3>Payment</h3>
                <div class="acct-kv">
                    <span>Status</span><strong>{{ ucfirst($order->payment_status ?? 'Paid') }}</strong>
                    @if($order->payment_reference)
                        <span>Reference</span><strong>{{ $order->payment_reference }}</strong>
                    @endif
                    @if($order->card_last4)
                        <span>Card</span><strong>{{ ucfirst($order->card_brand ?? 'Card') }} ···· {{ $order->card_last4 }}</strong>
                    @endif
                    @if($order->payment_provider)
                        <span>Provider</span><strong>{{ $order->payment_provider }}</strong>
                    @endif
                </div>
            </div>

            <div class="cust-card acct-order-block">
                <h3>Contact</h3>
                <div class="acct-kv">
                    <span>Name</span><strong>{{ $order->customer_name }}</strong>
                    <span>Email</span><strong>{{ $order->customer_email }}</strong>
                    <span>Phone</span><strong>{{ $order->customer_phone }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
