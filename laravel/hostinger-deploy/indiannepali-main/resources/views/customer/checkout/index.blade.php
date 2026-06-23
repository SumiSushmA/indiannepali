@extends('layouts.customer')

@section('content')
<div class="cust-checkout-page">
    <a href="{{ route('menu') }}" class="btn btn-ghost btn-sm cust-checkout-back"><x-icon name="arrowL" :size="16" /> Back to menu</a>
    <h1 class="cust-checkout-title">Checkout</h1>

    <div class="cust-payment-badge {{ ($toastPayment['live'] ?? false) ? 'live' : '' }}">
        <x-icon name="link" :size="16" color="{{ ($toastPayment['live'] ?? false) ? 'var(--leaf-500)' : 'var(--muted)' }}" />
        {{ $toastPayment['label'] ?? 'Demo payments' }}
    </div>

    @if($errors->has('payment'))
        <div class="cust-card cust-checkout-error">{{ $errors->first('payment') }}</div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form"
          data-subtotal="{{ $cartSubtotal }}"
          data-tax-rate="{{ \App\Http\Controllers\Customer\CartController::taxRate() }}"
          data-delivery-fee="{{ \App\Http\Controllers\Customer\CartController::deliveryFeeAmount() }}"
          data-free-delivery-min="{{ \App\Http\Controllers\Customer\CartController::freeDeliveryMin() }}">
        @csrf
        <div class="cust-checkout-grid">
            <div class="cust-checkout-main">
                <div class="cust-card">
                    <div class="cust-choice-row">
                        @foreach(['delivery', 'pickup'] as $m)
                            <label class="cust-choice">
                                <input type="radio" name="mode" value="{{ $m }}" class="cust-sr-input" {{ $mode === $m ? 'checked' : '' }}>
                                <div class="cust-choice-title">
                                    <x-icon :name="$m === 'delivery' ? 'truck' : 'bag'" :size="18" color="var(--gold-400)" />
                                    {{ $m === 'delivery' ? 'Delivery' : 'Pickup' }}
                                </div>
                                <div class="cust-choice-sub">{{ $m === 'delivery' ? '30–60 min · to your door' : 'Ready for pickup · ' . ($site['address'] ?? '13754 Aurora Ave N') }}</div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="cust-card">
                    <h3 class="cust-card-heading">Contact details</h3>
                    <div class="cust-form-grid">
                        <label class="cust-field"><span>Full name</span><input class="cust-inp" name="name" placeholder="Asha Gurung" required value="{{ $prefill['name'] ?? old('name') }}"></label>
                        <label class="cust-field"><span>Phone</span><input class="cust-inp" name="phone" type="tel" placeholder="{{ $site['phone'] ?? '(206) 397-3211' }}" required value="{{ $prefill['phone'] ?? old('phone') }}"></label>
                        <label class="cust-field full"><span>Email</span><input class="cust-inp" name="email" type="email" placeholder="you@email.com" required value="{{ $prefill['email'] ?? old('email') }}"></label>
                        <label class="cust-field full cust-delivery-field"><span>Delivery address</span><input class="cust-inp" name="address" placeholder="Your Seattle delivery address" value="{{ old('address') }}"></label>
                        <label class="cust-field full cust-delivery-field"><span>Delivery notes</span><input class="cust-inp" name="notes" placeholder="Gate code, floor…" value="{{ old('notes') }}"></label>
                    </div>
                </div>

                <div class="cust-card">
                    <h3 class="cust-card-heading">Payment</h3>
                    <div class="cust-form-grid">
                        <label class="cust-field full"><span>Card number</span><input class="cust-inp" name="card_number" placeholder="4242 4242 4242 4242" required value="{{ old('card_number') }}" autocomplete="cc-number"></label>
                        <label class="cust-field"><span>Expiry</span><input class="cust-inp" name="card_expiry" placeholder="MM / YY" value="{{ old('card_expiry') }}" autocomplete="cc-exp"></label>
                        <label class="cust-field"><span>CVC</span><input class="cust-inp" name="card_cvc" placeholder="123" value="{{ old('card_cvc') }}" autocomplete="cc-csc"></label>
                    </div>
                </div>
            </div>

            <div class="cust-card cust-checkout-summary">
                <h3 class="cust-card-heading">Order summary</h3>
                <div class="cust-summary-items">
                    @foreach($cartItems as $item)
                        <div class="cust-summary-item">
                            <div class="cust-summary-item-top">
                                <span class="cust-summary-item-name">{{ $item['name'] }}</span>
                                <span class="cust-summary-item-price">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                            </div>
                            @if(!empty($item['desc']))
                                <p class="cust-summary-item-desc">{{ $item['desc'] }}</p>
                            @endif
                            <div class="cust-summary-item-actions">
                                <div class="cust-summary-qty-wrap">
                                    <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="qty" value="{{ max(0, $item['qty'] - 1) }}">
                                        <input type="hidden" name="redirect" value="checkout">
                                        <button type="submit" class="cust-qty-btn" aria-label="Decrease quantity"><x-icon name="minus" :size="14" /></button>
                                    </form>
                                    <span class="cust-summary-qty-val">{{ $item['qty'] }}</span>
                                    <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                        <input type="hidden" name="redirect" value="checkout">
                                        <button type="submit" class="cust-qty-btn" aria-label="Increase quantity"><x-icon name="plus" :size="14" /></button>
                                    </form>
                                </div>
                                <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect" value="checkout">
                                    <button type="submit" class="cust-summary-remove" aria-label="Remove item"><x-icon name="trash" :size="15" /></button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cust-summary-tips">
                    <div class="cust-summary-tips-label">Add a tip</div>
                    <div class="cust-tip-row">
                        @foreach([0.15, 0.18, 0.2, 0] as $t)
                            <label class="cust-tip-btn">
                                <input type="radio" name="tip" value="{{ $t }}" class="cust-sr-input" {{ ($tipRate ?? 0.18) == $t ? 'checked' : '' }}>
                                {{ $t === 0 ? 'None' : ($t * 100).'%' }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="cust-summary-totals">
                    <div class="cust-summary-row"><span>Subtotal</span><span id="checkout-subtotal">${{ number_format($cartSubtotal, 2) }}</span></div>
                    <div class="cust-summary-row"><span>Tax</span><span id="checkout-tax">${{ number_format($tax ?? round($cartSubtotal * 0.0875, 2), 2) }}</span></div>
                    <div class="cust-summary-row" id="checkout-delivery-row" style="{{ ($mode ?? 'delivery') !== 'delivery' ? 'display:none' : '' }}">
                        <span>Delivery</span>
                        <span id="checkout-delivery">{{ ($deliveryFee ?? 0) == 0 ? 'Free' : '$'.number_format($deliveryFee, 2) }}</span>
                    </div>
                    @if(($freeDelivery['offer'] ?? null) && ($mode ?? 'delivery') === 'delivery')
                        <div id="checkout-delivery-note" class="cust-summary-note" style="{{ ($deliveryFee ?? 0) == 0 ? '' : 'display:none' }}">
                            @if($freeDelivery['qualifies'])
                                Offer applied: {{ $freeDelivery['offer']->title }}
                            @else
                                Add ${{ number_format(max(0, $freeDelivery['min'] - $cartSubtotal), 2) }} more for free delivery ({{ $freeDelivery['offer']->title }})
                            @endif
                        </div>
                    @endif
                    <div class="cust-summary-row" id="checkout-tip-row" style="{{ ($tipAmount ?? 0) <= 0 ? 'display:none' : '' }}">
                        <span>Tip</span><span id="checkout-tip">${{ number_format($tipAmount ?? 0, 2) }}</span>
                    </div>
                </div>

                <div class="cust-summary-grand">
                    <span>Total</span>
                    <span id="checkout-total">${{ number_format($total ?? $cartSubtotal, 2) }}</span>
                </div>

                <button type="submit" class="btn btn-gold cust-checkout-submit" id="checkout-submit">
                    Place order · $<span id="checkout-submit-total">{{ number_format($total ?? $cartSubtotal, 2) }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="/js/checkout.js"></script>
@endpush
