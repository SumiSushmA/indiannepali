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
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <button type="button" class="btn btn-gold btn-sm" onclick="document.getElementById('gift-issue-dialog')?.showModal()"><x-icon name="plus" :size="16"/> Issue card</button>
        <button type="button" class="btn btn-gold btn-sm" onclick="document.getElementById('gift-add-design-dialog')?.showModal()"><x-icon name="plus" :size="16"/> Add design</button>
    </div>
</div>

<div class="adm-stat-grid" style="margin-bottom:18px;">
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

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Card design colors</h3>
    <p style="color:var(--muted);font-size:13px;margin-bottom:14px;">Update existing designs. Active designs appear on the customer gift cards page.</p>

    <div style="display:grid;gap:8px;">
        @foreach($designs as $design)
            @php($palette = $design->palette())
            <form action="{{ route('admin.gift-cards.designs.update', $design) }}" method="POST" style="display:grid;grid-template-columns:48px 1.2fr 1fr .75fr .75fr .75fr .75fr auto auto auto;gap:10px;align-items:center;">
                @csrf @method('PATCH')
                <div style="width:48px;height:38px;border-radius:8px;border:1px solid var(--line);background:linear-gradient(125deg, {{ $palette['start'] }} 0%, {{ $palette['mid'] }} 48%, {{ $palette['end'] }} 100%);"></div>
                <input name="name" value="{{ $design->name }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:9px 10px;color:var(--cream);font-family:var(--sans);">
                <input name="subtitle" value="{{ $design->subtitle }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:9px 10px;color:var(--cream);font-family:var(--sans);">
                <input name="bg_start" type="color" value="{{ $palette['start'] }}" style="width:100%;height:36px;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
                <input name="bg_mid" type="color" value="{{ $palette['mid'] }}" style="width:100%;height:36px;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
                <input name="bg_end" type="color" value="{{ $palette['end'] }}" style="width:100%;height:36px;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
                <input name="text_color" type="color" value="{{ $palette['text'] }}" style="width:100%;height:36px;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--sand);"><input type="checkbox" name="is_active" value="1" @checked($design->is_active)> Active</label>
                <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                <button type="submit"
                        form="delete-design-{{ $design->id }}"
                        class="btn btn-ghost btn-sm"
                        style="color:var(--spice-400);border-color:var(--spice-600);">
                    Delete
                </button>
            </form>
            <form id="delete-design-{{ $design->id }}"
                  action="{{ route('admin.gift-cards.designs.destroy', $design) }}"
                  method="POST"
                  data-confirm="Delete this gift card design? This cannot be undone."
                  style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>
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

@push('modals')
<dialog id="gift-issue-dialog" class="adm-dialog" style="width:min(720px,calc(100vw - 28px));">
    <form action="{{ route('admin.gift-cards.store') }}" method="POST" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <h4 style="margin:0;font-size:19px;font-weight:600;">Issue card</h4>
        <p style="margin:0;font-size:13px;color:var(--muted);">Create a gift card manually for a guest.</p>
        <label style="display:grid;gap:6px;">
            <span style="font-size:13px;color:var(--sand);font-weight:600;">Design</span>
            <select name="gift_card_design_id" required class="adm-inp">
                @foreach($activeDesigns as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Face value ($)</span>
                <input name="face_value" type="number" step="1" min="10" max="1000" placeholder="e.g. 50" required class="adm-inp">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Recipient name</span>
                <input name="recipient_name" placeholder="Recipient name" required class="adm-inp">
            </label>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Sender name</span>
                <input name="sender_name" placeholder="Optional" class="adm-inp">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Message</span>
                <input name="message" placeholder="Optional" class="adm-inp">
            </label>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('gift-issue-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Issue card</button>
        </div>
    </form>
</dialog>

<dialog id="gift-add-design-dialog" class="adm-dialog" style="width:min(760px,calc(100vw - 28px));">
    <form action="{{ route('admin.gift-cards.designs.store') }}" method="POST" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <h4 style="margin:0;font-size:19px;font-weight:600;">Add design</h4>
        <p style="margin:0;font-size:13px;color:var(--muted);">Create a new gift card color theme for the customer page.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Design name</span>
                <input name="name" placeholder="Design name" required class="adm-inp">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Subtitle</span>
                <input name="subtitle" placeholder="Subtitle" class="adm-inp">
            </label>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:12px;color:var(--sand);">Start color</span>
                <input name="bg_start" type="color" value="#c9922a" class="adm-inp" style="height:44px;padding:4px;">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:12px;color:var(--sand);">Mid color</span>
                <input name="bg_mid" type="color" value="#e8c56a" class="adm-inp" style="height:44px;padding:4px;">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:12px;color:var(--sand);">End color</span>
                <input name="bg_end" type="color" value="#f8e8b8" class="adm-inp" style="height:44px;padding:4px;">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:12px;color:var(--sand);">Text color</span>
                <input name="text_color" type="color" value="#3a2810" class="adm-inp" style="height:44px;padding:4px;">
            </label>
        </div>
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--sand);"><input type="checkbox" name="is_active" value="1" checked> Active</label>
        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('gift-add-design-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Add design</button>
        </div>
    </form>
</dialog>
@endpush
@endsection
