@extends('layouts.customer')

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Gallery</div>
    <h1>A look inside</h1>
    <p class="cust-text-sand cust-page-lead">The plates, the room, and the feasts we cater — browse by what you're after.</p>
</div>

<div class="cust-gallery-page">
    <div class="cust-gallery-tabs">
        <a href="{{ route('gallery') }}" class="cust-tab {{ $tab === 'all' ? 'active' : '' }}">All</a>
        @foreach($cats as $c)
            <a href="{{ route('gallery', ['tab' => $c['id']]) }}" class="cust-tab {{ $tab === $c['id'] ? 'active' : '' }}">{{ $c['name'] }}</a>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);grid-auto-rows:230px;gap:14px;grid-auto-flow:dense">
        @foreach($shown as $i => $g)
            <button type="button" class="gallery-item cust-gallery-tile-btn" data-label="{{ $g['label'] }}" data-url="{{ $g['url'] }}" style="grid-column:{{ ($spans[$i % count($spans)] ?? 1) === 2 ? 'span 2' : 'auto' }}">
                @if($g['url'])
                    <img src="{{ $g['url'] }}" alt="{{ $g['label'] }}" loading="lazy">
                @else
                    <div class="ph cust-gallery-ph"><span>{{ $g['label'] }}</span></div>
                @endif
                <span class="cust-gallery-cap">{{ $g['cat'] }}</span>
            </button>
        @endforeach
    </div>
</div>

<div id="gallery-lightbox" class="cust-lightbox">
    <div class="cust-lightbox-inner">
        <img id="gallery-lb-img" src="" alt="">
        <p id="gallery-lb-label" class="cust-text-cream" style="text-align:center;margin-top:14px"></p>
        <button type="button" id="gallery-lb-close" aria-label="Close">
            <x-icon name="x" :size="30" />
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const lb = document.getElementById('gallery-lightbox');
    const img = document.getElementById('gallery-lb-img');
    const label = document.getElementById('gallery-lb-label');
    document.querySelectorAll('.gallery-item').forEach(btn => {
        btn.addEventListener('click', () => {
            img.src = btn.dataset.url;
            img.alt = btn.dataset.label;
            label.textContent = btn.dataset.label;
            lb.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    });
    function closeLb() {
        lb.classList.remove('open');
        document.body.style.overflow = '';
    }
    document.getElementById('gallery-lb-close')?.addEventListener('click', closeLb);
    lb?.addEventListener('click', e => { if (e.target === lb) closeLb(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLb(); });
})();
</script>
@endpush
