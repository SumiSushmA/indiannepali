@extends('layouts.admin')

@php
$statusTone = ['New' => 'gold', 'Preparing' => 'blue'];
$maxRev = max(1, max($analytics['revenue7'] ?: [0]));
$maxSold = max(1, ($analytics['topItems'][0]['sold'] ?? 0));
$donutStops = [];
$offset = 0;
foreach ($analytics['channelSplit'] as $ch) {
    $donutStops[] = $ch['color'] . ' ' . $offset . '% ' . ($offset + $ch['value']) . '%';
    $offset += $ch['value'];
}
$rangeOptions = ['7' => '7 days', 'today' => 'Today', '30' => '30 days'];
$revChange = $analytics['revenueChange'];
$revUp = $analytics['revenueUp'];
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;letter-spacing:-.01em;">{{ $greeting }}, {{ $firstName }}</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ now()->format('l, F j') }} · here's how the Kitchen is doing.</p>
    </div>
    <div style="display:inline-flex;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
        @foreach($rangeOptions as $key => $label)
        <a href="{{ route('admin.dashboard', ['range' => $key]) }}"
           style="border:none;background:{{ $range === $key ? 'var(--ink-600)' : 'transparent' }};color:{{ $range === $key ? 'var(--cream)' : 'var(--muted)' }};padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);cursor:pointer;text-decoration:none;display:inline-block;">
            {{ $label }}
        </a>
        @endforeach
    </div>
</div>

<div class="adm-stat-grid">
    @foreach($dashboardStats as $stat)
    <div class="adm-card" style="padding:22px;display:flex;flex-direction:column;gap:14px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div style="font-size:13px;color:var(--sand);font-weight:500;">{{ $stat[0] }}</div>
            <div style="width:38px;height:38px;border-radius:10px;background:rgba(200,133,47,.16);display:grid;place-items:center;color:var(--gold-400);">
                <x-icon :name="$stat[3]" :size="19"/>
            </div>
        </div>
        <div>
            <div style="font-family:var(--serif);font-size:32px;font-weight:600;line-height:1;color:var(--cream);">{{ $stat[1] }}</div>
            <div style="display:flex;align-items:center;gap:5px;margin-top:8px;font-size:12.5px;color:var(--muted);">
                {{ $stat[2] }}
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="adm-over-grid">
    <div class="adm-card" style="padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <div>
                <h3 style="font-size:19px;font-weight:600;">{{ $analytics['chartTitle'] }}</h3>
                <div style="font-size:13px;color:var(--muted);margin-top:3px;">{{ $analytics['chartSubtitle'] }}</div>
            </div>
            @if($revChange != 0 || $maxRev > 1)
                @include('admin.partials.badge', [
                    'tone' => $revUp ? 'green' : 'red',
                    'dot' => true,
                    'label' => ($revUp ? 'Up ' : 'Down ').abs($revChange).'%',
                ])
            @endif
        </div>
        <div class="adm-bar-chart" style="{{ $range === '30' ? 'gap:4px;' : '' }}">
            @foreach($analytics['revenue7'] as $i => $v)
            <div class="adm-bar-col">
                <div class="adm-bar-val">
                    @if($v >= 1000)
                        ${{ number_format($v / 1000, 1) }}k
                    @elseif($v > 0)
                        ${{ number_format($v, 0) }}
                    @else
                        —
                    @endif
                </div>
                <div class="adm-bar" style="height:{{ round($v / $maxRev * 100) }}%;"></div>
                <div class="adm-bar-label" style="{{ $range === '30' ? 'font-size:10px;' : '' }}">{{ $analytics['revenueDays'][$i] }}</div>
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
            <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost btn-sm" style="text-decoration:none;">
                View all <x-icon name="arrow" :size="15"/>
            </a>
        </div>
        <div style="padding:6px 12px;">
            @forelse(array_slice($liveOrders, 0, 5) as $o)
            @php $itemCount = array_sum(array_column($o['items'], 'qty')); @endphp
            <a href="{{ route('admin.orders.index', ['q' => $o['id']]) }}" style="display:flex;align-items:center;gap:12px;padding:12px;text-decoration:none;color:inherit;border-radius:10px;transition:background .15s;" onmouseover="this.style.background='var(--ink-800)'" onmouseout="this.style.background='transparent'">
                <div style="width:40px;height:40px;border-radius:10px;background:var(--ink-800);display:grid;place-items:center;color:var(--gold-400);flex-shrink:0;">
                    <x-icon :name="$o['type'] === 'Delivery' ? 'truck' : 'bag'" :size="18"/>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:14.5px;">{{ $o['id'] }} · {{ $o['customer'] }}</div>
                    <div style="font-size:13px;color:var(--muted);">{{ $itemCount }} items · {{ $o['time'] }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:600;color:var(--gold-400);font-family:var(--serif);font-size:16px;">${{ number_format($o['total']) }}</div>
                    @include('admin.partials.badge', ['tone' => $statusTone[$o['status']] ?? 'blue', 'dot' => true, 'label' => $o['status']])
                </div>
            </a>
            @empty
            <div style="padding:28px 16px;text-align:center;color:var(--muted);font-size:14px;">No active orders right now.</div>
            @endforelse
        </div>
    </div>
    <div class="adm-card" style="padding:0;">
        <div style="padding:20px 24px;border-bottom:1px solid var(--line);">
            <h3 style="font-size:19px;font-weight:600;">Top dishes this period</h3>
        </div>
        <div style="padding:12px 24px;">
            @forelse($analytics['topItems'] as $i => $it)
            <div style="padding:11px 0;border-bottom:{{ $i < count($analytics['topItems']) - 1 ? '1px solid var(--line-soft)' : 'none' }};">
                <div style="display:flex;justify-content:space-between;margin-bottom:7px;font-size:14px;">
                    <span style="font-weight:600;"><span style="color:var(--faint);margin-right:8px;">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>{{ $it['name'] }}</span>
                    <span style="color:var(--sand);">{{ $it['sold'] }} sold · ${{ number_format($it['rev']) }}</span>
                </div>
                <div style="height:6px;background:var(--ink-800);border-radius:99px;">
                    <div style="width:{{ round($it['sold'] / $maxSold * 100) }}%;height:100%;border-radius:99px;background:linear-gradient(90deg,var(--gold-700),var(--gold-500));"></div>
                </div>
            </div>
            @empty
            <div style="padding:28px 0;text-align:center;color:var(--muted);font-size:14px;">No dish sales in this period yet.</div>
            @endforelse
        </div>
    </div>
</div>

@if(count($tasks) > 0)
<div class="adm-task-grid">
    @foreach($tasks as $task)
    <a href="{{ route($task[4]) }}" class="adm-card" style="padding:22px;cursor:pointer;display:flex;align-items:center;gap:16px;text-decoration:none;color:inherit;">
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
@endif
@endsection
