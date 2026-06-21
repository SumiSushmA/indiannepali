<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminNotifications;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function markAllRead(): RedirectResponse
    {
        AdminNotifications::markAllRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
