@props(['item', 'h' => null, 'w' => null, 'r' => 0])

@php
$url = \App\Support\StockImages::resolve($item['img'] ?? $item['name'] ?? '', $item['image_path'] ?? null);
$label = $item['img'] ?? $item['name'] ?? '';
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

@if($url && $useFrame)
<div {{ $attributes->merge(['class' => 'cust-img-frame']) }} @if($mergedStyle) style="{{ $mergedStyle }}" @endif>
    <img
        src="{{ $url }}"
        alt="{{ $label }}"
        class="cust-img"
        loading="lazy"
    >
</div>
@elseif($url)
<img
    src="{{ $url }}"
    alt="{{ $label }}"
    {{ $attributes->merge(['class' => 'cust-img food-img']) }}
    @if($mergedStyle) style="{{ $mergedStyle }}" @elseif($r) style="border-radius:{{ $r }}px" @endif
    loading="lazy"
>
@elseif($useFrame)
<div {{ $attributes->merge(['class' => 'cust-img-frame ph']) }} @if($mergedStyle) style="{{ $mergedStyle }}" @endif>
    <span>{{ $label }}</span>
</div>
@else
<div {{ $attributes->merge(['class' => 'ph food-img']) }}
     @if($mergedStyle) style="{{ $mergedStyle }}" @elseif($r) style="border-radius:{{ $r }}px" @endif>
    <span>{{ $label }}</span>
</div>
@endif
