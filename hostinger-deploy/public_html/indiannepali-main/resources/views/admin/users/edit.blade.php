@extends('layouts.admin')

@php
$statusValue = strtolower(old('status', $editUser->status));
$roleValue = old('role', $editUser->role);
$selectedPermissions = old('permissions', $editUser->admin_permissions ?? []);
@endphp

@section('content')
<div style="max-width:980px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
        <div>
            <h1 style="font-size:30px;font-weight:600;">Edit admin user</h1>
            <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Manage role, status, permissions, and password.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm" style="text-decoration:none;">Back to users</a>
    </div>

    <form action="{{ route('admin.users.update', $editUser) }}" method="POST">
        @csrf @method('PATCH')
        <div class="adm-card" style="padding:22px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">Full name</span>
                    <input name="name" value="{{ old('name', $editUser->name) }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </label>
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">Email</span>
                    <input name="email" type="email" value="{{ old('email', $editUser->email) }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </label>
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">Role</span>
                    <select name="role" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        @foreach(['Owner', 'Manager', 'Sub-admin', 'Staff'] as $role)
                            <option value="{{ $role }}" @selected($roleValue === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                </label>
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">Status</span>
                    <select name="status" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        <option value="active" @selected($statusValue === 'active')>Active</option>
                        <option value="invited" @selected($statusValue === 'invited')>Invited</option>
                        <option value="inactive" @selected($statusValue === 'inactive')>Inactive</option>
                    </select>
                </label>
            </div>

            <div style="margin-top:16px;">
                <div style="font-size:13px;color:var(--sand);font-weight:600;margin-bottom:8px;">Feature access (for Sub-admin)</div>
                <div style="display:grid;grid-template-columns:repeat(4,minmax(140px,1fr));gap:8px;">
                    @foreach($areas as $area)
                        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--cream-2);">
                            <input type="checkbox" name="permissions[]" value="{{ $area }}" @checked(in_array($area, $selectedPermissions, true)) style="accent-color:var(--gold-600);">
                            {{ ucfirst($area) }}
                        </label>
                    @endforeach
                </div>
            </div>

            <label style="display:flex;flex-direction:column;gap:6px;margin-top:16px;">
                <span style="font-size:13px;color:var(--sand);font-weight:600;">Set new password (optional)</span>
                <input name="password" type="password" minlength="8" placeholder="Leave blank to keep current password" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            </label>

            <button type="submit" class="btn btn-gold" style="margin-top:16px;">Save user</button>
        </div>
    </form>
</div>
@endsection
