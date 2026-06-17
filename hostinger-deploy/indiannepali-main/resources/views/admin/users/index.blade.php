@extends('layouts.admin')

@php
$roleTone = ['Owner' => 'gold', 'Manager' => 'purple', 'Chef' => 'red', 'Front of house' => 'blue', 'Marketing' => 'green'];
$activeCount = count(array_filter($users, fn($u) => $u['status'] === 'Active'));
@endphp

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px;flex-wrap:wrap;margin-bottom:26px;">
    <div>
        <h1 style="font-size:30px;font-weight:600;">Users & roles</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">{{ $activeCount }} active · {{ count($users) }} total</p>
    </div>
</div>

<div class="adm-card" style="padding:22px;margin-bottom:18px;">
    <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Invite user</h3>
    <form action="{{ route('admin.users.store') }}" method="POST" style="display:grid;gap:12px;">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <input name="name" placeholder="Full name" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
            <input name="email" type="email" placeholder="Email" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <select name="role" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                <option value="Owner">Owner</option>
                <option value="Manager">Manager</option>
                <option value="Sub-admin">Sub-admin</option>
                <option value="Staff">Staff</option>
            </select>
            <input name="password" type="password" placeholder="Temporary password" required minlength="8" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,minmax(120px,1fr));gap:8px;">
            @foreach($areas as $area)
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--sand);">
                    <input type="checkbox" name="permissions[]" value="{{ $area }}" @checked(in_array($area, ['dashboard', 'orders', 'reservations', 'profile'], true)) style="accent-color:var(--gold-600);">
                    {{ ucfirst($area) }}
                </label>
            @endforeach
        </div>
        <button type="submit" class="btn btn-gold btn-sm" style="justify-self:start;"><x-icon name="plus" :size="16"/> Invite user</button>
    </form>
</div>

<div class="adm-card" style="padding:8px;">
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Member</th><th>Role</th><th>Email</th><th>Status</th><th>Last active</th><th class="right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                @php
                    $initials = collect(explode(' ', $u['name']))->map(fn($n) => $n[0])->join('');
                    $statusTone = match($u['status']) {
                        'Active' => 'green',
                        'Invited' => 'gold',
                        default => 'neutral',
                    };
                    $statusValue = strtolower($u['status']);
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:38px;height:38px;border-radius:999px;background:linear-gradient(135deg,var(--gold-600),var(--spice-600));display:grid;place-items:center;color:#fff;font-weight:700;font-size:14px;font-family:var(--serif);">{{ $initials }}</div>
                            <span style="font-weight:600;color:var(--cream);">{{ $u['name'] }}</span>
                        </div>
                    </td>
                    <td>@include('admin.partials.badge', ['tone' => $roleTone[$u['role']] ?? 'neutral', 'label' => $u['role']])</td>
                    <td><span style="font-size:13.5px;color:var(--sand);">{{ $u['email'] }}</span></td>
                    <td>@include('admin.partials.badge', ['tone' => $statusTone, 'label' => $u['status']])</td>
                    <td><span style="font-size:13.5px;color:var(--muted);">{{ $u['last'] }}</span></td>
                    <td class="right">
                        <div style="display:flex;justify-content:flex-end;gap:8px;">
                            <a href="{{ route('admin.users.edit', $u['id']) }}" class="btn btn-ghost btn-sm" style="text-decoration:none;">Manage</a>
                            <form action="{{ route('admin.users.destroy', $u['id']) }}" method="POST" data-confirm="Remove this user?">
                                @csrf @method('DELETE')
                                <button type="submit" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--line);color:var(--sand);cursor:pointer;display:grid;place-items:center;"><x-icon name="trash" :size="16"/></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="adm-perm-grid">
    @foreach([
        ['Owner', 'Full access to everything, billing & integrations'],
        ['Manager', 'Orders, reservations, menu, content & reports'],
        ['Staff', 'View & update assigned orders and reservations'],
    ] as $r)
    <div class="adm-card" style="padding:22px;">
        <h4 style="font-size:17px;font-weight:600;margin-bottom:6px;">{{ $r[0] }}</h4>
        <p style="font-size:13.5px;color:var(--muted);line-height:1.6;margin:0;">{{ $r[1] }}</p>
    </div>
    @endforeach
</div>
@endsection
