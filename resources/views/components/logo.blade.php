@props(['size' => 38, 'href' => null])

@php
$gold = '#d4a24e';
$tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'cust-logo', 'style' => 'display:flex;align-items:center;gap:'.($size * 0.3).'px;text-decoration:none;color:inherit;cursor:'.($href ? 'pointer' : 'default').';']) }}>
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 48 48" style="flex-shrink:0">
        <circle cx="24" cy="24" r="22" fill="none" stroke="{{ $gold }}" stroke-width="1.4" opacity=".55"/>
        <circle cx="24" cy="24" r="16.5" fill="none" stroke="{{ $gold }}" stroke-width="1"/>
        <path d="M24 11 L33 24 L24 37 L15 24 Z" fill="none" stroke="{{ $gold }}" stroke-width="1.4"/>
        <circle cx="24" cy="24" r="4.4" fill="{{ $gold }}"/>
    </svg>
    <div style="line-height:1">
        <div style="font-family:var(--serif);font-weight:600;font-size:{{ $size * 0.5 }}px;letter-spacing:.02em;color:var(--cream)">Indian Nepali</div>
        <div style="font-family:var(--sans);font-weight:600;font-size:{{ $size * 0.235 }}px;letter-spacing:.42em;text-transform:uppercase;color:{{ $gold }};margin-top:3px;padding-left:2px">Kitchen</div>
    </div>
</{{ $tag }}>
