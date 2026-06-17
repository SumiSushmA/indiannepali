<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $range = in_array($request->query('range'), ['today', '30'], true)
            ? $request->query('range')
            : '7';

        $orders = AdminData::getOrders();
        $badges = AdminData::getNavBadges();
        $analytics = AdminData::getAnalytics($range);
        $dashboard = AdminData::getDashboardStats($range);

        $user = $request->user();
        $firstName = Str::before($user->name ?? 'there', ' ');
        $hour = (int) now()->format('G');
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

        $tasks = array_values(array_filter([
            $badges['reservations'] > 0 ? [
                'cal', 'gold',
                $badges['reservations'].' reservation'.($badges['reservations'] === 1 ? '' : 's'),
                'pending confirmation',
                'admin.reservations.index',
            ] : null,
            $badges['catering'] > 0 ? [
                'box', 'purple',
                $badges['catering'].' catering inquir'.($badges['catering'] === 1 ? 'y' : 'ies'),
                'awaiting a quote',
                'admin.catering.index',
            ] : null,
            $badges['contact'] > 0 ? [
                'mail', 'red',
                $badges['contact'].' unread message'.($badges['contact'] === 1 ? '' : 's'),
                'in the contact inbox',
                'admin.inquiries.index',
            ] : null,
            $badges['orders'] > 0 ? [
                'bag', 'green',
                $badges['orders'].' new order'.($badges['orders'] === 1 ? '' : 's'),
                'need attention',
                'admin.orders.index',
            ] : null,
        ]));

        return view('admin.dashboard', [
            'active' => 'overview',
            'range' => $range,
            'greeting' => $greeting,
            'firstName' => $firstName,
            'orders' => $orders,
            'liveOrders' => array_values(array_filter($orders, fn ($o) => in_array($o['status'], ['New', 'Preparing'], true))),
            'analytics' => $analytics,
            'dashboardStats' => $dashboard['cards'],
            'tasks' => $tasks,
            'badges' => $badges,
        ]);
    }
}
