@extends('layouts.customer')

@section('content')
@if(session('gift_sent') && $purchase)
    <div style="min-height:80vh;display:grid;place-items:center;padding:120px 24px;text-align:center">
        <div class="fade-up" style="max-width:520px;width:100%">
            <div style="width:88px;height:88px;border-radius:999px;margin:0 auto 26px;background:var(--gold-glow);border:1px solid var(--gold-600);display:grid;place-items:center;color:var(--gold-400)">
                <x-icon name="check" :size="42" />
            </div>
            <h1 style="font-size:44px">Gift card ready!</h1>
            <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:16px">
                @if($purchase['delivery'] === 'email')
                    A ${{ number_format($purchase['amount'], 0) }} gift card was emailed to <strong style="color:var(--cream)">{{ $purchase['recipient'] }}</strong>.
                @elseif($purchase['delivery'] === 'print')
                    Save or print the code below to hand-deliver your ${{ number_format($purchase['amount'], 0) }} gift card.
                @else
                    Your ${{ number_format($purchase['amount'], 0) }} gift card order is confirmed. Use the code below online until the physical card arrives.
                @endif
            </p>

            <div class="cust-gift-code-box" style="margin:28px 0 22px">
                <div class="cust-gift-code-box__label">Gift card code</div>
                <div class="cust-gift-code-box__code" id="gift-success-code">{{ $purchase['code'] }}</div>
                <button type="button" class="btn btn-ghost btn-sm" id="gift-copy-code" style="margin-top:14px">Copy code</button>
            </div>

            <p style="color:var(--muted);font-size:14px;line-height:1.6;margin-bottom:28px">
                The recipient enters this code at checkout. You can also check the balance anytime on the gift cards page.
            </p>
            <a href="{{ route('home') }}" class="btn btn-gold">Back to home</a>
        </div>
    </div>
@else
    <div class="cust-page-head cust-pad">
        <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Gift cards</div>
        <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">Give the gift of the feast</h1>
        <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">Send a digital gift card in seconds, or print one to hand over in person. Never expires.</p>
    </div>

    @php
    $defaultDesign = $giftDesigns[0] ?? ['id' => 'gold', 'bg' => 'linear-gradient(125deg, #c9922a 0%, #e8c56a 48%, #f8e8b8 100%)', 'text' => '#3a2810'];
    $design = old('design', $defaultDesign['id']);
    $selectedDesign = collect($giftDesigns)->firstWhere('id', $design) ?? $defaultDesign;
    $amount = old('amount', 50);
    @endphp

    <form action="{{ route('giftcards.store') }}" method="POST" class="cust-form-shell" id="gift-form">
        @csrf
        <div class="cust-gift-grid">
            <div id="gift-preview" class="cust-split-sticky cust-gift-preview" style="background:{{ $selectedDesign['bg'] }};color:{{ $selectedDesign['text'] }}">
                <img src="/logo.png" alt="" class="cust-gift-preview__logo-bg" aria-hidden="true">
                <div class="cust-gift-preview__surface">
                    <div class="cust-gift-preview__head">
                        <div>
                            <div class="cust-gift-preview__brand">Indian Nepali</div>
                            <div class="cust-gift-preview__tag">Kitchen</div>
                        </div>
                        <svg width="34" height="34" viewBox="0 0 48 48" class="cust-gift-preview__mark" aria-hidden="true"><path d="M24 11 L33 24 L24 37 L15 24 Z" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="24" cy="24" r="4" fill="currentColor"/></svg>
                    </div>
                    <div>
                        <div class="cust-gift-preview__label">Gift Card</div>
                        <div id="gift-preview-amount" class="cust-gift-preview__amount">${{ $amount }}</div>
                    </div>
                </div>
            </div>

            <div class="cust-card">
                <div class="cust-field-label">Choose a design</div>
                <div class="cust-gift-designs">
                    @foreach($giftDesigns as $d)
                        <label class="cust-gift-design">
                            <input type="radio" name="design" value="{{ $d['id'] }}" class="cust-sr-input" {{ $design === $d['id'] ? 'checked' : '' }} data-bg="{{ $d['bg'] }}" data-color="{{ $d['text'] }}">
                            <span class="cust-gift-design__card" style="background:{{ $d['bg'] }}"></span>
                            <span class="cust-gift-design__name">{{ $d['name'] }}</span>
                            <span class="cust-gift-design__sub">{{ $d['sub'] }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="cust-field-label">Amount</div>
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

        <div class="cust-card cust-gift-redeem">
            <div>
                <h3 style="font-size:22px">Have a gift card?</h3>
                <p style="color:var(--muted);font-size:14.5px;margin-top:6px;line-height:1.55">
                    Your code appears on the confirmation screen after purchase, and by email when you choose email delivery. Enter it at checkout or check the balance below.
                </p>
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
@if(session('gift_sent') && $purchase)
<script>
(function () {
    const codeEl = document.getElementById('gift-success-code');
    const copyBtn = document.getElementById('gift-copy-code');
    copyBtn?.addEventListener('click', async () => {
        const code = codeEl?.textContent?.trim();
        if (!code) return;
        try {
            await navigator.clipboard.writeText(code);
            copyBtn.textContent = 'Copied!';
            setTimeout(() => { copyBtn.textContent = 'Copy code'; }, 2000);
        } catch (e) {
            copyBtn.textContent = 'Copy failed';
        }
    });
})();
</script>
@else
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
    balanceBtn?.addEventListener('click', async () => {
        const code = balanceCode?.value.trim();
        if (!code) {
            balanceMsg.style.display = 'block';
            balanceMsg.style.color = 'var(--spice-400)';
            balanceMsg.textContent = 'Please enter a gift card code.';
            return;
        }
        balanceMsg.style.display = 'block';
        balanceMsg.style.color = 'var(--sand)';
        balanceMsg.textContent = 'Checking balance…';
        try {
            const res = await fetch('{{ route('giftcards.balance') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ code }),
            });
            const data = await res.json();
            if (data.ok) {
                balanceMsg.style.color = 'var(--gold-400)';
                balanceMsg.textContent = 'Balance for ' + data.code + ': $' + Number(data.balance).toFixed(2);
            } else {
                balanceMsg.style.color = 'var(--spice-400)';
                balanceMsg.textContent = data.message || 'Gift card not found.';
            }
        } catch (e) {
            balanceMsg.style.color = 'var(--spice-400)';
            balanceMsg.textContent = 'Could not check balance. Please try again.';
        }
    });
})();
</script>
@endif
@endpush
