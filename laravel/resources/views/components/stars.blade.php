@props(['value' => 5, 'size' => 14])

<div style="display:inline-flex;gap:2px;color:var(--gold-500)">
    @for ($i = 0; $i < 5; $i++)
        <x-icon name="star" :size="$size" :color="$i < $value ? 'var(--gold-500)' : 'var(--gold-500)'" style="opacity:{{ $i < $value ? 1 : .3 }}" />
    @endfor
</div>
