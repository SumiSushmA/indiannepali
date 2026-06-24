@props([
    'name',
    'label',
    'value' => '',
    'required' => false,
    'autocomplete' => 'current-password',
    'hint' => null,
])

<label {{ $attributes->class(['cust-field', 'acct-pass-field']) }}>
    <span>{{ $label }}</span>
    <div class="acct-pass-wrap">
        <input
            class="cust-inp acct-pass-inp"
            type="password"
            name="{{ $name }}"
            value="{{ $value }}"
            @if($required) required @endif
            autocomplete="{{ $autocomplete }}"
        >
        <button type="button" class="acct-pass-toggle" aria-label="Show password" data-pass-toggle>
            <x-icon name="eye" :size="18" />
        </button>
    </div>
    @if($hint)
        <span class="acct-hint">{{ $hint }}</span>
    @endif
</label>
