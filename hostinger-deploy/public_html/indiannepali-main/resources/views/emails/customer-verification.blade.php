@php
$restaurant = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';
$isReset = $purpose === \App\Models\CustomerVerificationCode::PURPOSE_PASSWORD_RESET;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $isReset ? 'Password reset' : 'Email verification' }}</title>
</head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#222;max-width:560px;margin:0 auto;padding:24px">
    <h2 style="margin:0 0 12px">{{ $restaurant }}</h2>
    <p style="margin:0 0 16px">
        @if($isReset)
            Use this code to reset your account password:
        @else
            Use this code to verify your email and finish creating your account:
        @endif
    </p>
    <p style="font-size:32px;font-weight:700;letter-spacing:.25em;margin:24px 0">{{ $code }}</p>
    <p style="color:#666;font-size:14px;margin:0">This code expires in 10 minutes. If you did not request this, you can ignore this email.</p>

    @include('emails.partials.footer', ['recipientEmail' => $recipientEmail ?? $email ?? null])
</body>
</html>
