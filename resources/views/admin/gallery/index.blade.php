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
    <div class="adm-gallery-item">
        <button
            type="button"
            class="adm-gallery-photo"
            data-gallery-replace
            data-action="{{ route('admin.gallery.update', $img) }}"
            data-caption="{{ $img->caption }}"
            data-src="{{ $img->image_path ? asset('storage/'.$img->image_path) : '' }}"
            aria-label="Change photo for {{ $img->caption }}"
        >
            @if($img->image_path)
                <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $img->caption }}">
            @else
                <div class="ph adm-gallery-photo__ph"><span>{{ $img->caption }}</span></div>
            @endif
            <span class="adm-gallery-photo__overlay"><x-icon name="image" :size="18"/> Change photo</span>
        </button>
        <div class="adm-gallery-item__footer">
            <form action="{{ route('admin.gallery.update', $img) }}" method="POST" style="display:flex;gap:6px;">
                @csrf @method('PUT')
                <input name="caption" value="{{ $img->caption }}" style="flex:1;background:rgba(23,18,14,.8);border:1px solid var(--line);border-radius:8px;padding:6px 10px;color:var(--cream);font-size:12px;font-family:var(--sans);">
                <button type="submit" style="width:34px;height:34px;border-radius:9px;background:rgba(23,18,14,.8);border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;" aria-label="Save caption"><x-icon name="edit" :size="14"/></button>
            </form>
        </div>
        <div class="adm-gallery-item__delete">
            <form action="{{ route('admin.gallery.destroy', $img) }}" method="POST" data-confirm="Delete this image?">
                @csrf @method('DELETE')
                <button type="submit" style="width:34px;height:34px;border-radius:9px;background:rgba(23,18,14,.8);border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;" aria-label="Delete image"><x-icon name="trash" :size="16"/></button>
            </form>
        </div>
    </div>
    @endforeach
</div>

@push('modals')
<dialog id="gallery-replace-dialog" class="adm-dialog" style="width:min(520px,calc(100vw - 28px));">
    <form id="gallery-replace-form" method="POST" enctype="multipart/form-data" style="padding:18px;display:grid;gap:14px;">
        @csrf
        @method('PUT')
        <input type="hidden" name="caption" id="gallery-replace-caption" value="">
        <h4 style="margin:0;font-size:19px;font-weight:600;">Replace photo</h4>
        <p id="gallery-replace-name" style="margin:0;font-size:13px;color:var(--muted);"></p>
        <div id="gallery-replace-preview" style="width:100%;aspect-ratio:1;border-radius:12px;overflow:hidden;background:var(--ink-800);border:1px solid var(--line);"></div>
        <div class="adm-file-field">
            <span class="adm-file-field__title">New image file</span>
            <span class="adm-file-field__hint">Choose a photo to replace the current one.</span>
            <input name="image" type="file" accept="image/*" required>
            <span data-adm-file-name style="font-size:12px;color:var(--muted);">No file chosen</span>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <button type="button" class="btn btn-ghost btn-sm" id="gallery-replace-cancel">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Replace photo</button>
        </div>
    </form>
</dialog>
@endpush
@endif
@endsection
