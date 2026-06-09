<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminData;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $orders = AdminData::getOrders();
        $analytics = AdminData::getAnalytics();

        return view('admin.dashboard', [
            'active' => 'overview',
            'orders' => $orders,
            'liveOrders' => array_values(array_filter($orders, fn ($o) => in_array($o['status'], ['New', 'Preparing'], true))),
            'analytics' => $analytics,
            'badges' => AdminData::getNavBadges(),
        ]);
    }
}
