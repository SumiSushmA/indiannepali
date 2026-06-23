@props(['height' => 480, 'radius' => 18, 'h' => null, 'r' => null])

@php
$h = (int) ($h ?? $height);
$r = (int) ($r ?? $radius);
$address = trim(($site['address'] ?? '13754 Aurora Ave N, Suite D').', '.($site['city'] ?? 'Seattle, WA 98133'));
$mapsLink = 'https://maps.google.com/?q='.urlencode($address);
$embedUrl = $site['map_embed_url'] ?? ('https://maps.google.com/maps?q='.urlencode($address).'&z=15&ie=UTF8&iwloc=&output=embed');
$wrapperStyle = collect([
    "height: {$h}px",
    "border-radius: {$r}px",
    $attributes->get('style'),
])->filter()->implode('; ');
@endphp

<div {{ $attributes->except('style')->merge(['class' => 'cust-map-embed']) }} style="{{ $wrapperStyle }}">
    <iframe
        src="{{ $embedUrl }}"
        title="Map — {{ $site['restaurant_name'] ?? 'Indian-Nepali Kitchen' }}"
        loading="lazy"
        allowfullscreen
        referrerpolicy="no-referrer-when-downgrade"
    ></iframe>
    <a href="{{ $mapsLink }}" target="_blank" rel="noopener" class="cust-map-embed__link">
        Open in Google Maps <x-icon name="arrow" :size="14" />
    </a>
</div>
