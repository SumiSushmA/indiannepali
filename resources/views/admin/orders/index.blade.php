@extends('layouts.admin')

@php
$statusTone = ['New' => 'gold', 'Preparing' => 'blue', 'Ready' => 'green', 'Out for delivery' => 'purple', 'Completed' => 'neutral'];
$tabs = array_merge(['All'], $orderStatuses);
$activeTab = $activeTab ?? 'All';
$counts = ['All' => count($orders)];
foreach ($orderStatuses as $s) {
    $counts[$s] = count(array_filter($orders, fn($o) => $o['status'] === $s));
}
$filteredOrders = $activeTab === 'All'
    ? $orders
    : array_values(array_filter($orders, fn($o) => $o['status'] === $activeTab));
$newCount = $counts['New'] ?? 0;
$inProgress = count(array_filter($orders, fn($o) => in_array($o['status'], ['Preparing', 'Ready', 'Out for delivery'], true)));
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Orders</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $newCount }} new · {{ $inProgress }} in progress</p>
    </div>
    <button class="btn btn-gold btn-sm"><x-icon name="plus" :size="16"/> New order</button>
</div>

<div class="adm-tabs">
    @foreach($tabs as $tab)
    <a href="{{ $tab === 'All' ? route('admin.orders.index') : route('admin.orders.index', ['status' => $tab]) }}" class="adm-tab {{ $activeTab === $tab ? 'active' : '' }}">
        {{ $tab }} <span class="adm-tab-count">{{ $counts[$tab] }}</span>
    </a>
    @endforeach
</div>

<div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:18px;">
    <div style="display:flex;align-items:center;gap:9px;background:var(--ink-700);border:1px solid var(--line);border-radius:10px;padding:0 13px;min-width:220px;">
        <x-icon name="search" :size="16" color="var(--muted)"/>
        <input placeholder="Search by order # or guest…" style="flex:1;background:none;border:none;outline:none;color:var(--cream);font-size:14px;padding:10px 0;font-family:var(--sans);">
    </div>
    <button class="btn btn-ghost btn-sm"><x-icon name="filter" :size="15"/> Filters</button>
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Order</th><th>Guest</th><th>Type</th><th>Channel</th><th>Items</th>
                    <th class="right">Total</th><th>Status</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($filteredOrders as $o)
                @php $itemCount = array_sum(array_column($o['items'], 'qty')); @endphp
                <tr>
                    <td>
                        <span style="font-weight:700;color:var(--cream);">{{ $o['id'] }}</span>
                        <div style="font-size:12px;color:var(--muted);">{{ $o['time'] }}</div>
                    </td>
                    <td>{{ $o['customer'] }}</td>
                    <td>
                        <span style="display:inline-flex;gap:6px;align-items:center;">
                            <x-icon :name="$o['type'] === 'Delivery' ? 'truck' : 'bag'" :size="15" color="var(--muted)"/>
                            {{ $o['type'] }}
                        </span>
                    </td>
                    <td><span style="font-size:13px;color:var(--sand);">{{ $o['channel'] }}</span></td>
                    <td>{{ $itemCount }}</td>
                    <td class="right"><span style="font-weight:600;font-family:var(--serif);font-size:16px;color:var(--cream);">${{ $o['total'] }}</span></td>
                    <td>
                        <form action="{{ route('admin.orders.status', $o['id']) }}" method="POST" style="display:inline-flex;align-items:center;gap:8px;">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()" style="background:var(--ink-700);border:1px solid var(--line);color:var(--cream);border-radius:8px;padding:6px 10px;font-size:13px;font-family:var(--sans);">
                                @foreach($orderStatuses as $s)
                                    <option value="{{ $s }}" @selected($o['status'] === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="right">
                        <button style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;">
                            <x-icon name="arrow" :size="16"/>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
