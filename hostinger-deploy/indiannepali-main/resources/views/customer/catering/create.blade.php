@extends('layouts.customer')

@push('styles')
<link rel="stylesheet" href="/css/catering.css">
@endpush

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Catering</div>
    <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Order catering online</h1>
    <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px;max-width:760px;margin-left:auto;margin-right:auto">
        Build your menu, choose tray sizes, and checkout — just like our live ordering site. Minimum {{ $minGuests }} guests for per-person catering.
    </p>
</div>

<div class="cust-catering-wrap">
    @if(session('success'))
        <div class="cust-card cust-catering-flash">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="cust-card cust-catering-flash cust-catering-flash--error">{{ session('error') }}</div>
    @endif

    <div class="cust-catering-tabs">
        <a href="{{ route('catering', ['tab' => 'per-person']) }}" class="cust-catering-tab {{ $tab === 'per-person' ? 'is-active' : '' }}">Per person · $5/guest</a>
        <a href="{{ route('catering', ['tab' => 'trays']) }}" class="cust-catering-tab {{ $tab === 'trays' ? 'is-active' : '' }}">By tray</a>
    </div>

    @if($tab === 'per-person')
        <form action="{{ route('catering.order') }}" method="POST" class="cust-form-shell cust-catering-form" id="catering-order-form">
            @csrf
            <div class="cust-gift-grid">
                <div class="cust-split-sticky cust-catering-media">
                    <x-ph label="catering spread" :src="\App\Support\StockImages::forLabel('catering spread')" :h="420" style="border:none;border-radius:18px" />
                </div>

                <div class="cust-card">
                    <h2>{{ $perPerson['title'] }}</h2>
                    <p class="cust-catering-price">${{ number_format($perPersonPrice, 2) }} <span>/ person</span></p>
                    <p class="cust-catering-desc">{{ $perPerson['description'] }}</p>

                    @if($errors->any())
                        <div class="cust-catering-flash cust-catering-flash--error" style="margin-bottom:18px">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <label class="cust-field">
                        <span>Number of guests <em style="color:var(--muted);font-style:normal;font-weight:400">(minimum {{ $minGuests }})</em></span>
                        <input class="cust-inp" type="number" name="guest_count" min="{{ $minGuests }}" step="1" value="{{ old('guest_count', $cart['per_person']['guest_count'] ?? $minGuests) }}" required id="catering-guest-count">
                    </label>

                    <div class="cust-catering-total" id="catering-total">
                        Estimated total: <strong>${{ number_format($perPersonPrice * max($minGuests, (int) old('guest_count', $cart['per_person']['guest_count'] ?? $minGuests)), 2) }}</strong>
                    </div>

                    <div class="cust-catering-groups">
                        @foreach($perPerson['groups'] as $group)
                            <details class="cust-catering-group" open>
                                <summary>{{ $group['label'] }}</summary>
                                <div class="cust-catering-options">
                                    @foreach($group['options'] as $option)
                                        @php
                                            $checked = in_array($option, old('selections.'.$group['id'], $cart['per_person']['selections'][$group['id']] ?? []), true);
                                        @endphp
                                        <label class="cust-catering-option">
                                            <input type="checkbox" name="selections[{{ $group['id'] }}][]" value="{{ $option }}" {{ $checked ? 'checked' : '' }}>
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-gold" style="width:100%;margin-top:8px">
                        Add catering to order · Checkout <x-icon name="arrow" :size="18" />
                    </button>

                    <p class="cust-catering-note">Orders under {{ $minGuests }} guests will not be fulfilled. Need help? <a href="{{ route('contact') }}">Contact us</a>.</p>
                </div>
            </div>
        </form>
    @else
        <div class="cust-catering-tray-intro cust-card">
            <h2>Catering menu (by tray)</h2>
            <p>Pre-packed trays for office lunches, pujas, and celebrations. Add trays to your cart and checkout with regular menu items if you like.</p>
        </div>

        <div class="cust-catering-tray-grid">
            @foreach($trays as $tray)
                <div class="cust-card cust-catering-tray-card">
                    <x-ph :label="$tray['name']" :src="\App\Support\StockImages::forLabel($tray['name'])" :h="180" style="border:none;border-radius:12px;margin-bottom:14px" />
                    <div class="cust-catering-tray-head">
                        <h3>{{ $tray['name'] }}</h3>
                        <span class="cust-catering-tray-price">${{ number_format($tray['price'], 2) }}</span>
                    </div>
                    <p class="cust-catering-tray-serves">{{ $tray['serves'] }}</p>
                    <p class="cust-catering-tray-desc">{{ $tray['description'] }}</p>
                    <form action="{{ route('catering.trays.add', $tray['slug']) }}" method="POST" class="cust-catering-tray-form">
                        @csrf
                        <button type="submit" class="btn btn-gold btn-sm" style="width:100%;justify-content:center">
                            <x-icon name="plus" :size="15" /> Add to order
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="cust-catering-tray-foot">
            <a href="{{ route('checkout') }}" class="btn btn-gold">Go to checkout</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const guestInput = document.getElementById('catering-guest-count');
    const totalEl = document.getElementById('catering-total');
    const unitPrice = {{ json_encode($perPersonPrice) }};
    const minGuests = {{ json_encode($minGuests) }};

    if (!guestInput || !totalEl) return;

    function updateTotal() {
        const guests = Math.max(minGuests, parseInt(guestInput.value || minGuests, 10));
        totalEl.innerHTML = 'Estimated total: <strong>$' + (guests * unitPrice).toFixed(2) + '</strong>';
    }

    guestInput.addEventListener('input', updateTotal);
    updateTotal();
})();
</script>
@endpush
