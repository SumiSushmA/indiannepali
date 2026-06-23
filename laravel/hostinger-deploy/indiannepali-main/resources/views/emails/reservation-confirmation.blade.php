@php
$restaurant = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';
$date = $reservation->reserved_date->format('l, F j, Y');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation {{ $reservation->reference }}</title>
</head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#222;max-width:560px;margin:0 auto;padding:24px">
    <h2 style="margin:0 0 8px">{{ $restaurant }}</h2>
    <p style="margin:0 0 20px;color:#666;font-size:14px">Table reservation confirmed</p>

    <p style="margin:0 0 12px">Hi {{ $reservation->customer_name }},</p>
    <p style="margin:0 0 20px">Your table is reserved. We look forward to welcoming you!</p>

    <div style="background:#f5f5f5;border-radius:12px;padding:18px 20px;margin:20px 0">
        <div style="font-size:13px;color:#666;margin-bottom:6px">Confirmation</div>
        <div style="font-size:22px;font-weight:700">{{ $reservation->reference }}</div>
        <div style="margin-top:14px;font-size:15px"><strong>{{ $date }}</strong> at {{ $reservation->reserved_time }}</div>
        <div style="margin-top:8px;font-size:14px">Party of {{ $reservation->party_size }}</div>
        @if($reservation->occasion && $reservation->occasion !== '—')
            <div style="margin-top:8px;font-size:14px;color:#444">Occasion: {{ $reservation->occasion }}</div>
        @endif
    </div>

    <p style="font-size:14px;color:#444">{{ $site['address'] ?? '13754 Aurora Ave N, Suite D, Seattle, WA' }}</p>

    @if($reservation->notes)
        <p style="font-size:14px;color:#444"><strong>Notes:</strong> {{ $reservation->notes }}</p>
    @endif

    <p style="color:#666;font-size:14px;margin-top:24px">Need to change your reservation? Call {{ $site['phone'] ?? '(206) 397-3211' }}.</p>

    @include('emails.partials.footer', ['recipientEmail' => $recipientEmail ?? $reservation->customer_email ?? null])
</body>
</html>
