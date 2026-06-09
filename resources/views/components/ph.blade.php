@props(['label', 'h' => null, 'r' => 0])

<div {{ $attributes->merge(['class' => 'ph']) }}
     @if(is_numeric($h)) style="height:{{ $h }}px;border-radius:{{ $r }}px" @elseif($r) style="border-radius:{{ $r }}px" @endif>
    <span>{{ $label }}</span>
</div>
