@extends('layouts.admin')

@php
$catMap = collect($menu['categories'])->keyBy('id');
$available = count(array_filter($menu['items'], fn($it) => !empty($it['available'])));
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Menu</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ count($menu['items']) }} items · {{ $available }} available · synced with Toast POS</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('add-menu-category-dialog')?.showModal()"><x-icon name="plus" :size="16"/> Add category</button>
        <button type="button" class="btn btn-gold btn-sm" onclick="document.getElementById('add-menu-item-dialog')?.showModal()"><x-icon name="plus" :size="16"/> Add item</button>
    </div>
</div>

<div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:18px;">
    <div style="display:flex;gap:6px;overflow-x:auto;">
        <button type="button" data-adm-menu-cat="all" style="flex-shrink:0;background:var(--gold-600);color:#211405;border:1px solid var(--gold-600);border-radius:999px;padding:9px 15px;cursor:pointer;font-size:13px;font-weight:600;font-family:var(--sans);">All</button>
        @foreach($menu['categories'] as $c)
        <button type="button" data-adm-menu-cat="{{ $c['id'] }}" style="flex-shrink:0;background:var(--ink-700);color:var(--cream-2);border:1px solid var(--line);border-radius:999px;padding:9px 15px;cursor:pointer;font-size:13px;font-weight:600;font-family:var(--sans);white-space:nowrap;">{{ $c['name'] }}</button>
        @endforeach
    </div>
</div>

<div class="adm-card" style="padding:20px;margin-bottom:16px;">
    <h3 style="font-size:18px;font-weight:600;margin-bottom:12px;">Menu categories</h3>

    <div style="display:grid;gap:8px;">
        @foreach($categories as $category)
            <form action="{{ route('admin.menu.categories.update', $category) }}" method="POST" style="display:grid;grid-template-columns:1.2fr .9fr 1.6fr auto auto auto;gap:10px;align-items:center;">
                @csrf
                @method('PATCH')
                <input name="name" value="{{ $category->name }}" required class="adm-inp" style="min-height:40px;padding:8px 12px;">
                <input name="tag" value="{{ $category->tag }}" class="adm-inp" style="min-height:40px;padding:8px 12px;">
                <input name="description" value="{{ $category->description }}" class="adm-inp" style="min-height:40px;padding:8px 12px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--sand);"><input type="checkbox" name="is_active" value="1" @checked($category->is_active)> Active</label>
                <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                <button type="submit"
                        form="delete-menu-category-{{ $category->id }}"
                        style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;"
                        aria-label="Delete category">
                    <x-icon name="trash" :size="16"/>
                </button>
            </form>
            <form id="delete-menu-category-{{ $category->id }}" action="{{ route('admin.menu.categories.destroy', $category) }}" method="POST" data-confirm="Delete this category?">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>
</div>

