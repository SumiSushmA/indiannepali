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

    public static function getAnalytics(string $range = '7'): array
    {
        [$start, $end, $prevStart, $prevEnd, $bucketType, $bucketCount] = self::resolveDashboardRange($range);

        $orders = Order::query()->whereBetween('placed_at', [$start, $end])->get();
        $prevOrders = Order::query()->whereBetween('placed_at', [$prevStart, $prevEnd])->get();

        $revenueSeries = [];
        $revenueLabels = [];

        if ($bucketType === 'hour') {
            for ($h = 0; $h < 24; $h++) {
                $revenueLabels[] = sprintf('%02d', $h);
                $revenueSeries[] = (float) $orders
                    ->filter(fn ($o) => (int) $o->placed_at->format('G') === $h)
                    ->sum('total');
            }
        } else {
            for ($i = $bucketCount - 1; $i >= 0; $i--) {
                $day = now()->subDays($i)->startOfDay();
                $revenueLabels[] = $bucketCount <= 7 ? $day->format('D') : $day->format('M j');
                $revenueSeries[] = (float) $orders
                    ->filter(fn ($o) => $o->placed_at->isSameDay($day))
                    ->sum('total');
            }
        }

        $currentRevenue = (float) $orders->sum('total');
        $previousRevenue = (float) $prevOrders->sum('total');
        $revenueChange = $previousRevenue > 0
            ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1)
            : ($currentRevenue > 0 ? 100.0 : 0.0);

        $channelTotals = Order::query()
            ->whereBetween('placed_at', [$start, $end])
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

        $channelSplit = $channelTotals->isEmpty()
            ? [['label' => 'No orders yet', 'value' => 100, 'color' => 'var(--line)']]
            : $channelTotals->map(fn ($count, $label) => [
                'label' => $label,
                'value' => (int) round(($count / $totalOrders) * 100),
                'color' => $colors[$label] ?? 'var(--gold-600)',
            ])->values()->all();

        $topItems = Order::query()
            ->with('items')
            ->whereBetween('placed_at', [$start, $end])
            ->get()
            ->flatMap(fn ($o) => $o->items)
            ->groupBy('item_name')
            ->map(fn ($items, $name) => [
                'name' => $name,
                'sold' => $items->sum('quantity'),
                'rev' => (float) $items->sum('line_total'),
            ])
            ->sortByDesc('sold')
            ->take(5)
            ->values()
            ->all();

        $hourly = array_fill(0, 24, 0);
        Order::query()->whereBetween('placed_at', [now()->startOfDay(), now()->endOfDay()])->get()
            ->each(fn ($o) => $hourly[(int) $o->placed_at->format('G')]++);

        return [
            'revenue7' => $revenueSeries,
            'revenueDays' => $revenueLabels,
            'channelSplit' => $channelSplit,
            'topItems' => $topItems,
            'hourly' => $hourly,
            'revenueChange' => $revenueChange,
            'revenueUp' => $revenueChange >= 0,
            'chartTitle' => match ($range) {
                'today' => 'Revenue by hour',
                '30' => 'Revenue by day',
                default => 'Revenue by day',
            },
            'chartSubtitle' => match ($range) {
                'today' => 'Today · all channels',
                '30' => 'Last 30 days · all channels',
                default => 'Last 7 days · all channels',
            },
            'sparks' => [
                array_map(fn ($v) => max(1, (int) ($v / 500)), $revenueSeries),
                [3, 5, 2, 4, 6, 3, 5],
                [2, 4, 3, 5, 4, 3, 2],
                [4, 3, 5, 2, 4, 3, 5],
            ],
        ];
    }

    public static function getDashboardStats(string $range = '7'): array
    {
        [$start, $end] = self::resolveDashboardRange($range);

        $orders = Order::query()->whereBetween('placed_at', [$start, $end])->get();
        $revenue = (float) $orders->sum('total');
        $orderCount = $orders->count();
        $avgOrder = $orderCount > 0 ? $revenue / $orderCount : 0.0;

        $covers = (int) Reservation::query()
            ->whereBetween('reserved_date', [$start->toDateString(), $end->toDateString()])
            ->sum('party_size');

        $rangeLabel = match ($range) {
            'today' => 'today',
            '30' => 'last 30 days',
            default => 'last 7 days',
        };

        $periodLabel = match ($range) {
            'today' => 'Today',
            '30' => '30d',
            default => '7d',
        };

        return [
            'range' => $range,
            'periodLabel' => $periodLabel,
            'cards' => [
                ["Revenue ({$periodLabel})", '$'.number_format($revenue, $revenue >= 1000 ? 0 : 2), "{$orderCount} orders", 'dollar'],
                ["Orders ({$periodLabel})", (string) $orderCount, $rangeLabel, 'bag'],
                ['Covers booked', (string) $covers, 'party size total', 'cal'],
                ['Avg. order value', '$'.number_format($avgOrder, 2), 'per order', 'trend'],
            ],
        ];
    }

    /** @return array{0: \Illuminate\Support\Carbon, 1: \Illuminate\Support\Carbon, 2: \Illuminate\Support\Carbon, 3: \Illuminate\Support\Carbon, 4: string, 5: int} */
    private static function resolveDashboardRange(string $range): array
    {
        return match ($range) {
            'today' => [
                now()->startOfDay(),
                now()->endOfDay(),
                now()->subDay()->startOfDay(),
                now()->subDay()->endOfDay(),
                'hour',
                24,
            ],
            '30' => [
                now()->subDays(29)->startOfDay(),
                now()->endOfDay(),
                now()->subDays(59)->startOfDay(),
                now()->subDays(30)->endOfDay(),
                'day',
                30,
            ],
            default => [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay(),
                now()->subDays(13)->startOfDay(),
                now()->subDays(7)->endOfDay(),
                'day',
                7,
            ],
        };
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

    public static function customerContentSections(): array
    {
        return [
            'Hero subtext',
            'About story',
            'Home story title',
            'Home story text',
            'Delivery blurb',
            'Catering blurb',
            'Footer tagline',
        ];
    }

    public static function getContent(): array
    {
        return ContentBlock::query()
            ->whereIn('section', self::customerContentSections())
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
