@extends('layouts.admin')

@section('content')
<div style="max-width:760px;">
    <div style="margin-bottom:26px;">
        <h1 style="font-size:30px;font-weight:600;">My profile</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Update your admin account details and password.</p>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" style="margin-bottom:16px;">
        @csrf @method('PUT')
        <div class="adm-card" style="padding:22px;">
            <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Profile details</h3>
            <div style="display:grid;gap:14px;">
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">Full name</span>
                    <input name="name" value="{{ old('name', $user->name) }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </label>
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">Email</span>
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </label>
            </div>
            <button type="submit" class="btn btn-gold" style="margin-top:16px;">Save profile</button>
        </div>
    </form>

    <form action="{{ route('admin.profile.password') }}" method="POST">
        @csrf @method('PUT')
        <div class="adm-card" style="padding:22px;">
            <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Change password</h3>
            <div style="display:grid;gap:14px;">
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">Current password</span>
                    <input name="current_password" type="password" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </label>
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">New password</span>
                    <input name="password" type="password" required minlength="8" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </label>
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">Confirm new password</span>
                    <input name="password_confirmation" type="password" required minlength="8" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </label>
            </div>
            <button type="submit" class="btn btn-gold" style="margin-top:16px;">Update password</button>
        </div>
    </form>
</div>
@endsection
