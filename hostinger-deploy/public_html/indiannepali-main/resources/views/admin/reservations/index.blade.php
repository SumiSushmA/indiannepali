@extends('layouts.admin')

@php
$statusTone = ['Confirmed' => 'green', 'Seated' => 'gold', 'Pending' => 'blue', 'Cancelled' => 'red', 'Completed' => 'neutral'];
$pending = count(array_filter($reservations, fn($r) => $r['status'] === 'Pending'));
$confirmed = count(array_filter($reservations, fn($r) => $r['status'] === 'Confirmed'));
$dow = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Reservations</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $pending }} pending · {{ $confirmed }} confirmed</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        <div data-adm-segments data-adm-target="reservations" style="display:inline-flex;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
            <button type="button" data-adm-segment="calendar" style="border:none;background:var(--ink-600);color:var(--cream);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);cursor:pointer;">Calendar</button>
            <button type="button" data-adm-segment="list" style="border:none;background:transparent;color:var(--muted);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);cursor:pointer;">List</button>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="document.getElementById('new-reservation-dialog')?.showModal()"><x-icon name="plus" :size="16"/> New booking</button>
    </div>
</div>

<dialog id="new-reservation-dialog" style="width:min(860px,calc(100vw - 28px));border:1px solid var(--line);border-radius:14px;background:var(--ink-700);color:var(--cream);padding:0;box-shadow:var(--shadow-3);">
    <form action="{{ route('admin.reservations.store') }}" method="POST" style="padding:18px;display:grid;gap:12px;">
        @csrf
        <h3 style="font-size:20px;font-weight:600;margin:0;">Add new booking</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Guest name</span>
                <input name="name" class="adm-inp" value="{{ old('name') }}" required>
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Phone</span>
                <input name="phone" class="adm-inp" value="{{ old('phone') }}" required>
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Email</span>
                <input name="email" type="email" class="adm-inp" value="{{ old('email') }}" required>
            </label>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Date</span>
                <input name="date" type="date" class="adm-inp" value="{{ old('date', now()->toDateString()) }}" required>
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Time</span>
                <select name="time" class="adm-inp" required>
                    @foreach(['17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00'] as $t)
                        <option value="{{ $t }}" @selected(old('time', '19:00') === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Party</span>
                <input name="party" type="number" min="1" max="20" class="adm-inp" value="{{ old('party', 2) }}" required>
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Table (optional)</span>
                <input name="table_number" class="adm-inp" value="{{ old('table_number') }}" placeholder="T7">
            </label>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Occasion (optional)</span>
                <input name="occasion" class="adm-inp" value="{{ old('occasion') }}" placeholder="Birthday">
            </label>
            <label style="display:grid;gap:6px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Status</span>
                <select name="status" class="adm-inp">
                    @foreach($resStatuses as $s)
                        <option value="{{ $s }}" @selected(old('status', 'Confirmed') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <label style="display:grid;gap:6px;">
            <span style="font-size:13px;color:var(--sand);font-weight:600;">Notes (optional)</span>
            <textarea name="notes" rows="3" class="adm-inp" style="min-height:96px;">{{ old('notes') }}</textarea>
        </label>
        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('new-reservation-dialog')?.close()">Cancel</button>
            <button type="submit" class="btn btn-gold btn-sm">Create booking</button>
        </div>
    </form>
</dialog>

@if($errors->any() && old('party'))
    @push('scripts')
    <script>
        document.getElementById('new-reservation-dialog')?.showModal();
    </script>
    @endpush
@endif

<div data-adm-view="reservations" data-adm-panel="calendar">
    <div class="adm-cal-wrap">
        <div class="adm-card" style="padding:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <h3 style="font-size:21px;font-weight:600;">{{ $monthLabel }}</h3>
            </div>
            <div class="adm-cal-grid">
                @foreach($dow as $d)
                <div class="adm-cal-dow">{{ $d }}</div>
                @endforeach
                @for($i = 0; $i < $firstDay; $i++)
                <div></div>
                @endfor
                @for($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $count = $calCounts[$d] ?? 0;
                    $heat = $count === 0 ? 0 : ($count < 6 ? 1 : ($count < 12 ? 2 : 3));
                @endphp
                <div class="adm-cal-cell {{ $d === $today ? 'today' : '' }} {{ $heat ? 'heat-' . $heat : '' }}" style="cursor:default;">
                    <span style="font-size:13.5px;font-weight:{{ $d === $today ? '700' : '500' }};color:{{ $d === $today ? 'var(--gold-400)' : 'var(--cream-2)' }};">{{ $d }}</span>
                    @if($count > 0)<span style="font-size:11px;color:var(--sand);font-weight:600;">{{ $count }} bk</span>@endif
                </div>
                @endfor
            </div>
        </div>
        <div class="adm-card" style="padding:0;">
            <div style="padding:18px 20px;border-bottom:1px solid var(--line);">
                <h3 style="font-size:18px;font-weight:600;">Upcoming</h3>
                <div style="font-size:13px;color:var(--muted);margin-top:3px;">{{ count($reservations) }} reservations this month</div>
            </div>
            <div style="padding:12px;">
                @forelse(array_slice($reservations, 0, 7) as $r)
                <div style="display:flex;align-items:center;gap:12px;padding:11px;border-radius:10px;border-bottom:1px solid var(--line-soft);">
                    <div style="font-family:var(--serif);font-size:15px;font-weight:600;color:var(--gold-400);width:48px;flex-shrink:0;">{{ $r['time'] }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:14px;">{{ $r['name'] }}</div>
                        <div style="font-size:12.5px;color:var(--muted);">{{ $r['party'] }} guests · {{ $r['table'] }}</div>
                    </div>
                    <form action="{{ route('admin.reservations.status', $r['id']) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" style="background:var(--ink-700);border:1px solid var(--line);color:var(--cream);border-radius:8px;padding:5px 8px;font-size:12px;font-family:var(--sans);">
                            @foreach($resStatuses as $s)
                                <option value="{{ $s }}" @selected($r['status'] === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                @empty
                <div style="padding:20px;text-align:center;color:var(--muted);font-size:14px;">No reservations yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div data-adm-view="reservations" data-adm-panel="list" hidden>
    <div class="adm-card" style="padding:8px;">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Ref</th><th>Guest</th><th>Date</th><th>Time</th><th>Party</th><th>Table</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                    <tr>
                        <td style="font-weight:600;color:var(--cream);">{{ $r['id'] }}</td>
                        <td>{{ $r['name'] }}</td>
                        <td>{{ $r['date'] }}</td>
                        <td>{{ $r['time'] }}</td>
                        <td>{{ $r['party'] }}</td>
                        <td>{{ $r['table'] }}</td>
                        <td>
                            <form action="{{ route('admin.reservations.status', $r['id']) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="background:var(--ink-700);border:1px solid var(--line);color:var(--cream);border-radius:8px;padding:6px 10px;font-size:13px;font-family:var(--sans);">
                                    @foreach($resStatuses as $s)
                                        <option value="{{ $s }}" @selected($r['status'] === $s)>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="padding:24px;text-align:center;color:var(--muted);">No reservations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
