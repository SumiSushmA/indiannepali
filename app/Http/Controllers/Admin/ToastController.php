<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AdminData;
use App\Services\Toast\ToastConfiguration;
use App\Services\Toast\ToastSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ToastController extends Controller
{
    public function __construct(private ToastSyncService $syncService) {}

    public function index(): View
    {
        return view('admin.toast.index', [
            'active' => 'toast',
            'toast' => AdminData::getToast(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function sync(): RedirectResponse
    {
        $log = $this->syncService->sync();

        Setting::set('toast_connected', ToastConfiguration::isLive());
        Setting::set('toast_last_sync', now()->toIso8601String());
        Setting::set('toast_payment_mode', ToastConfiguration::mode());

        $message = $log->is_success
            ? ($log->message)
            : $log->message;

        return back()->with($log->is_success ? 'success' : 'error', $message);
    }
}
