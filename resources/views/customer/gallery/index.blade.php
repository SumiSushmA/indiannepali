@extends('layouts.customer')

@section('content')
<div class="cust-page-head cust-pad">
    <div class="eyebrow center" style="justify-content:center;margin-bottom:16px">Gallery</div>
    <h1 style="font-size:clamp(38px,5vw,62px);line-height:1.03">A look inside</h1>
    <p style="color:var(--sand);font-size:17px;line-height:1.65;margin-top:18px">The plates, the room, and the feasts we cater — browse by what you're after.</p>
</div>

<div style="max-width:1200px;margin:0 auto;padding:0 32px 110px">
    <div style="display:flex;justify-content:center;gap:8px;margin:40px 0 36px;flex-wrap:wrap">
        <a href="{{ route('gallery') }}" class="btn btn-sm" style="border-radius:999px;padding:10px 20px;background:{{ $tab === 'all' ? 'var(--gold-600)' : 'var(--ink-700)' }};color:{{ $tab === 'all' ? '#211405' : 'var(--cream-2)' }};border:1px solid {{ $tab === 'all' ? 'var(--gold-600)' : 'var(--line)' }};text-decoration:none">All</a>
        @foreach($cats as $c)
            <a href="{{ route('gallery', ['tab' => $c['id']]) }}" class="btn btn-sm" style="border-radius:999px;padding:10px 20px;background:{{ $tab === $c['id'] ? 'var(--gold-600)' : 'var(--ink-700)' }};color:{{ $tab === $c['id'] ? '#211405' : 'var(--cream-2)' }};border:1px solid {{ $tab === $c['id'] ? 'var(--gold-600)' : 'var(--line)' }};text-decoration:none">{{ $c['name'] }}</a>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);grid-auto-rows:230px;gap:14px;grid-auto-flow:dense">
        @foreach($shown as $i => $g)
            <button type="button" class="gallery-item" data-label="{{ $g['label'] }}" style="padding:0;border:none;cursor:pointer;border-radius:14px;overflow:hidden;position:relative;grid-column:{{ ($spans[$i % count($spans)] ?? 1) === 2 ? 'span 2' : 'auto' }}">
                <x-ph :label="$g['label']" style="width:100%;height:100%" />
                <span style="position:absolute;left:12px;bottom:12px;font-size:10.5px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-400);background:rgba(13,10,8,.7);backdrop-filter:blur(6px);padding:4px 9px;border-radius:999px;border:1px solid var(--gold-700)">{{ $g['cat'] }}</span>
            </button>
        @endforeach
    </div>
</div>

<div id="gallery-lightbox" style="display:none;position:fixed;inset:0;z-index:400;background:rgba(0,0,0,.85);backdrop-filter:blur(8px);place-items:center;padding:40px">
    <div style="position:relative;width:min(900px,90vw);height:min(600px,80vh)">
        <x-ph id="gallery-lb-ph" label="" style="width:100%;height:100%" :r="16" />
        <button type="button" id="gallery-lb-close" style="position:absolute;top:-50px;right:0;background:none;border:none;color:#fff;cursor:pointer">
            <x-icon name="x" :size="30" />
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const lb = document.getElementById('gallery-lightbox');
    const ph = document.getElementById('gallery-lb-ph');
    document.querySelectorAll('.gallery-item').forEach(btn => {
        btn.addEventListener('click', () => {
            ph.querySelector('span').textContent = btn.dataset.label;
            lb.style.display = 'grid';
        });
    });
    document.getElementById('gallery-lb-close')?.addEventListener('click', () => lb.style.display = 'none');
    lb?.addEventListener('click', e => { if (e.target === lb) lb.style.display = 'none'; });
})();
</script>
@endpush
