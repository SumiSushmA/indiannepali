@extends('layouts.customer')

@section('content')
<div style="padding:120px 32px 100px;max-width:1100px;margin:0 auto">
    <a href="{{ route('menu') }}" class="btn btn-ghost btn-sm" style="margin-bottom:22px"><x-icon name="arrowL" :size="16" /> Back to menu</a>
    <h1 style="font-size:44px;margin-bottom:28px">Checkout</h1>

    <div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:18px;padding:10px 16px;border-radius:10px;border:1px solid {{ ($toastPayment['live'] ?? false) ? 'var(--leaf-600)' : 'var(--line)' }};background:{{ ($toastPayment['live'] ?? false) ? 'rgba(79,125,68,.12)' : 'var(--ink-800)' }};font-size:13.5px;color:var(--sand)">
        <x-icon name="link" :size="16" color="{{ ($toastPayment['live'] ?? false) ? 'var(--leaf-500)' : 'var(--muted)' }}" />
        {{ $toastPayment['label'] ?? 'Demo payments' }}
    </div>

    @if($errors->has('payment'))
        <div class="cust-card" style="padding:14px 18px;margin-bottom:18px;border-color:var(--spice-600);color:var(--spice-400)">{{ $errors->first('payment') }}</div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="cust-checkout-grid">
            <div style="display:flex;flex-direction:column;gap:24px">
                <div class="cust-card">
                    <div style="display:flex;gap:12px">
                        @foreach(['delivery', 'pickup'] as $m)
                            <label style="flex:1;padding:16px;border-radius:12px;cursor:pointer;background:{{ $mode === $m ? 'var(--gold-glow)' : 'var(--ink-800)' }};border:1px solid {{ $mode === $m ? 'var(--gold-600)' : 'var(--line)' }};color:var(--cream)">
                                <input type="radio" name="mode" value="{{ $m }}" {{ $mode === $m ? 'checked' : '' }} style="position:absolute;opacity:0">
                                <div style="display:flex;align-items:center;gap:9px;font-weight:700;margin-bottom:4px">
                                    <x-icon :name="$m === 'delivery' ? 'truck' : 'bag'" :size="18" color="var(--gold-400)" />
                                    {{ $m === 'delivery' ? 'Delivery' : 'Pickup' }}
                                </div>
                                <div style="font-size:13px;color:var(--muted)">{{ $m === 'delivery' ? '35–45 min · to your door' : '20–25 min · ' . ($site['address'] ?? '418 Saffron Lane') }}</div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="cust-card">
                    <h3 style="font-size:22px;margin-bottom:18px">Contact details</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                        <label class="cust-field"><span>Full name</span><input class="cust-inp" name="name" placeholder="Asha Gurung" required value="{{ old('name') }}"></label>
                        <label class="cust-field"><span>Phone</span><input class="cust-inp" name="phone" type="tel" placeholder="(415) 555-0140" required value="{{ old('phone') }}"></label>
                        <label class="cust-field full"><span>Email</span><input class="cust-inp" name="email" type="email" placeholder="you@email.com" required value="{{ old('email') }}"></label>
                        <label class="cust-field full cust-delivery-field"><span>Delivery address</span><input class="cust-inp" name="address" placeholder="418 Saffron Lane, Apt 2" value="{{ old('address') }}"></label>
                        <label class="cust-field full cust-delivery-field"><span>Delivery notes</span><input class="cust-inp" name="notes" placeholder="Gate code, floor…" value="{{ old('notes') }}"></label>
                    </div>
                </div>

                <div class="cust-card">
                    <h3 style="font-size:22px;margin-bottom:18px">Payment</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                        <label class="cust-field full"><span>Card number</span><input class="cust-inp" name="card_number" placeholder="4242 4242 4242 4242" required value="{{ old('card_number') }}"></label>
                        <label class="cust-field"><span>Expiry</span><input class="cust-inp" name="card_expiry" placeholder="MM / YY" value="{{ old('card_expiry') }}"></label>
                        <label class="cust-field"><span>CVC</span><input class="cust-inp" name="card_cvc" placeholder="123" value="{{ old('card_cvc') }}"></label>
                    </div>
                </div>
            </div>

            <div class="cust-card" style="position:sticky;top:100px">
                <h3 style="font-size:22px;margin-bottom:16px">Order summary</h3>
                <div style="display:flex;flex-direction:column;gap:10px;max-height:220px;overflow-y:auto;padding-bottom:14px;border-bottom:1px solid var(--line)">
                    @foreach($cartItems as $item)
                        <div style="display:flex;justify-content:space-between;font-size:14.5px;gap:10px">
                            <span style="color:var(--cream-2)"><span style="color:var(--gold-500);font-weight:700">{{ $item['qty'] }}× </span>{{ $item['name'] }}</span>
                            <span style="color:var(--cream)">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div style="padding:16px 0;border-bottom:1px solid var(--line)">
                    <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:10px">Add a tip</div>
                    <div style="display:flex;gap:8px">
                        @foreach([0.15, 0.18, 0.2, 0] as $t)
                            <label style="flex:1;padding:10px 0;border-radius:9px;cursor:pointer;font-weight:600;font-size:14px;text-align:center;background:{{ ($tipRate ?? 0.18) == $t ? 'var(--gold-600)' : 'var(--ink-800)' }};color:{{ ($tipRate ?? 0.18) == $t ? '#211405' : 'var(--cream-2)' }};border:1px solid {{ ($tipRate ?? 0.18) == $t ? 'var(--gold-600)' : 'var(--line)' }}">
                                <input type="radio" name="tip" value="{{ $t }}" {{ ($tipRate ?? 0.18) == $t ? 'checked' : '' }} style="position:absolute;opacity:0">
                                {{ $t === 0 ? 'None' : ($t * 100).'%' }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="padding:16px 0;display:flex;flex-direction:column;gap:9px;font-size:14.5px;color:var(--sand)">
                    <div style="display:flex;justify-content:space-between"><span>Subtotal</span><span style="color:var(--cream-2)">${{ number_format($cartSubtotal, 2) }}</span></div>
                    <div style="display:flex;justify-content:space-between"><span>Tax</span><span style="color:var(--cream-2)">${{ number_format($tax ?? round($cartSubtotal * 0.0875, 2), 2) }}</span></div>
                    @if(($mode ?? 'delivery') === 'delivery')
                        <div style="display:flex;justify-content:space-between">
                            <span>Delivery</span>
                            <span style="color:{{ ($deliveryFee ?? 0) == 0 ? 'var(--leaf-500)' : 'var(--cream-2)' }}">{{ ($deliveryFee ?? 0) == 0 ? 'Free' : '$'.number_format($deliveryFee, 2) }}</span>
                        </div>
                    @endif
                    @if(($tipAmount ?? 0) > 0)
                        <div style="display:flex;justify-content:space-between"><span>Tip</span><span style="color:var(--cream-2)">${{ number_format($tipAmount, 2) }}</span></div>
                    @endif
                </div>

                <div style="display:flex;justify-content:space-between;font-family:var(--serif);font-size:24px;font-weight:700;padding:14px 0;border-top:1px solid var(--line)">
                    <span>Total</span>
                    <span style="color:var(--gold-400)">${{ number_format($total ?? $cartSubtotal, 2) }}</span>
                </div>

                <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center">
                    Place order · ${{ number_format($total ?? $cartSubtotal, 2) }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('input[name="mode"]').forEach(r => {
    r.addEventListener('change', () => {
        const isDelivery = document.querySelector('input[name="mode"]:checked')?.value === 'delivery';
        document.querySelectorAll('.cust-delivery-field').forEach(el => {
            el.style.display = isDelivery ? '' : 'none';
        });
    });
});
document.querySelector('input[name="mode"]:checked')?.dispatchEvent(new Event('change'));
</script>
@endpush
