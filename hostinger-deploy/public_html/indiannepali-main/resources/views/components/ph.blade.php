@props(['label', 'h' => null, 'w' => null, 'r' => 0, 'src' => null])

@php
$url = $src ?? \App\Support\StockImages::forLabel($label);
$useFrame = (bool) $url && (is_numeric($h) || is_numeric($w));
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
        class="cust-img ph-img"
        loading="lazy"
    >
</div>
@elseif($url)
<img
    src="{{ $url }}"
    alt="{{ $label }}"
    {{ $attributes->merge(['class' => 'cust-img ph-img']) }}
    @if($mergedStyle) style="{{ $mergedStyle }}" @endif
    loading="lazy"
>
@else
<div {{ $attributes->merge(['class' => 'ph']) }}
     @if($mergedStyle) style="{{ $mergedStyle }}" @endif>
    <span>{{ $label }}</span>
</div>
@endif
