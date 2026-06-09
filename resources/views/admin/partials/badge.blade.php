@php
$toneClass = match($tone ?? 'neutral') {
    'gold' => 'adm-badge-gold',
    'green' => 'adm-badge-green',
    'red' => 'adm-badge-red',
    'blue' => 'adm-badge-blue',
    'purple' => 'adm-badge-purple',
    default => 'adm-badge-neutral',
};
@endphp
<span class="adm-badge {{ $toneClass }}">
    @if(!empty($dot))<span class="adm-badge-dot"></span>@endif
    {{ $label ?? '' }}
</span>
