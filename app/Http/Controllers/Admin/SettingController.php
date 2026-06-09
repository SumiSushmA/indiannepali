<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'active' => 'settings',
            'badges' => AdminData::getNavBadges(),
            'settings' => Setting::allCached(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'restaurant_name' => 'required|string|max:120',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:120',
            'hours' => 'required|string|max:120',
            'closed_days' => 'nullable|string|max:120',
            'footer_tagline' => 'nullable|string|max:500',
            'instagram_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'privacy_url' => 'nullable|url|max:255',
            'terms_url' => 'nullable|url|max:255',
            'accessibility_url' => 'nullable|url|max:255',
            'tax_rate' => 'required|numeric|min:0|max:1',
            'delivery_fee' => 'required|numeric|min:0',
            'free_delivery_min' => 'required|numeric|min:0',
            'online_ordering_enabled' => 'nullable|boolean',
            'delivery_enabled' => 'nullable|boolean',
            'tips_enabled' => 'nullable|boolean',
            'sms_alerts_enabled' => 'nullable|boolean',
        ]);

        foreach ([
            'restaurant_name', 'address', 'city', 'phone', 'email', 'hours', 'closed_days', 'footer_tagline',
            'instagram_url', 'facebook_url', 'whatsapp_url', 'privacy_url', 'terms_url', 'accessibility_url',
            'tax_rate', 'delivery_fee', 'free_delivery_min',
        ] as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        foreach (['online_ordering_enabled', 'delivery_enabled', 'tips_enabled', 'sms_alerts_enabled'] as $key) {
            Setting::set($key, $request->boolean($key));
        }

        return back()->with('success', 'Settings saved.');
    }
}
