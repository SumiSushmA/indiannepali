<?php

namespace App\Support;

use App\Data\CateringMenu;

class CateringCart
{
    public const SESSION_KEY = 'catering_cart';

    public const PER_PERSON_ID = 'catering-per-person';

    /** @return array{per_person: ?array, trays: array<string, int>} */
    public static function all(): array
    {
        return session(self::SESSION_KEY, [
            'per_person' => null,
            'trays' => [],
        ]);
    }

    public static function save(array $cart): void
    {
        session([self::SESSION_KEY => $cart]);
    }

    public static function setPerPerson(int $guestCount, array $selections): void
    {
        $cart = self::all();
        $cart['per_person'] = [
            'guest_count' => $guestCount,
            'selections' => $selections,
        ];
        self::save($cart);
    }

    public static function addTray(string $slug, int $qty = 1): void
    {
        $cart = self::all();
        $cart['trays'][$slug] = ($cart['trays'][$slug] ?? 0) + max(1, $qty);
        self::save($cart);
    }

    public static function updateTray(string $slug, int $qty): void
    {
        $cart = self::all();

        if ($qty <= 0) {
            unset($cart['trays'][$slug]);
        } else {
            $cart['trays'][$slug] = $qty;
        }

        self::save($cart);
    }

    public static function removePerPerson(): void
    {
        $cart = self::all();
        $cart['per_person'] = null;
        self::save($cart);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public static function itemCount(): int
    {
        $cart = self::all();
        $count = 0;

        if ($cart['per_person']) {
            $count += 1;
        }

        foreach ($cart['trays'] as $qty) {
            $count += (int) $qty;
        }

        return $count;
    }

    /** @return array<int, array<string, mixed>> */
    public static function lines(): array
    {
        $cart = self::all();
        $lines = [];

        if ($cart['per_person']) {
            $guests = (int) $cart['per_person']['guest_count'];
            $selections = $cart['per_person']['selections'] ?? [];
            $summary = collect($selections)
                ->flatMap(fn ($items) => $items)
                ->take(6)
                ->implode(', ');

            if (count(collect($selections)->flatMap(fn ($items) => $items)) > 6) {
                $summary .= '…';
            }

            $unitPrice = CateringMenu::perPersonUnitPrice($selections);

            $lines[] = [
                'id' => self::PER_PERSON_ID,
                'name' => 'Catering Menu ('.$guests.' guests)',
                'price' => $unitPrice,
                'qty' => $guests,
                'desc' => $summary ?: 'Custom catering selections',
                'img' => 'catering spread',
                'catering' => true,
                'catering_type' => 'per_person',
                'selections' => $selections,
            ];
        }

        foreach ($cart['trays'] as $slug => $qty) {
            $tray = CateringMenu::tray($slug);
            if (! $tray || $qty < 1) {
                continue;
            }

            $lines[] = [
                'id' => 'catering-tray:'.$slug,
                'name' => $tray['name'],
                'price' => $tray['price'],
                'qty' => (int) $qty,
                'desc' => $tray['serves'],
                'img' => $tray['name'],
                'catering' => true,
                'catering_type' => 'tray',
            ];
        }

        return $lines;
    }

    public static function subtotal(): float
    {
        return round(collect(self::lines())->sum(fn ($line) => $line['price'] * $line['qty']), 2);
    }

    public static function isCateringLine(string $id): bool
    {
        return $id === self::PER_PERSON_ID || str_starts_with($id, 'catering-tray:');
    }
}
