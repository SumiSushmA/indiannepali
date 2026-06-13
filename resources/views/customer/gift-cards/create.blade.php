@extends('layouts.customer')

@section('content')
@if(session('gift_sent') && $purchase)
    <div style="min-height:80vh;display:grid;place-items:center;padding:120px 24px;text-align:center">
        <div class="fade-up" style="max-width:480px">
            <div style="width:88px;height:88px;border-radius:999px;margin:0 auto 26px;background:var(--gold-glow);border:1px solid var(--gold-600);display:grid;place-items:center;color:var(--gold-400)">
                <x-icon name="check" :size="42" />
            </div>
            <h1 style="font-size:44px">Gift sent!</h1>
            <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:16px">
                A ${{ number_format($purchase['amount'], 0) }} gift card is on its way
                @if($purchase['delivery'] === 'email') by email.@else — print or hand-deliver it whenever you like.@endif
            </p>
            <a href="{{ route('home') }}" class="btn btn-gold" style="margin-top:28px">Back to home</a>
        </div>
    </div>
@else
    <div class="cust-page-head cust-pad">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Gift cards</div>
        <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Give the gift of the feast</h1>
        <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">Send a digital gift card in seconds, or print one to hand over in person. Never expires.</p>
    </div>

    @php
    $accents = [
        'gold' => ['linear-gradient(135deg,#3a2a14,#c8852f)', '#f4ecdd'],
        'spice' => ['linear-gradient(135deg,#3a1810,#9c3b25)', '#fff'],
        'leaf' => ['linear-gradient(135deg,#1c2a18,#4f7d44)', '#fff'],
    ];
    $design = old('design', 'gold');
    $amount = old('amount', 50);
    @endphp

    <form action="{{ route('giftcards.store') }}" method="POST" style="max-width:1040px;margin:0 auto;padding:44px 32px 110px" id="gift-form">
        @csrf
        <div class="cust-gift-grid">
            <div id="gift-preview" style="border-radius:18px;background:{{ $accents[$design][0] }};color:{{ $accents[$design][1] }};padding:26px;aspect-ratio:1.6;display:flex;flex-direction:column;justify-content:space-between;box-shadow:var(--shadow-3);border:1px solid rgba(255,255,255,.12);position:sticky;top:100px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div style="font-family:var(--serif);font-size:24px;font-weight:600;line-height:1">Indian Nepali</div>
                        <div style="font-size:10px;letter-spacing:.4em;text-transform:uppercase;margin-top:5px;opacity:.85">Kitchen</div>
                    </div>
                    <svg width="34" height="34" viewBox="0 0 48 48" style="opacity:.9"><path d="M24 11 L33 24 L24 37 L15 24 Z" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="24" cy="24" r="4" fill="currentColor"/></svg>
                </div>
                <div>
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.16em;opacity:.8">Gift Card</div>
                    <div id="gift-preview-amount" style="font-family:var(--serif);font-size:46px;font-weight:600;line-height:1">${{ $amount }}</div>
                </div>
            </div>

            <div class="cust-card">
                <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:12px">Choose a design</div>
                <div style="display:flex;gap:10px;margin-bottom:26px">
                    @foreach($giftDesigns as $d)
                        <label class="cust-gift-design">
                            <input type="radio" name="design" value="{{ $d['id'] }}" class="cust-sr-input" {{ $design === $d['id'] ? 'checked' : '' }} data-bg="{{ $accents[$d['id']][0] }}" data-color="{{ $accents[$d['id']][1] }}">
                            <div style="height:30px;border-radius:6px;margin-bottom:9px;background:{{ $accents[$d['id']][0] }}"></div>
                            <div style="font-weight:600;font-size:13.5px">{{ $d['name'] }}</div>
                            <div style="font-size:11.5px;color:var(--muted)">{{ $d['sub'] }}</div>
                        </label>
                    @endforeach
                </div>

                <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:12px">Amount</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:10px">
                    @foreach($giftAmounts as $a)
                        <label class="cust-gift-amount">
                            <input type="radio" name="amount_preset" value="{{ $a }}" class="cust-sr-input gift-amount-radio" {{ (int)$amount === $a && !old('custom_amount') ? 'checked' : '' }}>${{ $a }}
                        </label>
                    @endforeach
                </div>
                <input type="number" id="gift-custom-amount" min="10" max="500" placeholder="Or enter custom amount" class="cust-inp" style="margin-bottom:26px" value="{{ old('custom_amount') }}">
                <input type="hidden" name="amount" id="gift-amount-hidden" value="{{ $amount }}">

                <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:12px">How to deliver</div>
                <div style="display:flex;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:4px;gap:4px;margin-bottom:18px">
                    @foreach([['email', 'Email'], ['print', 'Print at home'], ['mail', 'Physical card']] as [$v, $label])
                        <label class="cust-gift-delivery">
                            <input type="radio" name="delivery" value="{{ $v }}" class="cust-sr-input" {{ old('delivery', 'email') === $v ? 'checked' : '' }}>{{ $label }}
                        </label>
                    @endforeach
                </div>

                <div style="display:grid;gap:14px;margin-bottom:22px">
                    <input class="cust-inp" name="recipient" placeholder="Recipient email or name" required value="{{ old('recipient') }}">
                    <input class="cust-inp" name="sender" placeholder="Your name" required value="{{ old('sender') }}">
                    <textarea class="cust-inp" name="message" placeholder="Add a message (optional)" style="min-height:70px;resize:vertical">{{ old('message') }}</textarea>
                </div>

                <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:12px">Payment</div>
                <div style="display:inline-flex;align-items:center;gap:8px;margin-bottom:14px;font-size:13px;color:var(--muted)">{{ $toastPayment['label'] ?? 'Demo payments' }}</div>
                @if($errors->has('payment'))
                    <div style="padding:12px 14px;margin-bottom:14px;border-radius:10px;border:1px solid var(--spice-600);color:var(--spice-400);font-size:14px">{{ $errors->first('payment') }}</div>
                @endif
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:22px">
                    <label class="cust-field full"><span>Card number</span><input class="cust-inp" name="card_number" placeholder="4242 4242 4242 4242" required value="{{ old('card_number') }}"></label>
                    <label class="cust-field"><span>Expiry</span><input class="cust-inp" name="card_expiry" placeholder="MM / YY" value="{{ old('card_expiry') }}"></label>
                    <label class="cust-field"><span>CVC</span><input class="cust-inp" name="card_cvc" placeholder="123" value="{{ old('card_cvc') }}"></label>
                </div>

                <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center"><x-icon name="tag" :size="18" /> Buy gift card</button>
            </div>
        </div>

        <div class="cust-card" style="margin-top:24px;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap">
            <div>
                <h3 style="font-size:22px">Have a gift card?</h3>
                <p style="color:var(--muted);font-size:14.5px;margin-top:6px">Enter the code at checkout, or check a balance below.</p>
            </div>
            <div style="display:flex;gap:10px;flex:1;min-width:260px;max-width:420px">
                <input class="cust-inp" id="gift-balance-code" placeholder="Gift card code" style="flex:1">
                <button type="button" class="btn btn-ghost" id="gift-check-balance">Check balance</button>
            </div>
            <p id="gift-balance-msg" style="width:100%;margin-top:12px;font-size:14px;color:var(--muted);display:none"></p>
        </div>
    </form>
