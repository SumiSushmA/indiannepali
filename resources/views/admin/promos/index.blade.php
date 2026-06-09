@extends('layouts.admin')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Promos & offers</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $promos->count() }} offers · shown on /offers</p>
    </div>
</div>

@if(session('success'))
    <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--gold-700);color:var(--gold-400)">{{ session('success') }}</div>
@endif

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Add promo</h3>
    <form action="{{ route('admin.promos.store') }}" method="POST" style="display:grid;gap:12px;">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <input name="badge" placeholder="Badge (e.g. Lunch special)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="price_label" placeholder="Price label (e.g. $12.99)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </div>
        <input name="title" placeholder="Title" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        <textarea name="detail" placeholder="Detail description" required rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;"></textarea>
        <div style="display:flex;gap:12px;align-items:center;">
            <select name="accent" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 14px;color:var(--cream);font-family:var(--sans);">
                <option value="gold">Gold accent</option>
                <option value="spice">Spice accent</option>
                <option value="leaf">Leaf accent</option>
            </select>
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);">
                <input type="checkbox" name="is_active" value="1" checked> Active
            </label>
            <button type="submit" class="btn btn-gold btn-sm" style="margin-left:auto;">Add promo</button>
        </div>
    </form>
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Badge</th><th>Title</th><th>Price</th><th>Status</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($promos as $promo)
                <tr>
                    <td>@include('admin.partials.badge', ['tone' => $promo->accent === 'gold' ? 'gold' : 'neutral', 'label' => $promo->badge])</td>
                    <td>
                        <form action="{{ route('admin.promos.update', $promo) }}" method="POST" style="display:grid;gap:8px;">
                            @csrf @method('PUT')
                            <input name="title" value="{{ $promo->title }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:14px;font-family:var(--sans);">
                            <textarea name="detail" rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:13px;font-family:var(--sans);resize:vertical;">{{ $promo->detail }}</textarea>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                <input name="badge" value="{{ $promo->badge }}" style="flex:1;min-width:100px;background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:13px;font-family:var(--sans);">
                                <input name="price_label" value="{{ $promo->price_label }}" style="width:100px;background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:13px;font-family:var(--sans);">
                                <select name="accent" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:13px;font-family:var(--sans);">
                                    <option value="gold" @selected($promo->accent === 'gold')>Gold</option>
                                    <option value="spice" @selected($promo->accent === 'spice')>Spice</option>
                                    <option value="leaf" @selected($promo->accent === 'leaf')>Leaf</option>
                                </select>
                                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--cream-2);">
                                    <input type="checkbox" name="is_active" value="1" @checked($promo->is_active)> Active
                                </label>
                                <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                            </div>
                        </form>
                    </td>
                    <td><span style="font-weight:600;font-family:var(--serif);">{{ $promo->price_label }}</span></td>
                    <td>@include('admin.partials.badge', ['tone' => $promo->is_active ? 'green' : 'neutral', 'dot' => true, 'label' => $promo->is_active ? 'Active' : 'Inactive'])</td>
                    <td class="right">
                        <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" onsubmit="return confirm('Delete this promo?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"><x-icon name="trash" :size="16"/></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
