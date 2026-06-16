@extends('layouts.admin')

@section('content')
<div style="max-width:640px;">
    <div style="margin-bottom:26px;">
        <a href="{{ route('admin.menu.index') }}" style="font-size:13px;color:var(--muted);text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:12px;">
            <x-icon name="arrowL" :size="14"/> Back to menu
        </a>
        <h1 style="font-size:30px;font-weight:600;">Edit {{ $item->name }}</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Slug: {{ $item->slug }}</p>
    </div>

    @if($errors->any())
        <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--spice-600);color:var(--spice-400)">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <form action="{{ route('admin.menu.update', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="adm-card" style="padding:22px;display:grid;gap:16px;">
            <label style="display:flex;flex-direction:column;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Category</span>
                <select name="menu_category_id" required class="adm-inp">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('menu_category_id', $item->menu_category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </label>
            <label style="display:flex;flex-direction:column;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Name</span>
                <input name="name" value="{{ old('name', $item->name) }}" required class="adm-inp">
            </label>
            <label style="display:flex;flex-direction:column;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Price ($)</span>
                <input name="price" type="number" step="0.01" min="0" value="{{ old('price', $item->price) }}" required class="adm-inp">
            </label>
            <label style="display:flex;flex-direction:column;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Description</span>
                <textarea name="description" rows="3" class="adm-inp">{{ old('description', $item->description) }}</textarea>
            </label>
            <label style="display:flex;flex-direction:column;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Spice level (0–5)</span>
                <input name="spice_level" type="number" min="0" max="5" value="{{ old('spice_level', $item->spice_level) }}" class="adm-inp">
            </label>
            <label style="display:flex;flex-direction:column;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Image @if($item->image_path)<span style="color:var(--muted);font-weight:400;">(leave blank to keep current)</span>@endif</span>
                <input name="image" type="file" accept="image/*" style="color:var(--sand);font-size:14px;">
            </label>
            <div style="display:flex;gap:20px;">
                <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);cursor:pointer;">
                    <input type="checkbox" name="is_veg" value="1" @checked(old('is_veg', $item->is_veg))> Vegetarian
                </label>
                <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);cursor:pointer;">
                    <input type="checkbox" name="is_popular" value="1" @checked(old('is_popular', $item->is_popular))> Popular
                </label>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-gold btn-sm">Save changes</button>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-ghost btn-sm" style="text-decoration:none;">Cancel</a>
            </div>
        </div>
    </form>

    <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" style="margin-top:16px;" data-confirm="Delete this menu item?">
        @csrf @method('DELETE')
        <button type="submit" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;" aria-label="Delete menu item"><x-icon name="trash" :size="16"/></button>
    </form>
</div>
@endsection