@endif
@endsection

@push('scripts')
<script>
(function () {
    const preview = document.getElementById('gift-preview');
    const amountEl = document.getElementById('gift-preview-amount');
    const customInput = document.getElementById('gift-custom-amount');
    const hiddenAmount = document.getElementById('gift-amount-hidden');
    function updateAmount(v) {
        if (amountEl && v) amountEl.textContent = '$' + v;
        if (hiddenAmount && v) hiddenAmount.value = v;
    }
    document.querySelectorAll('.gift-amount-radio').forEach(r => {
        r.addEventListener('change', () => { customInput.value = ''; updateAmount(r.value); });
    });
    customInput?.addEventListener('input', () => {
        document.querySelectorAll('.gift-amount-radio').forEach(r => r.checked = false);
        updateAmount(customInput.value || '0');
    });
    document.querySelectorAll('input[name="design"]').forEach(r => {
        r.addEventListener('change', () => {
            preview.style.background = r.dataset.bg;
            preview.style.color = r.dataset.color;
        });
    });

    const balanceBtn = document.getElementById('gift-check-balance');
    const balanceCode = document.getElementById('gift-balance-code');
    const balanceMsg = document.getElementById('gift-balance-msg');
    balanceBtn?.addEventListener('click', () => {
        const code = balanceCode?.value.trim();
        if (!code) {
            balanceMsg.style.display = 'block';
            balanceMsg.style.color = 'var(--spice-400)';
            balanceMsg.textContent = 'Please enter a gift card code.';
            return;
        }
        balanceMsg.style.display = 'block';
        balanceMsg.style.color = 'var(--sand)';
        balanceMsg.textContent = 'Balance lookup is not available online yet. Please call {{ $site['phone'] ?? '(206) 397-3211' }} or visit us with your code.';
    });
})();
</script>
@endpush
