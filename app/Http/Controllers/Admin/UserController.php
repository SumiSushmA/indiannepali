<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.settings.index', ['tab' => 'users']);
    }

    public function edit(User $user): RedirectResponse
    {
        return redirect()->route('admin.settings.index', [
            'tab' => 'users',
            'edit_user' => $user->id,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120|unique:users,email',
            'role' => 'required|in:Owner,Manager,Sub-admin,Staff',
            'password' => 'required|string|min:8|max:120',
            'permissions' => 'array',
            'permissions.*' => 'string|in:'.implode(',', User::adminAreas()),
        ]);

        $permissions = $this->resolvePermissions($data);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => $data['password'],
            'admin_permissions' => $permissions,
            'status' => 'invited',
        ]);

        return redirect()->route('admin.settings.index', ['tab' => 'users'])->with('success', 'User invited.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:120|unique:users,email,'.$user->id,
            'role' => 'required|in:Owner,Manager,Sub-admin,Staff',
            'status' => 'required|in:active,invited,inactive',
            'permissions' => 'array',
            'permissions.*' => 'string|in:'.implode(',', User::adminAreas()),
            'password' => 'nullable|string|min:8|max:120',
        ]);

        $permissions = $this->resolvePermissions($data);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'status' => $data['status'],
            'admin_permissions' => $permissions,
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);

        return redirect()->route('admin.settings.index', [
            'tab' => 'users',
            'edit_user' => $user->id,
        ])->with('success', 'User updated.');
    }

    private function resolvePermissions(array $data): array
    {
        if ($data['role'] === 'Owner') {
            return User::adminAreas();
        }

        $permissions = array_values(array_unique($data['permissions'] ?? []));

        if ($permissions === []) {
            $permissions = ['dashboard', 'profile'];
        }

        if (! in_array('profile', $permissions, true)) {
            $permissions[] = 'profile';
        }

        return $permissions;
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.settings.index', ['tab' => 'users'])->with('success', 'User removed.');
    }
}
