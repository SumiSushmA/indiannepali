@extends('layouts.admin')

@php
$statusTone = ['New' => 'gold', 'Preparing' => 'blue', 'Ready' => 'green', 'Out for delivery' => 'purple', 'Completed' => 'neutral'];
$tabs = array_merge(['All'], $orderStatuses);
$activeTab = $activeTab ?? 'All';
$allOrders = $allOrders ?? $orders;
$counts = ['All' => count($allOrders)];
foreach ($orderStatuses as $s) {
    $counts[$s] = count(array_filter($allOrders, fn($o) => $o['status'] === $s));
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
    <a href="{{ route('menu') }}" target="_blank" class="btn btn-ghost btn-sm" style="text-decoration:none;">View customer menu ↗</a>
</div>

<div style="display:flex;align-items:flex-end;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:18px;">
    <div class="adm-tabs" style="margin-bottom:0;border-bottom:none;flex:1;min-width:340px;">
        @foreach($tabs as $tab)
        <a href="{{ $tab === 'All' ? route('admin.orders.index', request()->only('q')) : route('admin.orders.index', array_merge(['status' => $tab], request()->only('q'))) }}" class="adm-tab {{ $activeTab === $tab ? 'active' : '' }}">
            {{ $tab }} <span class="adm-tab-count">{{ $counts[$tab] ?? 0 }}</span>
        </a>
        @endforeach
    </div>

    <div></div>
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table" id="adm-orders-table">
            <thead>
                <tr>
                    <th>Order</th><th>Guest</th><th>Type</th><th>Channel</th><th>Items</th>
                    <th class="right">Total</th><th>Status</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredOrders as $o)
                @php $itemCount = array_sum(array_column($o['items'], 'qty')); @endphp
                <tr data-adm-row data-adm-search-text="{{ strtolower($o['id'].' '.$o['customer']) }}">
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
                        <button type="button" data-adm-order-toggle="{{ $o['id'] }}" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;" aria-label="Show items">
                            <x-icon name="arrow" :size="16"/>
                        </button>
                    </td>
                </tr>
                <tr data-adm-order-detail="{{ $o['id'] }}" hidden>
                    <td colspan="8" style="background:var(--ink-800);padding:14px 18px;">
                        <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">Line items</div>
                        <div style="display:flex;flex-wrap:wrap;gap:8px 18px;font-size:14px;color:var(--cream-2);">
                            @foreach($o['items'] as $item)
                                <span>{{ $item['qty'] }}× {{ $item['name'] }}</span>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="padding:24px;text-align:center;color:var(--muted);">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
