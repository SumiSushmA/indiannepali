@extends('layouts.admin')

@php
$total = $galleryCats->sum(fn($c) => $c->images->count());
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Gallery</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $total }} images live · {{ $galleryCats->count() }} categories</p>
    </div>
</div>

@if(session('success'))
    <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--gold-700);color:var(--gold-400)">{{ session('success') }}</div>
@endif

<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach($galleryCats as $c)
    <a href="{{ route('admin.gallery.index', ['category' => $c->slug]) }}" style="text-decoration:none;background:{{ $activeCategory && $c->id === $activeCategory->id ? 'var(--gold-600)' : 'var(--ink-700)' }};color:{{ $activeCategory && $c->id === $activeCategory->id ? '#211405' : 'var(--cream-2)' }};border:1px solid {{ $activeCategory && $c->id === $activeCategory->id ? 'var(--gold-600)' : 'var(--line)' }};border-radius:999px;padding:9px 16px;cursor:pointer;font-size:13.5px;font-weight:600;font-family:var(--sans);display:flex;gap:8px;align-items:center;">
        {{ $c->name }} <span style="font-size:11px;opacity:.7;">{{ $c->images->count() }}</span>
    </a>
    @endforeach
</div>

@if($activeCategory)
<div class="adm-card" style="padding:22px;margin-bottom:20px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Upload to {{ $activeCategory->name }}</h3>
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        @csrf
        <input type="hidden" name="gallery_category_id" value="{{ $activeCategory->id }}">
        <label style="display:flex;flex-direction:column;gap:6px;flex:1;min-width:180px;">
            <span style="font-size:13px;color:var(--sand);font-weight:600;">Caption</span>
            <input name="caption" required placeholder="Image caption" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </label>
        <label style="display:flex;flex-direction:column;gap:6px;">
            <span style="font-size:13px;color:var(--sand);font-weight:600;">Image file</span>
            <input name="image" type="file" accept="image/*" required style="color:var(--sand);font-size:14px;">
        </label>
        <button type="submit" class="btn btn-gold btn-sm"><x-icon name="plus" :size="16"/> Upload</button>
    </form>
</div>

<div class="adm-gallery-grid">
    @foreach($activeCategory->images as $img)
    <div style="position:relative;border-radius:14px;overflow:hidden;aspect-ratio:1;">
        @if($img->image_path)
            <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $img->caption }}" style="width:100%;height:100%;object-fit:cover;">
        @else
            <div class="ph" style="width:100%;height:100%;"><span>{{ $img->caption }}</span></div>
        @endif
        <div style="position:absolute;bottom:0;left:0;right:0;padding:10px;background:linear-gradient(transparent,rgba(0,0,0,.7));">
            <form action="{{ route('admin.gallery.update', $img) }}" method="POST" style="display:flex;gap:6px;">
                @csrf @method('PUT')
                <input name="caption" value="{{ $img->caption }}" style="flex:1;background:rgba(23,18,14,.8);border:1px solid var(--line);border-radius:8px;padding:6px 10px;color:var(--cream);font-size:12px;font-family:var(--sans);">
                <button type="submit" style="width:34px;height:34px;border-radius:9px;background:rgba(23,18,14,.8);border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"><x-icon name="edit" :size="14"/></button>
            </form>
        </div>
        <div style="position:absolute;top:8px;right:8px;">
            <form action="{{ route('admin.gallery.destroy', $img) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                @csrf @method('DELETE')
                <button type="submit" style="width:34px;height:34px;border-radius:9px;background:rgba(23,18,14,.8);border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"><x-icon name="trash" :size="16"/></button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
