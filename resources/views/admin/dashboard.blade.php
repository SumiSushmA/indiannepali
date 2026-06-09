@extends('layouts.admin')

@php
$statusTone = ['New' => 'gold', 'Preparing' => 'blue'];
$maxRev = max($analytics['revenue7']);
$maxSold = $analytics['topItems'][0]['sold'];
$donutStops = [];
$offset = 0;
foreach ($analytics['channelSplit'] as $ch) {
    $donutStops[] = $ch['color'] . ' ' . $offset . '% ' . ($offset + $ch['value']) . '%';
    $offset += $ch['value'];
}
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;letter-spacing:-.01em;">Good afternoon, Suman</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Tuesday, June 2 · here's how the Kitchen is doing today.</p>
    </div>
    <div style="display:inline-flex;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
        <button style="border:none;background:var(--ink-600);color:var(--cream);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);">7 days</button>
        <button style="border:none;background:transparent;color:var(--muted);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);cursor:pointer;">Today</button>
        <button style="border:none;background:transparent;color:var(--muted);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);cursor:pointer;">30 days</button>
    </div>
</div>

<div class="adm-stat-grid">
    @foreach([
        ['Revenue (7d)', '$31,510', '+12.4%', true, 'dollar', 'gold'],
        ['Orders (7d)', '486', '+8.1%', true, 'bag', 'green'],
        ['Covers booked', '312', '+5.6%', true, 'cal', 'blue'],
        ['Avg. order value', '$48.20', '−2.0%', false, 'trend', 'red'],
    ] as $stat)
    <div class="adm-card" style="padding:22px;display:flex;flex-direction:column;gap:14px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div style="font-size:13px;color:var(--sand);font-weight:500;">{{ $stat[0] }}</div>
            <div style="width:38px;height:38px;border-radius:10px;background:rgba(200,133,47,.16);display:grid;place-items:center;color:var(--gold-400);">
                <x-icon :name="$stat[4]" :size="19"/>
            </div>
        </div>
        <div>
            <div style="font-family:var(--serif);font-size:32px;font-weight:600;line-height:1;color:var(--cream);">{{ $stat[1] }}</div>
            <div style="display:flex;align-items:center;gap:5px;margin-top:8px;font-size:12.5px;font-weight:600;color:{{ $stat[3] ? '#86b074' : '#d4795f' }};">
                <x-icon :name="$stat[3] ? 'up' : 'down'" :size="14"/> {{ $stat[2] }}
                <span style="color:var(--muted);font-weight:400;">vs last wk</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="adm-over-grid">
    <div class="adm-card" style="padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <div>
                <h3 style="font-size:19px;font-weight:600;">Revenue by day</h3>
                <div style="font-size:13px;color:var(--muted);margin-top:3px;">Last 7 days · all channels</div>
            </div>
            @include('admin.partials.badge', ['tone' => 'green', 'dot' => true, 'label' => 'Up 12.4%'])
        </div>
        <div class="adm-bar-chart">
            @foreach($analytics['revenue7'] as $i => $v)
            <div class="adm-bar-col">
                <div class="adm-bar-val">${{ number_format($v / 1000, 1) }}k</div>
                <div class="adm-bar" style="height:{{ round($v / $maxRev * 100) }}%;"></div>
                <div class="adm-bar-label">{{ $analytics['revenueDays'][$i] }}</div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="adm-card" style="padding:24px;">
        <h3 style="font-size:19px;font-weight:600;margin-bottom:18px;">Order channels</h3>
        <div class="adm-donut-wrap">
            <div class="adm-donut" style="background:conic-gradient({{ implode(', ', $donutStops) }});border-radius:50%;">
                <div class="adm-donut-center">
                    <div>
                        <div style="font-family:var(--serif);font-size:26px;font-weight:600;">100%</div>
                        <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;">Channels</div>
                    </div>
                </div>
            </div>
            <div class="adm-donut-legend">
                @foreach($analytics['channelSplit'] as $ch)
                <div style="display:flex;align-items:center;gap:10px;font-size:13.5px;">
                    <span style="width:11px;height:11px;border-radius:3px;background:{{ $ch['color'] }};flex-shrink:0;"></span>
                    <span style="color:var(--cream-2);flex:1;">{{ $ch['label'] }}</span>
                    <span style="font-weight:700;color:var(--cream);">{{ $ch['value'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="adm-over-grid-2">
    <div class="adm-card" style="padding:0;">
        <div style="padding:20px 24px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;">
            <h3 style="font-size:19px;font-weight:600;">Live orders</h3>
            <a href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : '#' }}" class="btn btn-ghost btn-sm" style="text-decoration:none;">
                View all <x-icon name="arrow" :size="15"/>
            </a>
        </div>
        <div style="padding:6px 12px;">
            @foreach(array_slice($liveOrders, 0, 5) as $o)
            @php $itemCount = array_sum(array_column($o['items'], 'qty')); @endphp
            <div style="display:flex;align-items:center;gap:12px;padding:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:var(--ink-800);display:grid;place-items:center;color:var(--gold-400);flex-shrink:0;">
                    <x-icon :name="$o['type'] === 'Delivery' ? 'truck' : 'bag'" :size="18"/>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:14.5px;">{{ $o['id'] }} · {{ $o['customer'] }}</div>
                    <div style="font-size:13px;color:var(--muted);">{{ $itemCount }} items · {{ $o['time'] }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:600;color:var(--gold-400);font-family:var(--serif);font-size:16px;">${{ $o['total'] }}</div>
                    @include('admin.partials.badge', ['tone' => $statusTone[$o['status']] ?? 'blue', 'dot' => true, 'label' => $o['status']])
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="adm-card" style="padding:0;">
        <div style="padding:20px 24px;border-bottom:1px solid var(--line);">
            <h3 style="font-size:19px;font-weight:600;">Top dishes this week</h3>
        </div>
        <div style="padding:12px 24px;">
            @foreach($analytics['topItems'] as $i => $it)
            <div style="padding:11px 0;border-bottom:{{ $i < 4 ? '1px solid var(--line-soft)' : 'none' }};">
                <div style="display:flex;justify-content:space-between;margin-bottom:7px;font-size:14px;">
                    <span style="font-weight:600;"><span style="color:var(--faint);margin-right:8px;">0{{ $i + 1 }}</span>{{ $it['name'] }}</span>
                    <span style="color:var(--sand);">{{ $it['sold'] }} sold · ${{ number_format($it['rev']) }}</span>
                </div>
                <div style="height:6px;background:var(--ink-800);border-radius:99px;">
                    <div style="width:{{ round($it['sold'] / $maxSold * 100) }}%;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--gold-700),var(--gold-500));"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="adm-task-grid">
    @foreach([
        ['cal', 'gold', '5 reservations', 'need confirmation for tonight', 'admin.reservations.index'],
        ['box', 'purple', '3 catering inquiries', 'awaiting a quote', 'admin.catering.index'],
        ['mail', 'red', '4 unread messages', 'in the contact inbox', 'admin.inquiries.index'],
    ] as $task)
    <a href="{{ Route::has($task[4]) ? route($task[4]) : '#' }}" class="adm-card" style="padding:22px;cursor:pointer;display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;">
        <div style="width:46px;height:46px;border-radius:12px;background:rgba(200,133,47,.16);display:grid;place-items:center;color:var(--gold-400);flex-shrink:0;">
            <x-icon :name="$task[0]" :size="22"/>
        </div>
        <div style="flex:1;">
            <div style="font-weight:600;font-size:16px;">{{ $task[2] }}</div>
            <div style="font-size:13.5px;color:var(--muted);">{{ $task[3] }}</div>
        </div>
        <x-icon name="arrow" :size="18" color="var(--muted)"/>
    </a>
    @endforeach
</div>
@endsection
