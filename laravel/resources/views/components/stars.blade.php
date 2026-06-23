@props(['value' => 5, 'size' => 14])

<div style="display:inline-flex;gap:2px;align-items:center" aria-label="{{ $value }} out of 5 stars">
    @for ($i = 0; $i < 5; $i++)
        <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" aria-hidden="true" style="flex-shrink:0">
            <path
                d="M12 3.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.8L7.5 19.6l1-5.8L4.2 9.7l5.9-.9L12 3.5Z"
                fill="{{ $i < $value ? 'var(--gold-500)' : 'none' }}"
                stroke="var(--gold-500)"
                stroke-width="{{ $i < $value ? 0 : 1.5 }}"
                opacity="{{ $i < $value ? 1 : .35 }}"
            />
        </svg>
    @endfor
</div>
