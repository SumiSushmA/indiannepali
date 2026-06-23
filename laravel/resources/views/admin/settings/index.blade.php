@extends('layouts.admin')

@php
$tab = $tab ?? 'restaurant';
@endphp

@section('content')
<div class="adm-settings">
    <div class="adm-settings-head">
        <h1 class="adm-page-title">Settings</h1>
        <p class="adm-page-sub">Manage restaurant settings, users, and your profile in one place.</p>
    </div>

    <div class="adm-settings-toolbar">
        <div class="adm-settings-tabs">
            @foreach([
                'restaurant' => 'Settings',
                'users' => 'Users & roles',
                'profile' => 'My profile',
            ] as $key => $label)
                <a href="{{ route('admin.settings.index', ['tab' => $key]) }}"
                   class="btn btn-sm {{ $tab === $key ? 'btn-gold' : 'btn-ghost' }}"
                   style="text-decoration:none;flex-shrink:0;">{{ $label }}</a>
            @endforeach
        </div>
        @if($tab === 'restaurant')
            <button type="submit" form="restaurant-settings-form" class="btn btn-gold adm-settings-save adm-settings-save--top">Save settings</button>
        @endif
    </div>

    @if($tab === 'restaurant')
        <form id="restaurant-settings-form" class="adm-settings-form" action="{{ route('admin.settings.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="adm-settings-layout">
                <div class="adm-card adm-settings-card">
                    <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Restaurant profile</h3>
                    <div class="adm-settings-fields">
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">Name</span>
                            <input name="restaurant_name" value="{{ $settings['restaurant_name'] ?? 'Indian-Nepali Kitchen' }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        </label>
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">Address</span>
                            <input name="address" value="{{ $settings['address'] ?? '13754 Aurora Ave N, Suite D' }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        </label>
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">City & postal</span>
                            <input name="city" value="{{ $settings['city'] ?? 'Seattle, WA 98133' }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        </label>
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">Google Maps embed URL (optional)</span>
                            <input name="map_embed_url" type="url" value="{{ $settings['map_embed_url'] ?? '' }}" placeholder="https://www.google.com/maps/embed?pb=…" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        </label>
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">Phone</span>
                            <input name="phone" value="{{ $settings['phone'] ?? '(206) 397-3211' }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        </label>
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">Email</span>
                            <input name="email" type="email" value="{{ $settings['email'] ?? 'hello@indiannepali.kitchen' }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        </label>
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">Hours</span>
                            <input name="hours" value="{{ $settings['hours'] ?? 'Tue–Sun · 11:30 – 22:00' }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        </label>
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">Closed days</span>
                            <input name="closed_days" value="{{ $settings['closed_days'] ?? 'Closed Mondays' }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        </label>
                        <label style="display:flex;flex-direction:column;gap:6px;">
                            <span style="font-size:13px;color:var(--sand);font-weight:600;">Footer tagline</span>
                            <textarea name="footer_tagline" rows="2" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);resize:vertical;">{{ $settings['footer_tagline'] ?? '' }}</textarea>
                        </label>
                    </div>
                </div>

                <div class="adm-settings-stack">
                    <div class="adm-card adm-settings-card">
                        <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Social links</h3>
                        <div class="adm-settings-fields">
                            @foreach([
                                ['instagram_url', 'Instagram URL'],
                                ['facebook_url', 'Facebook URL'],
                                ['whatsapp_url', 'WhatsApp URL'],
                            ] as [$key, $label])
                            <label style="display:flex;flex-direction:column;gap:6px;">
                                <span style="font-size:13px;color:var(--sand);font-weight:600;">{{ $label }}</span>
                                <input name="{{ $key }}" type="url" value="{{ $settings[$key] ?? '' }}" placeholder="https://…" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="adm-card adm-settings-card">
                        <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Ordering & pricing</h3>
                        <div class="adm-settings-fields">
                            <label style="display:flex;flex-direction:column;gap:6px;">
                                <span style="font-size:13px;color:var(--sand);font-weight:600;">Tax rate</span>
                                <input name="tax_rate" type="number" step="0.0001" value="{{ $settings['tax_rate'] ?? 0.0875 }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                            </label>
                            <label style="display:flex;flex-direction:column;gap:6px;">
                                <span style="font-size:13px;color:var(--sand);font-weight:600;">Delivery fee ($)</span>
                                <input name="delivery_fee" type="number" step="0.01" value="{{ $settings['delivery_fee'] ?? 3.99 }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                            </label>
                            <label style="display:flex;flex-direction:column;gap:6px;">
                                <span style="font-size:13px;color:var(--sand);font-weight:600;">Free delivery min ($)</span>
                                <input name="free_delivery_min" type="number" step="0.01" value="{{ $settings['free_delivery_min'] ?? 40 }}" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                            </label>
                        </div>
                        <div style="margin-top:18px;display:flex;flex-direction:column;gap:12px;">
                            @foreach([
                                ['online_ordering_enabled', 'Online ordering', 'Accept orders from the website'],
                                ['delivery_enabled', 'Delivery', 'Offer delivery within 4 miles'],
                                ['tips_enabled', 'Allow tips', 'Show tip options at checkout'],
                                ['sms_alerts_enabled', 'SMS order alerts', 'Text the kitchen on new orders'],
                            ] as $toggle)
                            <label style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--line-soft);cursor:pointer;">
                                <div>
                                    <div style="font-weight:600;font-size:14.5px;">{{ $toggle[1] }}</div>
                                    <div style="font-size:13px;color:var(--muted);margin-top:2px;">{{ $toggle[2] }}</div>
                                </div>
                                <input type="hidden" name="{{ $toggle[0] }}" value="0">
                                <input type="checkbox" name="{{ $toggle[0] }}" value="1" @checked(($settings[$toggle[0]] ?? 'true') === true || ($settings[$toggle[0]] ?? 'true') === 'true') style="width:18px;height:18px;accent-color:var(--gold-600);">
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="adm-settings-save-wrap">
                <button type="submit" class="btn btn-gold adm-settings-save adm-settings-save--bottom">Save settings</button>
            </div>
        </form>
    @elseif($tab === 'users')
        <div class="adm-card adm-settings-card" style="margin-bottom:18px;">
            <h3 style="font-size:17px;font-weight:600;margin-bottom:14px;">Invite sub-admin / admin user</h3>
            <form action="{{ route('admin.users.store') }}" method="POST" class="adm-settings-form" style="display:grid;gap:12px;">
                @csrf
                <div class="adm-settings-grid-2">
                    <input name="name" placeholder="Full name" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                    <input name="email" type="email" placeholder="Email" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </div>
                <div class="adm-settings-grid-2">
                    <select name="role" required data-adm-user-role style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                        @foreach(['Owner', 'Manager', 'Sub-admin', 'Staff'] as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                    <input name="password" type="password" placeholder="Temporary password" required minlength="8" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </div>
                <div class="adm-settings-perms" data-adm-user-permissions>
                    <p style="grid-column:1/-1;margin:0;font-size:12px;color:var(--muted);">Owners always have full access. For other roles, choose which admin sections they can open.</p>
                    @foreach($areas as $area)
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--sand);">
                            <input type="checkbox" name="permissions[]" value="{{ $area }}" @checked(in_array($area, old('permissions', ['dashboard', 'orders', 'reservations', 'profile']), true)) style="accent-color:var(--gold-600);">
                            {{ ucfirst($area) }}
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-gold btn-sm" style="justify-self:start;">Invite user</button>
            </form>
        </div>

        @if($editUser)
            <form action="{{ route('admin.users.update', $editUser) }}" method="POST" class="adm-card adm-settings-form adm-settings-card" style="margin-bottom:18px;">
                @csrf @method('PATCH')
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <h3 style="font-size:17px;font-weight:600;">Edit user: {{ $editUser->name }}</h3>
                    <a href="{{ route('admin.settings.index', ['tab' => 'users']) }}" class="btn btn-ghost btn-sm" style="text-decoration:none;">Close</a>
                </div>
                <div class="adm-settings-grid-2">
                    <input name="name" value="{{ $editUser->name }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                    <input name="email" type="email" value="{{ $editUser->email }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                    <select name="role" data-adm-user-role style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                        @foreach(['Owner', 'Manager', 'Sub-admin', 'Staff'] as $role)
                            <option value="{{ $role }}" @selected($editUser->role === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                    <select name="status" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                        <option value="active" @selected($editUser->status === 'active')>Active</option>
                        <option value="invited" @selected($editUser->status === 'invited')>Invited</option>
                        <option value="inactive" @selected($editUser->status === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="adm-settings-perms" style="margin-top:10px;" data-adm-user-permissions>
                    <p style="grid-column:1/-1;margin:0;font-size:12px;color:var(--muted);">Owners always have full access. For other roles, choose which admin sections they can open.</p>
                    @foreach($areas as $area)
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--sand);">
                            <input type="checkbox" name="permissions[]" value="{{ $area }}" @checked(in_array($area, old('permissions', $editUser->admin_permissions ?? []), true)) style="accent-color:var(--gold-600);">
                            {{ ucfirst($area) }}
                        </label>
                    @endforeach
                </div>
                <input name="password" type="password" placeholder="Optional new password" minlength="8" style="margin-top:10px;background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                <button type="submit" class="btn btn-gold btn-sm" style="margin-top:12px;justify-self:start;">Save user</button>
            </form>
        @endif

        <div class="adm-card" style="padding:8px;">
            <div class="adm-table-wrap">
                <table class="adm-table">
                    <thead>
                    <tr><th>Member</th><th>Role</th><th>Email</th><th>Status</th><th>Permissions</th><th class="right"></th></tr>
                    </thead>
                    <tbody>
                    @foreach($adminUsers as $u)
                        <tr>
                            <td style="font-weight:600;color:var(--cream)">{{ $u->name }}</td>
                            <td>{{ $u->role }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ ucfirst($u->status) }}</td>
                            <td style="font-size:12px;color:var(--sand)">{{ implode(', ', $u->admin_permissions ?? []) }}</td>
                            <td class="right">
                                <div style="display:flex;justify-content:flex-end;gap:8px;">
                                    <a href="{{ route('admin.settings.index', ['tab' => 'users', 'edit_user' => $u->id]) }}" class="btn btn-ghost btn-sm" style="text-decoration:none;">Manage</a>
                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" data-confirm="Remove this user?">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="width:34px;height:34px;border-radius:9px;background:transparent;border:1px solid var(--spice-600);color:var(--spice-400);cursor:pointer;display:grid;place-items:center;" aria-label="Delete user"><x-icon name="trash" :size="16"/></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="adm-profile-grid">
            <form action="{{ route('admin.profile.update') }}" method="POST" class="adm-settings-form">
                @csrf @method('PUT')
                <div class="adm-card adm-settings-card" style="height:100%;">
                    <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Profile details</h3>
                    <div class="adm-settings-fields">
                        <input name="name" value="{{ old('name', $profileUser->name) }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                        <input name="email" type="email" value="{{ old('email', $profileUser->email) }}" required style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                    </div>
                    <button type="submit" class="btn btn-gold" style="margin-top:16px;">Save profile</button>
                </div>
            </form>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="adm-settings-form">
                @csrf @method('PUT')
                <div class="adm-card adm-settings-card" style="height:100%;">
                    <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Change password</h3>
                    <div class="adm-settings-fields">
                        <input name="current_password" type="password" required placeholder="Current password" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                        <input name="password" type="password" required minlength="8" placeholder="New password" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                        <input name="password_confirmation" type="password" required minlength="8" placeholder="Confirm new password" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);">
                    </div>
                    <button type="submit" class="btn btn-gold" style="margin-top:16px;">Update password</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
