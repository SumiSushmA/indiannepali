<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $tab = $requestTab = request()->query('tab', 'restaurant');
        if (! in_array($requestTab, ['restaurant', 'users', 'profile'], true)) {
            $tab = 'restaurant';
        }

        $editUserId = request()->query('edit_user');
        $editUser = null;
        if ($tab === 'users' && $editUserId) {
            $editUser = User::query()->find($editUserId);
        }

        return view('admin.settings.index', [
            'active' => 'settings',
            'badges' => AdminData::getNavBadges(),
            'settings' => Setting::allCached(),
            'tab' => $tab,
            'adminUsers' => User::query()->orderBy('name')->get(),
            'areas' => User::adminAreas(),
            'editUser' => $editUser,
            'profileUser' => auth()->user(),
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
            'map_embed_url' => 'nullable|url|max:500',
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
            'instagram_url', 'facebook_url', 'whatsapp_url', 'map_embed_url',
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
