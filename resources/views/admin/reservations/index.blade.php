@extends('layouts.admin')

@php
$statusTone = ['Confirmed' => 'green', 'Seated' => 'gold', 'Pending' => 'blue', 'Cancelled' => 'red', 'Completed' => 'neutral'];
$pending = count(array_filter($reservations, fn($r) => $r['status'] === 'Pending'));
$confirmed = count(array_filter($reservations, fn($r) => $r['status'] === 'Confirmed'));
$firstDay = (int) date('w', strtotime('2026-06-01'));
$dow = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
$today = 2;
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Reservations</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $pending }} pending · {{ $confirmed }} confirmed</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        <div style="display:inline-flex;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
            <button style="border:none;background:var(--ink-600);color:var(--cream);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);">Calendar</button>
            <button style="border:none;background:transparent;color:var(--muted);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);cursor:pointer;">List</button>
        </div>
        <button class="btn btn-gold btn-sm"><x-icon name="plus" :size="16"/> Add booking</button>
    </div>
</div>

<div class="adm-cal-wrap">
    <div class="adm-card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
            <h3 style="font-size:21px;font-weight:600;">June 2026</h3>
            <div style="display:flex;gap:8px;">
                <button style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"><x-icon name="arrowL" :size="16"/></button>
                <button style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"><x-icon name="arrow" :size="16"/></button>
            </div>
        </div>
        <div class="adm-cal-grid">
            @foreach($dow as $d)
            <div class="adm-cal-dow">{{ $d }}</div>
            @endforeach
            @for($i = 0; $i < $firstDay; $i++)
            <div></div>
            @endfor
            @for($d = 1; $d <= 30; $d++)
            @php
                $count = $calCounts[$d] ?? 0;
                $heat = $count === 0 ? 0 : ($count < 6 ? 1 : ($count < 12 ? 2 : 3));
            @endphp
            <button class="adm-cal-cell {{ $d === $today ? 'today' : '' }} {{ $heat ? 'heat-' . $heat : '' }}">
                <span style="font-size:13.5px;font-weight:{{ $d === $today ? '700' : '500' }};color:{{ $d === $today ? 'var(--gold-400)' : 'var(--cream-2)' }};">{{ $d }}</span>
                @if($count > 0)<span style="font-size:11px;color:var(--sand);font-weight:600;">{{ $count }} bk</span>@endif
            </button>
            @endfor
        </div>
    </div>
    <div class="adm-card" style="padding:0;">
        <div style="padding:18px 20px;border-bottom:1px solid var(--line);">
            <h3 style="font-size:18px;font-weight:600;">Tonight's service</h3>
            <div style="font-size:13px;color:var(--muted);margin-top:3px;">Tue, Jun 2 · 8 tables seated</div>
        </div>
        <div style="padding:12px;">
            @foreach(array_slice($reservations, 0, 7) as $r)
            <div style="display:flex;align-items:center;gap:12px;padding:11px;border-radius:10px;cursor:pointer;">
                <div style="font-family:var(--serif);font-size:15px;font-weight:600;color:var(--gold-400);width:48px;flex-shrink:0;">{{ $r['time'] }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:14px;">{{ $r['name'] }}</div>
                    <div style="font-size:12.5px;color:var(--muted);">{{ $r['party'] }} guests · {{ $r['table'] }}</div>
                </div>
                @include('admin.partials.badge', ['tone' => $statusTone[$r['status']] ?? 'neutral', 'label' => $r['status']])
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