<dialog id="add-menu-category-dialog" style="width:min(560px,calc(100vw - 28px));border:1px solid var(--line);border-radius:14px;background:var(--ink-700);color:var(--cream);padding:0;box-shadow:var(--shadow-3);">
    <form action="{{ route('admin.menu.categories.store') }}" method="POST" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
            <h4 style="margin:0;font-size:19px;font-weight:600;">Add menu category</h4>
        </div>
        <input name="name" placeholder="Category name (e.g. Appetizers)" required class="adm-inp">
        <input name="tag" placeholder="Tag (e.g. Starters)" class="adm-inp">
        <textarea name="description" placeholder="Short description" class="adm-inp" rows="3"></textarea>
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--sand);"><input type="checkbox" name="is_active" value="1" checked> Active</label>
        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('add-menu-category-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Save category</button>
        </div>
    </form>
</dialog>

<dialog id="add-menu-item-dialog" style="width:min(760px,calc(100vw - 28px));border:1px solid var(--line);border-radius:14px;background:var(--ink-700);color:var(--cream);padding:0;box-shadow:var(--shadow-3);">
    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
            <h4 style="margin:0;font-size:19px;font-weight:600;">Add menu item</h4>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Category</span>
                <select name="menu_category_id" required class="adm-inp">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('menu_category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Name</span>
                <input name="name" value="{{ old('name') }}" required class="adm-inp">
            </label>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Price ($)</span>
                <input name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" required class="adm-inp">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Spice level (0–5)</span>
                <input name="spice_level" type="number" min="0" max="5" value="{{ old('spice_level', 0) }}" class="adm-inp">
            </label>
        </div>

        <label style="display:grid;gap:6px;">
            <span style="font-size:13px;color:var(--sand);font-weight:600;">Description</span>
            <textarea name="description" rows="3" class="adm-inp">{{ old('description') }}</textarea>
        </label>

        <label style="display:grid;gap:6px;">
            <span style="font-size:13px;color:var(--sand);font-weight:600;">Image</span>
            <input name="image" type="file" accept="image/*" style="color:var(--sand);font-size:14px;">
        </label>

        <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);cursor:pointer;">
                <input type="checkbox" name="is_veg" value="1" @checked(old('is_veg'))> Vegetarian
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);cursor:pointer;">
                <input type="checkbox" name="is_popular" value="1" @checked(old('is_popular'))> Popular
            </label>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('add-menu-item-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Create item</button>
        </div>
    </form>
</dialog>

<dialog id="edit-menu-item-dialog" style="width:min(760px,calc(100vw - 28px));border:1px solid var(--line);border-radius:14px;background:var(--ink-700);color:var(--cream);padding:0;box-shadow:var(--shadow-3);">
    <form id="edit-menu-item-form" method="POST" enctype="multipart/form-data" style="padding:18px;display:grid;gap:12px;">
        @csrf
        @method('PUT')
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
            <h4 style="margin:0;font-size:19px;font-weight:600;">Edit menu item</h4>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Category</span>
                <select id="edit-menu-category" name="menu_category_id" required class="adm-inp">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Name</span>
                <input id="edit-menu-name" name="name" required class="adm-inp">
            </label>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Price ($)</span>
                <input id="edit-menu-price" name="price" type="number" step="0.01" min="0" required class="adm-inp">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Spice level (0–5)</span>
                <input id="edit-menu-spice" name="spice_level" type="number" min="0" max="5" class="adm-inp">
            </label>
        </div>

        <label style="display:grid;gap:6px;">
            <span style="font-size:13px;color:var(--sand);font-weight:600;">Description</span>
            <textarea id="edit-menu-description" name="description" rows="3" class="adm-inp"></textarea>
        </label>

        <label style="display:grid;gap:6px;">
            <span style="font-size:13px;color:var(--sand);font-weight:600;">Image <span style="color:var(--muted);font-weight:400;">(leave blank to keep current)</span></span>
            <input name="image" type="file" accept="image/*" style="color:var(--sand);font-size:14px;">
        </label>

        <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);cursor:pointer;">
                <input id="edit-menu-veg" type="checkbox" name="is_veg" value="1"> Vegetarian
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);cursor:pointer;">
                <input id="edit-menu-popular" type="checkbox" name="is_popular" value="1"> Popular
            </label>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('edit-menu-item-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Save changes</button>
        </div>
    </form>
</dialog>

@if($errors->any() && old('name'))
    @push('scripts')
    <script>
        document.getElementById('add-menu-item-dialog')?.showModal();
    </script>
    @endpush
@endif

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table" id="adm-menu-table" data-adm-menu-table>
            <thead>
                <tr>
                    <th>Dish</th><th>Category</th><th>Diet</th><th class="right">Price</th><th>POS ID</th><th>Available</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($menu['items'] as $it)
                @php $avail = !empty($it['available']); @endphp
                <tr data-adm-row data-adm-cat="{{ $it['cat'] }}" data-adm-search-text="{{ strtolower($it['name']) }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="ph" style="width:42px;height:42px;flex-shrink:0;border-radius:8px;"><span>{{ $it['img'] }}</span></div>
                            <div>
                                <div style="font-weight:600;color:var(--cream);">{{ $it['name'] }}</div>
                                @if(!empty($it['popular']))<span style="font-size:11px;color:var(--gold-500);font-weight:600;">★ Popular</span>@endif
                            </div>
                        </div>
                    </td>
                    <td><span style="font-size:13px;color:var(--sand);">{{ $catMap[$it['cat']]['name'] ?? $it['cat'] }}</span></td>
                    <td>
                        @if($it['veg'])
                            @include('admin.partials.badge', ['tone' => 'green', 'label' => 'Veg'])
                        @else
                            @include('admin.partials.badge', ['tone' => 'neutral', 'label' => 'Non-veg'])
                        @endif
                    </td>
                    <td class="right"><span style="font-weight:600;font-family:var(--serif);font-size:16px;">${{ $it['price'] }}</span></td>
                    <td><span style="font-family:ui-monospace,monospace;font-size:12.5px;color:var(--muted);">{{ $it['pos_id'] ?? 'TST-'.strtoupper($it['id']).'0'.$it['price'] }}</span></td>
                    <td>
                        <form action="{{ route('admin.menu.availability', $it['id']) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="adm-toggle {{ $avail ? 'on' : 'off' }}" role="switch" aria-checked="{{ $avail ? 'true' : 'false' }}">
                                <span class="adm-toggle-knob"></span>
                            </button>
                        </form>
                    </td>
                    <td class="right">
                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                            <button type="button"
                                    style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"
                                    data-edit-id="{{ $it['id'] }}"
                                    data-edit-category="{{ $it['menu_category_id'] }}"
                                    data-edit-name="{{ $it['name'] }}"
                                    data-edit-price="{{ $it['price'] }}"
                                    data-edit-description="{{ $it['description'] ?? '' }}"
                                    data-edit-spice="{{ $it['spice_level'] ?? 0 }}"
                                    data-edit-veg="{{ !empty($it['is_veg']) ? '1' : '0' }}"
                                    data-edit-popular="{{ !empty($it['is_popular']) ? '1' : '0' }}"
                                    onclick="openMenuItemEdit(this)">
                                <x-icon name="edit" :size="16"/>
                            </button>
                            <form action="{{ route('admin.menu.destroy', $it['id']) }}" method="POST" data-confirm="Delete this item?">
                                @csrf @method('DELETE')
                                <button type="submit" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"><x-icon name="trash" :size="16"/></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function openMenuItemEdit(btn) {
    const id = btn.getAttribute('data-edit-id');
    const template = @json(route('admin.menu.update', ['menuItem' => '__ITEM__']));
    const form = document.getElementById('edit-menu-item-form');
    if (!id || !form) return;

    form.action = template.replace('__ITEM__', encodeURIComponent(id));
    document.getElementById('edit-menu-category').value = btn.getAttribute('data-edit-category') || '';
    document.getElementById('edit-menu-name').value = btn.getAttribute('data-edit-name') || '';
    document.getElementById('edit-menu-price').value = btn.getAttribute('data-edit-price') || '';
    document.getElementById('edit-menu-description').value = btn.getAttribute('data-edit-description') || '';
    document.getElementById('edit-menu-spice').value = btn.getAttribute('data-edit-spice') || 0;
    document.getElementById('edit-menu-veg').checked = btn.getAttribute('data-edit-veg') === '1';
    document.getElementById('edit-menu-popular').checked = btn.getAttribute('data-edit-popular') === '1';

    document.getElementById('edit-menu-item-dialog')?.showModal();
}
</script>
@endpush
@endsection
