@extends('layouts.admin')

@php
$gtone = ['Active' => 'green', 'Partially used' => 'gold', 'Redeemed' => 'neutral'];
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Gift cards</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $giftStats['active'] }} active cards · {{ $giftStats['outstanding'] }} outstanding balance</p>
    </div>
</div>

@if(session('success'))
    <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--gold-700);color:var(--gold-400)">{{ session('success') }}</div>
@endif

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Issue card manually</h3>
    <form action="{{ route('admin.gift-cards.store') }}" method="POST" style="display:grid;gap:12px;">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
            <select name="gift_card_design_id" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                @foreach($designs as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
            <input name="face_value" type="number" step="1" min="10" max="1000" placeholder="Face value ($)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="recipient_name" placeholder="Recipient name" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <input name="sender_name" placeholder="Sender name (optional)" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="message" placeholder="Message (optional)" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </div>
        <button type="submit" class="btn btn-gold btn-sm" style="justify-self:start;"><x-icon name="plus" :size="16"/> Issue card</button>
    </form>
</div>

<div class="adm-stat-grid">
    @foreach([
        ['Sold (30d)', $giftStats['sold'], '+18%', true, 'tag'],
        ['Outstanding balance', $giftStats['outstanding'], null, null, 'dollar'],
        ['Active cards', $giftStats['active'], null, null, 'box'],
        ['Redeemed (30d)', $giftStats['redeemed30'], '+9%', true, 'check'],
    ] as $stat)
    <div class="adm-card" style="padding:22px;">
        <div style="display:flex;justify-content:space-between;">
            <span style="font-size:13px;color:var(--sand);">{{ $stat[0] }}</span>
            <div style="width:34px;height:34px;border-radius:9px;background:rgba(200,133,47,.16);display:grid;place-items:center;color:var(--gold-400);">
                <x-icon :name="$stat[4]" :size="17"/>
            </div>
        </div>
        <div style="font-family:var(--serif);font-size:30px;font-weight:600;margin-top:12px;">{{ $stat[1] }}</div>
        @if($stat[2])
        <div style="display:flex;align-items:center;gap:5px;margin-top:8px;font-size:12.5px;font-weight:600;color:#86b074;">
            <x-icon name="up" :size="14"/> {{ $stat[2] }} <span style="color:var(--muted);font-weight:400;">vs last wk</span>
        </div>
        @endif
    </div>
    @endforeach
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Code</th><th>Design</th><th>Recipient</th><th>Channel</th>
                    <th class="right">Face value</th><th class="right">Balance</th><th>Status</th><th>Issued</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($giftCards as $g)
                <tr>
                    <td><span style="font-family:ui-monospace,monospace;font-size:13px;font-weight:600;color:var(--cream);">{{ $g['code'] }}</span></td>
                    <td><span style="font-size:13.5px;color:var(--sand);">{{ $g['design'] }}</span></td>
                    <td>{{ $g['recipient'] }}</td>
                    <td>@include('admin.partials.badge', ['tone' => 'neutral', 'label' => $g['channel']])</td>
                    <td class="right">${{ $g['face'] }}</td>
                    <td class="right"><span style="font-weight:600;font-family:var(--serif);font-size:16px;color:{{ $g['balance'] > 0 ? 'var(--gold-400)' : 'var(--faint)' }};">${{ $g['balance'] }}</span></td>
                    <td>@include('admin.partials.badge', ['tone' => $gtone[$g['status']] ?? 'neutral', 'dot' => true, 'label' => $g['status']])</td>
                    <td><span style="font-size:13px;color:var(--muted);">{{ substr($g['issued'], 5) }}</span></td>
                    <td class="right">
                        <form action="{{ route('admin.gift-cards.update', $g['id']) }}" method="POST" style="display:flex;gap:6px;justify-content:flex-end;align-items:center;">
                            @csrf @method('PATCH')
                            <input name="balance" type="number" step="1" min="0" value="{{ $g['balance'] }}" style="width:70px;background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:6px 10px;color:var(--cream);font-size:13px;text-align:right;">
                            <select name="status" style="background:var(--ink-800);border:1px solid var(--line);border-radius:8px;padding:6px 8px;color:var(--cream);font-size:12px;font-family:var(--sans);">
                                <option value="Active" @selected($g['status'] === 'Active')>Active</option>
                                <option value="Partially used" @selected($g['status'] === 'Partially used')>Partially used</option>
                                <option value="Redeemed" @selected($g['status'] === 'Redeemed')>Redeemed</option>
                            </select>
                            <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
