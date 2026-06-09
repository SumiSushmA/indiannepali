@props(['item', 'h' => null, 'r' => 0])

@if(!empty($item['image_path']))
    <img
        src="{{ Storage::url($item['image_path']) }}"
        alt="{{ $item['img'] ?? $item['name'] ?? '' }}"
        {{ $attributes->merge(['class' => 'food-img']) }}
        @if(is_numeric($h))
            style="height:{{ $h }}px;border-radius:{{ $r }}px;object-fit:cover;width:100%"
        @elseif($r)
            style="border-radius:{{ $r }}px;object-fit:cover;width:100%"
        @endif
    >
@else
    <x-ph :label="$item['img'] ?? ''" :h="$h" :r="$r" {{ $attributes }} />
@endif
