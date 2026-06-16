@props(['size' => 38, 'href' => null, 'showText' => false])

@php
$tag = $href ? 'a' : 'div';
$imgH = (int) round($size * 1.15);
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'cust-logo', 'style' => 'display:flex;align-items:center;gap:'.($showText ? ($size * 0.28) : 0).'px;text-decoration:none;color:inherit;cursor:'.($href ? 'pointer' : 'default').';']) }}>
    <img
        src="/logo.png"
        alt="Indian-Nepali Kitchen"
        width="{{ $imgH }}"
        height="{{ $imgH }}"
        style="height:{{ $imgH }}px;width:auto;object-fit:contain;flex-shrink:0;display:block"
    >
    @if($showText)
        <div style="line-height:1">
            <div style="font-family:var(--sans);font-weight:700;font-size:{{ $size * 0.42 }}px;letter-spacing:.04em;text-transform:uppercase;color:var(--brand-500)">Indian-Nepali</div>
            <div style="font-family:var(--serif);font-style:italic;font-weight:500;font-size:{{ $size * 0.32 }}px;color:var(--cream);margin-top:2px">Kitchen</div>
        </div>
    @endif
</{{ $tag }}>
