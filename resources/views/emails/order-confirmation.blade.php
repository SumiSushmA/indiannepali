@php
$restaurant = $site['restaurant_name'] ?? 'Indian Nepali Kitchen';
$mode = ucfirst($order->fulfillment_type);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order {{ $order->order_number }}</title>
</head>
<body style="font-family:Arial,sans-serif;line-height:1.6;color:#222;max-width:560px;margin:0 auto;padding:24px">
    <h2 style="margin:0 0 8px">{{ $restaurant }}</h2>
    <p style="margin:0 0 20px;color:#666;font-size:14px">Order confirmation</p>

    <p style="margin:0 0 12px">Hi {{ $order->customer_name }},</p>
    <p style="margin:0 0 20px">Thanks for your order! We’ve received it and the kitchen is getting started.</p>

    <div style="background:#f5f5f5;border-radius:12px;padding:18px 20px;margin:20px 0">
        <div style="font-size:13px;color:#666;margin-bottom:6px">Order number</div>
        <div style="font-size:22px;font-weight:700">{{ $order->order_number }}</div>
        <div style="margin-top:14px;font-size:14px"><strong>{{ $mode }}</strong> · Estimated 30–60 minutes</div>
        @if($order->fulfillment_type === 'delivery' && $order->address)
            <div style="margin-top:8px;font-size:14px;color:#444">{{ $order->address }}</div>
        @endif
    </div>

    <table style="width:100%;border-collapse:collapse;font-size:14px;margin:16px 0">
        @foreach($order->items as $item)
            <tr>
                <td style="padding:8px 0;border-bottom:1px solid #eee">{{ $item->quantity }}× {{ $item->item_name }}</td>
                <td style="padding:8px 0;border-bottom:1px solid #eee;text-align:right">${{ number_format($item->line_total, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="padding:10px 0;color:#666">Subtotal</td>
            <td style="padding:10px 0;text-align:right">${{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td style="padding:4px 0;color:#666">Tax</td>
            <td style="padding:4px 0;text-align:right">${{ number_format($order->tax, 2) }}</td>
        </tr>
        @if($order->delivery_fee > 0)
            <tr>
                <td style="padding:4px 0;color:#666">Delivery</td>
                <td style="padding:4px 0;text-align:right">${{ number_format($order->delivery_fee, 2) }}</td>
            </tr>
        @endif
        @if($order->tip > 0)
            <tr>
                <td style="padding:4px 0;color:#666">Tip</td>
                <td style="padding:4px 0;text-align:right">${{ number_format($order->tip, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td style="padding:12px 0;font-weight:700">Total</td>
            <td style="padding:12px 0;text-align:right;font-weight:700;font-size:18px">${{ number_format($order->total, 2) }}</td>
        </tr>
    </table>

    @if($order->delivery_notes)
        <p style="font-size:14px;color:#444"><strong>Notes:</strong> {{ $order->delivery_notes }}</p>
    @endif

    <p style="color:#666;font-size:14px;margin-top:24px">Questions? Call {{ $site['phone'] ?? '(206) 397-3211' }} or reply to this email.</p>

    @include('emails.partials.footer', ['recipientEmail' => $recipientEmail ?? $order->customer_email ?? null])
</body>
</html>
