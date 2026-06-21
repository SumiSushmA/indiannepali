<?php

namespace App\Services;

use App\Models\CateringInquiry;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Support\Collection;

class AdminNotifications
{
    public static function markAllRead(): void
    {
        session(['admin_notifications_cleared_at' => now()->timestamp]);
    }

    private static function clearedAfter(): int
    {
        return (int) session('admin_notifications_cleared_at', 0);
    }

    /** @return array{items: list<array<string, mixed>>, total: int} */
    public static function get(): array
    {
        $items = static::buildItems()
            ->filter(fn (array $item) => ($item['at'] ?? 0) > static::clearedAfter())
            ->sortByDesc('at')
            ->values();

        return [
            'items' => $items->take(15)->all(),
            'total' => $items->count(),
        ];
    }

    public static function actionableCount(): int
    {
        return static::get()['total'];
    }

    /** @return Collection<int, array<string, mixed>> */
    private static function buildItems(): Collection
    {
        return collect()
            ->merge(static::orderNotifications())
            ->merge(static::reservationNotifications())
            ->merge(static::cateringNotifications())
            ->merge(static::inquiryNotifications());
    }

    private static function orderNotifications(): Collection
    {
        return Order::query()
            ->whereIn('status', ['New', 'Preparing'])
            ->orderByDesc('placed_at')
            ->limit(8)
            ->get()
            ->map(function (Order $order) {
                $placedAt = $order->placed_at ?? $order->created_at;
                $isNew = $order->status === 'New';

                return [
                    'type' => 'order',
                    'icon' => $order->fulfillment_type === 'delivery' ? 'truck' : 'bag',
                    'tone' => $isNew ? 'gold' : 'blue',
                    'title' => ($isNew ? 'New order' : 'Order in progress').' · '.$order->order_number,
                    'message' => $order->customer_name.' · $'.number_format((float) $order->total, 0).' · '.ucfirst($order->fulfillment_type),
                    'url' => route('admin.orders.index', ['q' => $order->order_number, 'status' => $isNew ? 'New' : 'Preparing']),
                    'at' => $placedAt?->timestamp ?? 0,
                    'time' => $placedAt?->diffForHumans(short: true) ?? '—',
                ];
            });
    }

    private static function reservationNotifications(): Collection
    {
        return Reservation::query()
            ->where('status', 'Pending')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get()
            ->map(function (Reservation $reservation) {
                return [
                    'type' => 'reservation',
                    'icon' => 'cal',
                    'tone' => 'purple',
                    'title' => 'Reservation · '.$reservation->reference,
                    'message' => $reservation->customer_name.' · party of '.$reservation->party_size.' · '.$reservation->reserved_date->format('M j').' '.$reservation->reserved_time,
                    'url' => route('admin.reservations.index'),
                    'at' => $reservation->created_at?->timestamp ?? 0,
                    'time' => $reservation->created_at?->diffForHumans(short: true) ?? '—',
                ];
            });
    }

    private static function cateringNotifications(): Collection
    {
        return CateringInquiry::query()
            ->where('status', 'New')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get()
            ->map(function (CateringInquiry $inquiry) {
                return [
                    'type' => 'catering',
                    'icon' => 'box',
                    'tone' => 'red',
                    'title' => 'Catering inquiry · '.$inquiry->reference,
                    'message' => $inquiry->customer_name.' · '.$inquiry->guest_count.' guests · '.$inquiry->event_type,
                    'url' => route('admin.catering.index'),
                    'at' => $inquiry->created_at?->timestamp ?? 0,
                    'time' => $inquiry->created_at?->diffForHumans(short: true) ?? '—',
                ];
            });
    }

    private static function inquiryNotifications(): Collection
    {
        return ContactMessage::query()
            ->where('status', 'Unread')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get()
            ->map(function (ContactMessage $message) {
                return [
                    'type' => 'contact',
                    'icon' => 'mail',
                    'tone' => 'red',
                    'title' => 'Contact message · '.$message->reference,
                    'message' => $message->customer_name.' · '.$message->subject,
                    'url' => route('admin.inquiries.show', $message->reference),
                    'at' => $message->created_at?->timestamp ?? 0,
                    'time' => $message->created_at?->diffForHumans(short: true) ?? '—',
                ];
            });
    }
}
