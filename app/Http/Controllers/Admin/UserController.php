<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'active' => 'users',
            'users' => AdminData::getUsers(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120|unique:users,email',
            'role' => 'required|string|max:40',
            'password' => 'required|string|min:8|max:120',
        ]);

        User::create([
            ...$data,
            'status' => 'invited',
        ]);

        return back()->with('success', 'User invited.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:active,invited,inactive',
        ]);

        $user->update(['status' => $request->input('status')]);

        return back()->with('success', 'User status updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('success', 'User removed.');
    }
}
