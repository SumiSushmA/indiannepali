<?php

namespace App\Services;

use App\Models\CateringInquiry;
use App\Models\ContactMessage;
use App\Models\ContentBlock;
use App\Models\GiftCard;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\ToastSyncLog;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdminData
{
    public static function getOrderStatuses(): array
    {
        return ['New', 'Preparing', 'Ready', 'Out for delivery', 'Completed'];
    }

    public static function getOrders(): array
    {
        return Order::query()
            ->with('items')
            ->orderByDesc('placed_at')
            ->get()
            ->map(fn (Order $order) => $order->toLegacy())
            ->all();
    }

    public static function getResStatuses(): array
    {
        return ['Confirmed', 'Seated', 'Pending', 'Cancelled', 'Completed'];
    }

    public static function getReservations(): array
    {
        return Reservation::query()
            ->orderBy('reserved_date')
            ->orderBy('reserved_time')
            ->get()
            ->map(fn (Reservation $r) => $r->toLegacy())
            ->all();
    }

    public static function getCalCounts(): array
    {
        $counts = [];
        for ($d = 1; $d <= 30; $d++) {
            $counts[$d] = 0;
        }

        Reservation::query()
            ->whereBetween('reserved_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->get()
            ->each(function (Reservation $r) use (&$counts) {
                $day = (int) Carbon::parse($r->reserved_date)->format('j');
                $counts[$day] = ($counts[$day] ?? 0) + 1;
            });

        return $counts;
    }

    public static function getCatering(): array
    {
        return CateringInquiry::query()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (CateringInquiry $c) => $c->toLegacy())
            ->all();
    }

    public static function getContact(): array
    {
        return ContactMessage::query()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (ContactMessage $m) => $m->toLegacy())
            ->all();
    }

    public static function getUsers(): array
    {
        return User::query()
            ->orderBy('name')
            ->get()
            ->map(fn (User $u) => $u->toLegacy())
            ->all();
    }

    public static function getAnalytics(): array
    {
        $orders = Order::query()->where('placed_at', '>=', now()->subDays(7))->get();
        $revenue7 = [];
        $revenueDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->startOfDay();
            $revenue7[] = (int) $orders->filter(fn ($o) => $o->placed_at->isSameDay($day))->sum('total');
        }

        $channelTotals = Order::query()
            ->selectRaw('channel, count(*) as count')
            ->groupBy('channel')
            ->pluck('count', 'channel');

        $totalOrders = max(1, $channelTotals->sum());
        $colors = [
            'Website' => 'var(--gold-600)',
            'Toast POS' => 'var(--spice-600)',
            'Phone' => '#6f9b5c',
            'Third-party' => '#5f5446',
        ];

        $channelSplit = $channelTotals->map(fn ($count, $label) => [
            'label' => $label,
            'value' => (int) round(($count / $totalOrders) * 100),
            'color' => $colors[$label] ?? 'var(--gold-600)',
        ])->values()->all();

        $topItems = Order::query()
            ->with('items')
            ->where('placed_at', '>=', now()->subDays(7))
            ->get()
            ->flatMap(fn ($o) => $o->items)
            ->groupBy('item_name')
            ->map(fn ($items, $name) => [
                'name' => $name,
                'sold' => $items->sum('quantity'),
                'rev' => (int) $items->sum('line_total'),
            ])
            ->sortByDesc('sold')
            ->take(5)
            ->values()
            ->all();

        $hourly = array_fill(0, 24, 0);
        Order::query()->where('placed_at', '>=', now()->subDay())->get()
            ->each(fn ($o) => $hourly[(int) $o->placed_at->format('G')]++);

        return [
            'revenue7' => $revenue7,
            'revenueDays' => $revenueDays,
            'channelSplit' => $channelSplit,
            'topItems' => $topItems,
            'hourly' => $hourly,
            'sparks' => [
                array_map(fn ($v) => max(1, (int) ($v / 500)), $revenue7),
                [3, 5, 2, 4, 6, 3, 5],
                [2, 4, 3, 5, 4, 3, 2],
                [4, 3, 5, 2, 4, 3, 5],
            ],
        ];
    }

    public static function getToast(): array
    {
        $logs = ToastSyncLog::query()->orderByDesc('logged_at')->limit(5)->get();

        return [
            'connected' => (bool) Setting::get('toast_connected', true),
            'location' => Setting::get('toast_location', 'Riverside District · Loc #RD-4471'),
            'lastSync' => $logs->first()?->logged_at?->diffForHumans(short: true) ?? '—',
            'syncs' => [
                ['type' => 'Menu items', 'dir' => 'POS → Web', 'count' => MenuItem::count(), 'status' => 'Synced', 'time' => '2 min ago'],
                ['type' => 'Orders', 'dir' => 'Web → POS', 'count' => Order::count(), 'status' => 'Synced', 'time' => '2 min ago'],
                ['type' => 'Modifiers', 'dir' => 'POS → Web', 'count' => 38, 'status' => 'Synced', 'time' => '11 min ago'],
                ['type' => 'Inventory (86\'d items)', 'dir' => 'POS → Web', 'count' => MenuItem::where('is_available', false)->count(), 'status' => 'Synced', 'time' => '11 min ago'],
                ['type' => 'Gift cards', 'dir' => 'Two-way', 'count' => GiftCard::count(), 'status' => 'Synced', 'time' => '1 hr ago'],
                ['type' => 'Payouts', 'dir' => 'POS → Web', 'count' => 7, 'status' => 'Pending', 'time' => '—'],
            ],
            'log' => $logs->map(fn ($l) => [
                't' => $l->logged_at->format('H:i'),
                'm' => $l->message,
                'ok' => $l->is_success,
            ])->all(),
        ];
    }

    public static function getContent(): array
    {
        return ContentBlock::query()
            ->orderBy('section')
            ->get()
            ->map(fn (ContentBlock $b) => $b->toLegacy())
            ->all();
    }

    public static function getGiftCards(): array
    {
        return GiftCard::query()
            ->with('design')
            ->orderByDesc('issued_at')
            ->get()
            ->map(fn (GiftCard $g) => $g->toLegacy())
            ->all();
    }

    public static function getGiftStats(): array
    {
        $cards = GiftCard::query()->get();

        return [
            'sold' => '$'.number_format($cards->where('issued_at', '>=', now()->subDays(30))->sum('face_value'), 0),
            'outstanding' => '$'.number_format($cards->where('status', '!=', 'Redeemed')->sum('balance'), 0),
            'active' => $cards->where('status', '!=', 'Redeemed')->count(),
            'redeemed30' => '$'.number_format($cards->where('status', 'Redeemed')->where('updated_at', '>=', now()->subDays(30))->sum('face_value'), 0),
        ];
    }

    public static function getGiftSales(): array
    {
        $sales = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->startOfDay();
            $sales[] = (int) GiftCard::query()
                ->whereDate('issued_at', $day->toDateString())
                ->sum('face_value');
        }

        return $sales;
    }

    public static function getNavBadges(): array
    {
        return [
            'orders' => Order::where('status', 'New')->count(),
            'reservations' => Reservation::where('status', 'Pending')->count(),
            'catering' => CateringInquiry::where('status', 'New')->count(),
            'contact' => ContactMessage::where('status', 'Unread')->count(),
        ];
    }
}
