@extends('layouts.admin')

@section('content')
<div style="max-width:760px;">
    <div style="margin-bottom:26px;">
        <h1 style="font-size:30px;font-weight:600;">Settings</h1>
        <p style="color:var(--muted);font-size:14.5px;margin-top:6px;">Restaurant profile, ordering rules and notifications.</p>
    </div>

    @if(session('success'))
        <div class="adm-card" style="padding:14px 18px;margin-bottom:16px;border-color:var(--gold-700);color:var(--gold-400)">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf @method('PUT')

        <div class="adm-card" style="padding:22px;margin-bottom:16px;">
            <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Restaurant profile</h3>
            <div style="display:grid;gap:14px;">
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

        <div class="adm-card" style="padding:22px;margin-bottom:16px;">
            <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Social & legal links</h3>
            <div style="display:grid;gap:14px;">
                @foreach([
                    ['instagram_url', 'Instagram URL'],
                    ['facebook_url', 'Facebook URL'],
                    ['whatsapp_url', 'WhatsApp URL'],
                    ['privacy_url', 'Privacy policy URL'],
                    ['terms_url', 'Terms URL'],
                    ['accessibility_url', 'Accessibility URL'],
                ] as [$key, $label])
                <label style="display:flex;flex-direction:column;gap:6px;">
                    <span style="font-size:13px;color:var(--sand);font-weight:600;">{{ $label }}</span>
                    <input name="{{ $key }}" type="url" value="{{ $settings[$key] ?? '' }}" placeholder="https://…" style="background:var(--ink-800);border:1px solid var(--line);border-radius:10px;padding:12px 14px;color:var(--cream);font-family:var(--sans);">
                </label>
                @endforeach
            </div>
        </div>

        <div class="adm-card" style="padding:22px;margin-bottom:16px;">
            <h3 style="font-size:18px;font-weight:600;margin-bottom:16px;">Ordering & pricing</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
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

        <button type="submit" class="btn btn-gold">Save settings</button>
    </form>
</div>
@endsection
