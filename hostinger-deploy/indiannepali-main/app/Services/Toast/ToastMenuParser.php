<?php

namespace App\Services\Toast;

class ToastMenuParser
{
    /**
     * @return array<int, array{guid: string, name: string, price: ?float, description: ?string, available: bool}>
     */
    public function flattenMenus(mixed $payload): array
    {
        $menus = is_array($payload) ? ($payload['menus'] ?? $payload) : [];

        if (! is_array($menus)) {
            return [];
        }

        $items = [];

        foreach ($menus as $menu) {
            if (! is_array($menu)) {
                continue;
            }

            foreach ($menu['menuGroups'] ?? [] as $group) {
                $this->collectFromGroup($group, $items);
            }
        }

        return array_values($items);
    }

    /**
     * @param  array<string, mixed>  $group
     * @param  array<string, array{guid: string, name: string, price: ?float, description: ?string, available: bool}>  $items
     */
    private function collectFromGroup(array $group, array &$items): void
    {
        foreach ($group['menuItems'] ?? [] as $item) {
            if (! is_array($item)) {
                continue;
            }

            $parsed = $this->parseItem($item);

            if ($parsed !== null) {
                $items[$parsed['guid']] = $parsed;
            }
        }

        foreach ($group['menuGroups'] ?? [] as $childGroup) {
            if (is_array($childGroup)) {
                $this->collectFromGroup($childGroup, $items);
            }
        }
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array{guid: string, name: string, price: ?float, description: ?string, available: bool}|null
     */
    public function parseItem(array $item): ?array
    {
        $guid = $item['guid'] ?? null;
        $name = trim((string) ($item['name'] ?? ''));

        if (! filled($guid) || $name === '') {
            return null;
        }

        return [
            'guid' => (string) $guid,
            'name' => $name,
            'price' => $this->resolvePrice($item),
            'description' => filled($item['description'] ?? null) ? trim((string) $item['description']) : null,
            'available' => $this->isAvailable($item),
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     */
    public function resolvePrice(array $item): ?float
    {
        $strategy = (string) ($item['pricingStrategy'] ?? 'BASE_PRICE');
        $price = $item['price'] ?? null;

        if (in_array($strategy, ['BASE_PRICE', 'MENU_SPECIFIC_PRICE', 'TIME_SPECIFIC_PRICE'], true) && is_numeric($price)) {
            return round((float) $price, 2);
        }

        if ($strategy === 'SIZE_PRICE') {
            return $this->lowestRulePrice($item['pricingRules'] ?? []);
        }

        return is_numeric($price) ? round((float) $price, 2) : null;
    }

    /**
     * @param  array<string, mixed>  $rules
     */
    private function lowestRulePrice(array $rules): ?float
    {
        $prices = [];

        foreach ($rules as $rule) {
            if (! is_array($rule)) {
                continue;
            }

            if (is_numeric($rule['price'] ?? null)) {
                $prices[] = (float) $rule['price'];
            }

            foreach ($rule['sizes'] ?? [] as $size) {
                if (is_array($size) && is_numeric($size['price'] ?? null)) {
                    $prices[] = (float) $size['price'];
                }
            }
        }

        if ($prices === []) {
            return null;
        }

        return round(min($prices), 2);
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function isAvailable(array $item): bool
    {
        return ($item['outOfStock'] ?? false) !== true;
    }

    public static function normalizeName(string $name): string
    {
        $normalized = strtolower(trim($name));
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?? $normalized;
        $normalized = preg_replace('/[^\p{L}\p{N}\s]/u', '', $normalized) ?? $normalized;

        return trim($normalized);
    }
}
