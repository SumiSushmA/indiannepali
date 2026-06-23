@extends('layouts.admin')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Gift card amounts</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Preset amounts shown on the gift card purchase page</p>
    </div>
</div>

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Add amount</h3>
    <form action="{{ route('admin.gift-amounts.store') }}" method="POST" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        @csrf
        <input name="amount" type="number" step="1" min="1" max="1000" placeholder="Amount ($)" required style="width:140px;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);">
            <input type="checkbox" name="is_active" value="1" checked> Active
        </label>
        <button type="submit" class="btn btn-gold btn-sm">Add amount</button>
    </form>
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Amount</th><th>Status</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($amounts as $amount)
                <tr>
                    <td>
                        <form action="{{ route('admin.gift-amounts.update', $amount) }}" method="POST" style="display:flex;gap:10px;align-items:center;">
                            @csrf @method('PUT')
                            <span style="font-family:var(--serif);font-size:22px;font-weight:600;">$</span>
                            <input name="amount" type="number" step="1" min="1" max="1000" value="{{ (int) $amount->amount }}" style="width:100px;background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:8px 12px;color:var(--cream);font-size:16px;font-family:var(--serif);font-weight:600;">
                            <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--cream-2);">
                                <input type="checkbox" name="is_active" value="1" @checked($amount->is_active)> Active
                            </label>
                            <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                        </form>
                    </td>
                    <td>@include('admin.partials.badge', ['tone' => $amount->is_active ? 'green' : 'neutral', 'dot' => true, 'label' => $amount->is_active ? 'Active' : 'Inactive'])</td>
                    <td class="right">
                        <form action="{{ route('admin.gift-amounts.destroy', $amount) }}" method="POST" data-confirm="Delete this amount?">
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
