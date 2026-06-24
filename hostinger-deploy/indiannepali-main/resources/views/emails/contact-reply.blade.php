@php
$restaurant = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reply from {{ $restaurant }}</title>
</head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#222;max-width:560px;margin:0 auto;padding:24px">
    <h2 style="margin:0 0 12px">{{ $restaurant }}</h2>
    <p style="margin:0 0 16px">Hi {{ $inquiry->customer_name }},</p>
    <p style="margin:0 0 16px">Thanks for reaching out. Here is our reply to your message about <strong>{{ $inquiry->subject }}</strong>:</p>
    <div style="background:#f5f5f5;border-radius:10px;padding:16px 18px;margin:20px 0;white-space:pre-wrap">{{ $inquiry->admin_reply }}</div>
    <p style="color:#666;font-size:14px;margin:0">You can also view this reply by signing in to your account on our website.</p>

    @include('emails.partials.footer', ['recipientEmail' => $recipientEmail ?? $inquiry->customer_email ?? null])
</body>
</html>
