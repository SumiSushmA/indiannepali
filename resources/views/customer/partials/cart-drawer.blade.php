@php
$tax = round($cartSubtotal * \App\Http\Controllers\Customer\CartController::taxRate(), 2);
$estTotal = round($cartSubtotal + $tax, 2);
@endphp

<div id="cart-drawer" aria-hidden="true">
    <div id="cart-drawer-backdrop"></div>
    <div id="cart-drawer-panel">
        <div style="padding:22px 24px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center">
            <div style="font-family:var(--serif);font-size:24px;font-weight:600">Your Order</div>
            <button type="button" id="cart-drawer-close" style="background:none;border:none;color:var(--sand);cursor:pointer">
                <x-icon name="x" :size="22" />
            </button>
        </div>

        @if(empty($cartItems))
            <div style="flex:1;display:grid;place-items:center;padding:40px;text-align:center;color:var(--muted)">
                <div>
                    <x-icon name="bag" :size="40" style="opacity:.4" />
                    <p style="margin-top:14px">Your bag is empty.</p>
                    <a href="{{ route('menu') }}" class="btn btn-ghost btn-sm" style="margin-top:8px">Browse the menu</a>
                </div>
            </div>
        @else
            <div style="flex:1;overflow-y:auto;padding:8px 24px">
                @foreach($cartItems as $item)
                    <div style="display:flex;gap:14px;padding:18px 0;border-bottom:1px solid var(--line-soft)">
                        <x-ph :label="$item['img'] ?? $item['name'] ?? 'catering spread'" :h="62" :w="62" :r="10" class="cust-cart-item-img" />
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;justify-content:space-between;gap:8px">
                                <div style="font-weight:600;font-size:15px">{{ $item['name'] }}</div>
                                <div style="color:var(--gold-400);font-weight:600">${{ number_format($item['price'] * $item['qty'], 2) }}</div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;margin-top:10px">
                                <div style="display:flex;align-items:center;border:1px solid var(--line);border-radius:999px">
                                    <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="qty" value="{{ max(0, $item['qty'] - 1) }}">
                                        <button type="submit" class="cust-qty-btn"><x-icon name="minus" :size="14" /></button>
                                    </form>
                                    <span style="min-width:22px;text-align:center;font-weight:600;font-size:14px">{{ $item['qty'] }}</span>
                                    <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                        <button type="submit" class="cust-qty-btn"><x-icon name="plus" :size="14" /></button>
                                    </form>
                                </div>
                                <form action="{{ route('cart.remove', $item['id']) }}" method="POST" style="margin-left:auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:var(--muted);cursor:pointer;display:grid;place-items:center">
                                        <x-icon name="trash" :size="16" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="padding:24px;border-top:1px solid var(--line);background:var(--ink-800)">
                <div style="display:flex;justify-content:space-between;font-size:14px;color:var(--sand);margin-bottom:8px">
                    <span>Subtotal</span><span>${{ number_format($cartSubtotal, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;color:var(--sand);margin-bottom:14px">
                    <span>Est. tax</span><span>${{ number_format($tax, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:19px;font-weight:700;margin-bottom:18px;font-family:var(--serif)">
                    <span>Total</span><span style="color:var(--gold-400)">${{ number_format($estTotal, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-gold" style="width:100%;justify-content:center">
                    Checkout <x-icon name="arrow" :size="18" />
                </a>
            </div>
        @endif
    </div>
</div>
