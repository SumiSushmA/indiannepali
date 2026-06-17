@props(['item', 'h' => null, 'w' => null, 'r' => 0])

@php
$url = \App\Support\StockImages::resolve($item['img'] ?? $item['name'] ?? '', $item['image_path'] ?? null);
$useFrame = is_numeric($h) || is_numeric($w);
$frameStyle = collect([
    is_numeric($h) ? "height:{$h}px" : null,
    is_numeric($w) ? "width:{$w}px" : null,
    $r ? "border-radius:{$r}px" : null,
])->filter()->join('; ');
$passedStyle = trim((string) $attributes->get('style', ''));
$mergedStyle = collect([$frameStyle, $passedStyle])->filter()->join('; ');
$attributes = $attributes->except('style');
@endphp

@if($useFrame)
<div {{ $attributes->merge(['class' => 'cust-img-frame']) }} @if($mergedStyle) style="{{ $mergedStyle }}" @endif>
    <img
        src="{{ $url }}"
        alt="{{ $item['img'] ?? $item['name'] ?? '' }}"
        class="cust-img"
        loading="lazy"
    >
</div>
@else
<img
    src="{{ $url }}"
    alt="{{ $item['img'] ?? $item['name'] ?? '' }}"
    {{ $attributes->merge(['class' => 'cust-img food-img']) }}
    @if($mergedStyle) style="{{ $mergedStyle }}" @elseif($r) style="border-radius:{{ $r }}px" @endif
    loading="lazy"
>
@endif
