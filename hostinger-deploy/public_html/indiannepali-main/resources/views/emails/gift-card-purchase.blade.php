@php
$restaurant = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gift card from {{ $restaurant }}</title>
</head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#222;max-width:560px;margin:0 auto;padding:24px">
    <h2 style="margin:0 0 8px">{{ $restaurant }}</h2>
    <p style="margin:0 0 20px;color:#666;font-size:14px">Gift card</p>

    <p style="margin:0 0 12px">Hi {{ $card->recipient_name }},</p>
    <p style="margin:0 0 16px">
        {{ $card->sender_name }} sent you a <strong>${{ number_format($card->face_value, 0) }}</strong> gift card
        ({{ $design->name }} design).
    </p>

    @if($card->message)
        <div style="background:#f5f5f5;border-radius:10px;padding:16px 18px;margin:20px 0;font-style:italic;white-space:pre-wrap">“{{ $card->message }}”</div>
    @endif

    <div style="background:#faf3eb;border:2px dashed #c9922a;border-radius:12px;padding:20px 24px;margin:24px 0;text-align:center">
        <div style="font-size:12px;letter-spacing:.12em;text-transform:uppercase;color:#666;margin-bottom:8px">Your gift card code</div>
        <div style="font-family:Georgia,serif;font-size:28px;font-weight:700;letter-spacing:.06em;color:#3a2810">{{ $card->code }}</div>
        <div style="font-size:14px;color:#666;margin-top:10px">Balance: ${{ number_format($card->balance, 2) }}</div>
    </div>

    <p style="margin:0 0 12px">Enter this code at checkout on our website or show it when you visit us.</p>
    <p style="color:#666;font-size:14px;margin:0">Never expires · Questions? Call {{ $site['phone'] ?? '(206) 397-3211' }}</p>

    @include('emails.partials.footer', ['recipientEmail' => $recipientEmail ?? null])
</body>
</html>
