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
    <a href="{{ route('admin.menu.create') }}" class="btn btn-gold btn-sm" style="text-decoration:none;"><x-icon name="plus" :size="16"/> Add item</a>
</div>

@if(session('success'))
    <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--gold-700);color:var(--gold-400)">{{ session('success') }}</div>
@endif

<div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:18px;">
    <div style="display:flex;align-items:center;gap:9px;background:var(--ink-700);border:1px solid var(--line);border-radius:10px;padding:0 13px;min-width:220px;">
        <x-icon name="search" :size="16" color="var(--muted)"/>
        <input placeholder="Search dishes…" style="flex:1;background:none;border:none;outline:none;color:var(--cream);font-size:14px;padding:10px 0;font-family:var(--sans);">
    </div>
    <div style="display:flex;gap:6px;overflow-x:auto;">
        <button style="flex-shrink:0;background:var(--gold-600);color:#211405;border:1px solid var(--gold-600);border-radius:999px;padding:9px 15px;cursor:pointer;font-size:13px;font-weight:600;font-family:var(--sans);">All</button>
        @foreach($menu['categories'] as $c)
        <button style="flex-shrink:0;background:var(--ink-700);color:var(--cream-2);border:1px solid var(--line);border-radius:999px;padding:9px 15px;cursor:pointer;font-size:13px;font-weight:600;font-family:var(--sans);white-space:nowrap;">{{ $c['name'] }}</button>
        @endforeach
    </div>
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Dish</th><th>Category</th><th>Diet</th><th class="right">Price</th><th>POS ID</th><th>Available</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($menu['items'] as $it)
                @php $avail = !empty($it['available']); @endphp
                <tr>
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
                            <a href="{{ route('admin.menu.edit', $it['id']) }}" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;text-decoration:none;"><x-icon name="edit" :size="16"/></a>
                            <form action="{{ route('admin.menu.destroy', $it['id']) }}" method="POST" onsubmit="return confirm('Delete this item?')">
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
@endsection
