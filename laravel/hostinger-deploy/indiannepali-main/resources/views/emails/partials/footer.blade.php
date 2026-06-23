@php
$recipient = $recipientEmail ?? $email ?? null;
$unsubscribeUrl = $recipient
    ? \App\Support\EmailPreferences::unsubscribeUrl($recipient)
    : route('unsubscribe');
@endphp
<div style="margin-top:32px;padding-top:20px;border-top:1px solid #e5e5e5;font-size:12px;color:#888;line-height:1.6">
    <p style="margin:0 0 8px">{{ $site['restaurant_name'] ?? 'Indian-Nepali Kitchen' }} · {{ $site['address'] ?? 'Seattle, WA' }}</p>
    <p style="margin:0">
        Don’t want marketing emails from us?
        <a href="{{ $unsubscribeUrl }}" style="color:#963042;text-decoration:underline">Unsubscribe</a>
    </p>
</div>
