<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(): RedirectResponse
    {
        return redirect()->route('admin.settings.index', ['tab' => 'profile']);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120|unique:users,email,'.$request->user()->id,
        ]);

        $request->user()->update($data);

        return redirect()->route('admin.settings.index', ['tab' => 'profile'])->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|max:120|confirmed',
        ]);

        if (! Hash::check($data['current_password'], $request->user()->password)) {
            return redirect()
                ->route('admin.settings.index', ['tab' => 'profile'])
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $request->user()->update(['password' => $data['password']]);

        return redirect()->route('admin.settings.index', ['tab' => 'profile'])->with('success', 'Password changed.');
    }
}
