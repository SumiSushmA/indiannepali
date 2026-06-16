@extends('layouts.admin')

@php
$statusTone = ['New' => 'gold', 'Quoted' => 'blue', 'In conversation' => 'purple', 'Booked' => 'green'];
$pipeline = ['New', 'Quoted', 'In conversation', 'Booked'];
$pipelineValue = array_sum(array_column(array_filter($catering, fn($c) => ($c['value'] ?? 0) > 0), 'value'));
$newLeads = count(array_filter($catering, fn($c) => $c['status'] === 'New'));
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Catering inquiries</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $newLeads }} new leads · ${{ number_format($pipelineValue) }} in pipeline</p>
    </div>
    <div data-adm-segments data-adm-target="catering" style="display:inline-flex;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:3px;">
        <button type="button" data-adm-segment="board" style="border:none;background:var(--ink-600);color:var(--cream);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);cursor:pointer;">Board</button>
        <button type="button" data-adm-segment="list" style="border:none;background:transparent;color:var(--muted);padding:7px 14px;border-radius:7px;font-weight:600;font-size:13px;font-family:var(--sans);cursor:pointer;">List</button>
    </div>
</div>

<div data-adm-view="catering" data-adm-panel="board" class="adm-kanban">
    @foreach($pipeline as $stage)
    @php $cards = array_filter($catering, fn($c) => $c['status'] === $stage); @endphp
    <div style="background:var(--ink-850);border:1px solid var(--line);border-radius:14px;padding:12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 8px 12px;">
            <span style="font-weight:600;font-size:14.5px;display:flex;gap:8px;align-items:center;">
                <span style="width:8px;height:8px;border-radius:99px;background:var(--gold-400);"></span>
                {{ $stage }}
            </span>
            <span style="font-size:12px;color:var(--muted);background:var(--ink-700);border-radius:99px;padding:2px 8px;">{{ count($cards) }}</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($cards as $c)
            <div style="background:var(--ink-700);border:1px solid var(--line);border-radius:11px;padding:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span style="font-weight:600;font-size:14px;">{{ $c['name'] }}</span>
                    @include('admin.partials.badge', ['tone' => 'gold', 'label' => $c['event']])
                </div>
                <div style="display:flex;gap:14px;font-size:12.5px;color:var(--muted);margin-bottom:10px;">
                    <span style="display:flex;gap:5px;align-items:center;"><x-icon name="users" :size="13"/> {{ $c['guests'] }}</span>
                    <span style="display:flex;gap:5px;align-items:center;"><x-icon name="cal" :size="13"/> {{ substr($c['date'], 5) }}</span>
                </div>
                <div style="padding-top:10px;border-top:1px solid var(--line-soft);display:grid;gap:8px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                        @if(($c['value'] ?? 0) > 0)
                            <span style="font-family:var(--serif);font-size:17px;font-weight:600;color:var(--gold-400);">
                                @if($c['status'] === 'New')<span style="font-family:var(--sans);font-size:11px;color:var(--muted);font-weight:600;margin-right:4px;">EST.</span>@endif
                                ${{ number_format($c['value']) }}
                            </span>
                        @else
                            <span style="font-size:13px;color:var(--muted);">Quote pending</span>
                        @endif
                        <form action="{{ route('admin.catering.status', $c['id']) }}" method="POST">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()">
                                @foreach($pipeline as $s)
                                    <option value="{{ $s }}" @selected($c['status'] === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <form action="{{ route('admin.catering.quote', $c['id']) }}" method="POST" style="display:flex;gap:6px;align-items:center;">
                        @csrf @method('PATCH')
                        <input type="number" name="quoted_value" min="0" step="1" value="{{ $c['quoted_value'] ?? $c['value'] }}" placeholder="Quote $" class="adm-inp" style="min-height:38px;padding:8px 10px;font-size:13px;">
                        <button type="submit" class="btn btn-ghost btn-sm">Save quote</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<div data-adm-view="catering" data-adm-panel="list" hidden>
    <div class="adm-card" style="padding:8px;margin-bottom:24px;">
        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr><th>Guest</th><th>Event</th><th>Date</th><th>Guests</th><th>Value</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($catering as $c)
                    <tr>
                        <td style="font-weight:600;color:var(--cream);">{{ $c['name'] }}</td>
                        <td>{{ $c['event'] }}</td>
                        <td>{{ $c['date'] }}</td>
                        <td>{{ $c['guests'] }}</td>
                        <td>${{ number_format($c['value']) }}</td>
                        <td>
                            <form action="{{ route('admin.catering.status', $c['id']) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" style="background:var(--ink-700);border:1px solid var(--line);color:var(--cream);border-radius:8px;padding:6px 10px;font-size:13px;font-family:var(--sans);">
                                    @foreach($pipeline as $s)
                                        <option value="{{ $s }}" @selected($c['status'] === $s)>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:32px;">
    <h2 style="font-size:22px;font-weight:600;margin-bottom:18px;">Catering packages</h2>

    <div class="adm-card" style="padding:22px;margin-bottom:18px;">
        <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Add package</h3>
        <form action="{{ route('admin.catering.packages.store') }}" method="POST" style="display:grid;gap:12px;">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                <input name="name" placeholder="Package name" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                <input name="guest_range" placeholder="Guest range (e.g. 20–40)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                <input name="price_label" placeholder="Price label (e.g. From $18/person)" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            </div>
            <textarea name="items" placeholder="Items (one per line)" required rows="3" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;"></textarea>
            <div style="display:flex;gap:12px;align-items:center;">
                <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--cream-2);">
                    <input type="checkbox" name="is_popular" value="1"> Popular
                </label>
                <button type="submit" class="btn btn-gold btn-sm" style="margin-left:auto;">Add package</button>
            </div>
        </form>
    </div>

    <div class="adm-perm-grid">
        @foreach($packages as $pkg)
        <div class="adm-card" style="padding:22px;">
            <form action="{{ route('admin.catering.packages.update', $pkg) }}" method="POST" style="display:grid;gap:10px;">
                @csrf @method('PUT')
                <input name="name" value="{{ $pkg->name }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 12px;color:var(--cream);font-weight:600;font-family:var(--sans);">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <input name="guest_range" value="{{ $pkg->guest_range }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 12px;color:var(--cream);font-size:13px;font-family:var(--sans);">
                    <input name="price_label" value="{{ $pkg->price_label }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 12px;color:var(--cream);font-size:13px;font-family:var(--sans);">
                </div>
                <textarea name="items" rows="4" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:10px 12px;color:var(--cream);font-size:13px;font-family:var(--sans);resize:vertical;">{{ implode("\n", $pkg->items ?? []) }}</textarea>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--cream-2);">
                    <input type="checkbox" name="is_popular" value="1" @checked($pkg->is_popular)> Popular
                </label>
                <button type="submit" class="btn btn-ghost btn-sm">Save</button>
            </form>
            <form action="{{ route('admin.catering.packages.destroy', $pkg) }}" method="POST" style="margin-top:8px;" data-confirm="Delete this package?">
                @csrf @method('DELETE')
                <button type="submit" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;" aria-label="Delete package"><x-icon name="trash" :size="16"/></button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection
