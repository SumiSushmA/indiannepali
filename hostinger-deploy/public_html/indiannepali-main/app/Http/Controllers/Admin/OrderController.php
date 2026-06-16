<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $activeTab = $request->query('status', 'All');
        $search = trim((string) $request->query('q', ''));
        $allOrders = AdminData::getOrders();
        $orders = $allOrders;

        if ($search !== '') {
            $needle = strtolower($search);
            $orders = array_values(array_filter($orders, fn ($o) => str_contains(strtolower($o['id']), $needle)
                || str_contains(strtolower($o['customer']), $needle)));
        }

        return view('admin.orders.index', [
            'active' => 'orders',
            'activeTab' => $activeTab,
            'search' => $search,
            'orders' => $orders,
            'allOrders' => $allOrders,
            'orderStatuses' => AdminData::getOrderStatuses(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate(['status' => 'required|in:'.implode(',', AdminData::getOrderStatuses())]);

        $order->update(['status' => $request->input('status')]);

        return back()->with('success', 'Order '.$order->order_number.' updated.');
    }
}
