@extends('layouts.customer')

@push('styles')
<link rel="stylesheet" href="/css/catering.css?v={{ filemtime(public_path('css/catering.css')) }}">
@endpush

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Catering</div>
    <h1>Order catering online</h1>
    <p class="cust-text-sand cust-page-lead" style="max-width:760px;margin-left:auto;margin-right:auto">
        Build your menu here, then checkout and pay on our secure payment page. Minimum {{ $minGuests }} guests for per-person catering.
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
        <a href="{{ route('catering', ['tab' => 'per-person']) }}" class="cust-catering-tab {{ $tab === 'per-person' ? 'is-active' : '' }}">Per person · from $5/guest</a>
        <a href="{{ route('catering', ['tab' => 'trays']) }}" class="cust-catering-tab {{ $tab === 'trays' ? 'is-active' : '' }}">By tray</a>
    </div>

    @if($tab === 'per-person')
        <form action="{{ route('catering.order') }}" method="POST" class="cust-form-shell cust-catering-form" id="catering-order-form">
            @csrf
            <div class="cust-gift-grid">
                <div class="cust-split-sticky cust-catering-media">
                    <x-ph label="catering spread" :src="\App\Support\StockImages::scene('catering spread')" :h="420" style="border:none;border-radius:18px" />
                </div>

                <div class="cust-card">
                    <h2>{{ $perPerson['title'] }}</h2>
                    <p class="cust-catering-price">${{ number_format($perPersonPrice, 2) }} <span>/ person base</span></p>
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

                    @if(!empty($perPerson['prep_notice']))
                        <div class="cust-catering-prep">
                            <x-icon name="info" :size="16" color="var(--gold-400)" />
                            <span>{{ $perPerson['prep_notice'] }}</span>
                        </div>
                    @endif

                    @php
                        $initialGuests = max($minGuests, (int) old('guest_count', $cart['per_person']['guest_count'] ?? $minGuests));
                        $initialSelections = old('selections', $cart['per_person']['selections'] ?? []);
                        $initialTotal = \App\Data\CateringMenu::perPersonTotal($initialGuests, $initialSelections);
                    @endphp
                    <div class="cust-catering-total" id="catering-total">
                        Estimated total: <strong id="catering-total-amount">${{ number_format($initialTotal, 2) }}</strong>
                        <span class="cust-catering-total-note" id="catering-total-note"></span>
                    </div>

                    <div class="cust-catering-groups">
                        @foreach($perPerson['groups'] as $group)
                            <details class="cust-catering-group" open>
                                <summary>
                                    <span class="cust-catering-group-title">{{ $group['label'] }}</span>
                                    @if(!empty($group['optional']))
                                        <span class="cust-catering-group-tag">Optional</span>
                                    @endif
                                </summary>
                                <div class="cust-catering-options {{ !empty($group['optional']) ? 'cust-catering-options--included' : '' }}">
                                    @foreach($group['options'] as $option)
                                        @php
                                            $checked = in_array($option['name'], old('selections.'.$group['id'], $cart['per_person']['selections'][$group['id']] ?? []), true);
                                        @endphp
                                        <label class="cust-catering-option">
                                            <span class="cust-catering-option__label">
                                                <input type="checkbox" name="selections[{{ $group['id'] }}][]" value="{{ $option['name'] }}" data-price="{{ number_format($option['price'], 2, '.', '') }}" {{ $checked ? 'checked' : '' }}>
                                                <span>{{ $option['name'] }}</span>
                                            </span>
                                            @if(!empty($option['included']))
                                                <span class="cust-catering-option__price cust-catering-option__price--included">Included</span>
                                            @else
                                                <span class="cust-catering-option__price">${{ number_format($option['price'], 2) }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-gold btn-sm" style="width:100%;margin-top:8px;justify-content:center">
                        Checkout <x-icon name="arrow" :size="15" />
                    </button>

                    <p class="cust-catering-note">Payment is collected on checkout{{ ($toastPayment['live'] ?? false) ? ' via Toast Payments' : '' }} — not on the Toast menu site.</p>

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
                    <x-ph :label="$tray['name']" :src="\App\Support\StockImages::forLabel($tray['name']) ?: \App\Support\StockImages::scene('catering spread')" :h="180" style="border:none;border-radius:12px;margin-bottom:14px" />
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
            <a href="{{ route('checkout') }}" class="btn btn-gold btn-sm">Go to checkout</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const guestInput = document.getElementById('catering-guest-count');
    const totalAmountEl = document.getElementById('catering-total-amount');
    const totalNoteEl = document.getElementById('catering-total-note');
    const basePrice = {{ json_encode($perPersonPrice) }};
    const minGuests = {{ json_encode($minGuests) }};
    const checkboxes = document.querySelectorAll('.cust-catering-option input[type="checkbox"]');

    if (!guestInput || !totalAmountEl) return;

    function selectedUnitPrice() {
        let unit = basePrice;
        checkboxes.forEach(function (box) {
            if (box.checked) {
                unit += parseFloat(box.dataset.price || '0');
            }
        });
        return unit;
    }

    function updateTotal() {
        const guests = Math.max(minGuests, parseInt(guestInput.value || minGuests, 10));
        const unit = selectedUnitPrice();
        const total = unit * guests;
        totalAmountEl.textContent = '$' + total.toFixed(2);
        if (totalNoteEl) {
            totalNoteEl.textContent = ' ($' + unit.toFixed(2) + ' / guest × ' + guests + ' guests)';
        }
    }

    guestInput.addEventListener('input', updateTotal);
    checkboxes.forEach(function (box) {
        box.addEventListener('change', updateTotal);
    });
    updateTotal();
})();
</script>
@endpush
