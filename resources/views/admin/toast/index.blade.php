@extends('layouts.admin')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Toast POS integration</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $toast['location'] }}</p>
    </div>
    <form action="{{ route('admin.toast.sync') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-ghost btn-sm"><x-icon name="refresh" :size="16"/> Sync now</button>
    </form>
</div>

@if(session('success'))
    <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--gold-700);color:var(--gold-400)">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--spice-600);color:var(--spice-400)">{{ session('error') }}</div>
@endif

<div class="adm-card" style="display:flex;align-items:center;gap:18px;margin-bottom:18px;padding:22px;border-color:{{ ($isLive ?? false) ? 'var(--leaf-600)' : 'var(--line)' }};background:linear-gradient(90deg,{{ ($isLive ?? false) ? 'rgba(79,125,68,.1)' : 'rgba(200,133,47,.08)' }},var(--ink-700));">
    <div style="width:52px;height:52px;border-radius:13px;background:{{ ($isLive ?? false) ? 'rgba(79,125,68,.18)' : 'rgba(200,133,47,.16)' }};border:1px solid {{ ($isLive ?? false) ? 'var(--leaf-600)' : 'var(--gold-700)' }};display:grid;place-items:center;color:{{ ($isLive ?? false) ? '#86b074' : 'var(--gold-400)' }};flex-shrink:0;">
        <x-icon name="link" :size="24"/>
    </div>
    <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:10px;">
            <h3 style="font-size:19px;font-weight:600;">{{ ($isLive ?? false) ? 'Toast connected (live payments)' : 'Demo mode (mock payments)' }}</h3>
            @include('admin.partials.badge', ['tone' => ($isLive ?? false) ? 'green' : 'gold', 'dot' => true, 'label' => ($isLive ?? false) ? 'Live' : 'Mock'])
        </div>
        <div style="font-size:13.5px;color:var(--muted);margin-top:3px;">{{ $paymentLabel ?? '' }} · Last sync {{ $toast['lastSync'] }}</div>
    </div>
</div>

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Toast credentials in .env</h3>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:10px;">
        @foreach($credentials ?? [] as $key => $set)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;border-radius:8px;background:var(--ink-800);border:1px solid var(--line);font-size:13px;">
            <span style="color:var(--sand)">{{ str_replace('_', ' ', $key) }}</span>
            @include('admin.partials.badge', ['tone' => $set ? 'green' : 'neutral', 'dot' => $set, 'label' => $set ? 'Set' : 'Missing'])
        </div>
        @endforeach
    </div>
    <p style="font-size:13px;color:var(--muted);margin-top:14px;line-height:1.6">Add the four required keys (<code style="color:var(--gold-400)">TOAST_CLIENT_ID</code>, <code style="color:var(--gold-400)">TOAST_CLIENT_SECRET</code>, <code style="color:var(--gold-400)">TOAST_RESTAURANT_GUID</code>, <code style="color:var(--gold-400)">TOAST_MERCHANT_UUID</code>) to switch to live Toast payments automatically. No code changes needed.</p>
</div>

<div class="adm-stat-grid">
    @foreach([
        ['Items synced', '64', 'fork'],
        ['Orders pushed', '1,284', 'bag'],
        ['Avg. sync time', '0.8s', 'refresh'],
        ['Conflicts (24h)', '0', 'check'],
    ] as $s)
    <div class="adm-card" style="padding:22px;">
        <div style="display:flex;justify-content:space-between;">
            <span style="font-size:13px;color:var(--sand);">{{ $s[0] }}</span>
            <div style="width:34px;height:34px;border-radius:9px;background:rgba(200,133,47,.16);display:grid;place-items:center;color:var(--gold-400);">
                <x-icon :name="$s[2]" :size="17"/>
            </div>
        </div>
        <div style="font-family:var(--serif);font-size:30px;font-weight:600;margin-top:12px;">{{ $s[1] }}</div>
    </div>
    @endforeach
</div>

<div class="adm-toast-grid">
    <div class="adm-card" style="padding:0;">
        <div style="padding:18px 22px;border-bottom:1px solid var(--line);">
            <h3 style="font-size:18px;font-weight:600;">Sync status by data type</h3>
        </div>
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Data</th><th>Direction</th><th class="right">Records</th><th>Status</th><th>Last</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($toast['syncs'] as $s)
                    <tr>
                        <td><span style="font-weight:600;color:var(--cream);">{{ $s['type'] }}</span></td>
                        <td><span style="font-family:ui-monospace,monospace;font-size:12.5px;color:var(--sand);">{{ $s['dir'] }}</span></td>
                        <td class="right">{{ number_format($s['count']) }}</td>
                        <td>@include('admin.partials.badge', ['tone' => $s['status'] === 'Synced' ? 'green' : 'gold', 'dot' => true, 'label' => $s['status']])</td>
                        <td><span style="font-size:13px;color:var(--muted);">{{ $s['time'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="adm-card" style="padding:0;">
        <div style="padding:18px 22px;border-bottom:1px solid var(--line);">
            <h3 style="font-size:18px;font-weight:600;">Activity log</h3>
        </div>
        <div style="padding:18px;">
            @foreach($toast['log'] as $i => $l)
            <div style="display:flex;gap:12px;padding-bottom:16px;">
                <div style="display:flex;flex-direction:column;align-items:center;">
                    <span style="width:9px;height:9px;border-radius:99px;background:{{ $l['ok'] ? 'var(--leaf-500)' : 'var(--gold-500)' }};margin-top:4px;flex-shrink:0;"></span>
                    @if($i < count($toast['log']) - 1)
                    <span style="width:1px;flex:1;background:var(--line);margin-top:4px;min-height:24px;"></span>
                    @endif
                </div>
                <div>
                    <div style="font-size:13.5px;color:var(--cream-2);line-height:1.5;">{{ $l['m'] }}</div>
                    <div style="font-size:12px;color:var(--faint);margin-top:2px;">{{ $l['t'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
