<?php

namespace App\Data;

/**
 * Catering catalog aligned with indiannepalikitchen.com Square Online.
 * Per-person builder ($5/guest base + item prices, 20 minimum) + tray menu.
 */
class CateringMenu
{
    public const MIN_GUESTS = 20;

    public const PER_PERSON_PRICE = 5.00;

    public const PREP_TIME_DAYS = 2;

    public static function perPerson(): array
    {
        return [
            'title' => 'Catering Menu (20 people required)',
            'description' => 'Our catering menu offers a variety of delectable dishes to choose from, with a base price of $5.00 per person. Each dish shows its per-person price — add your selections below. A minimum of 20 people is required for all catering orders. If you order for less than 20 people, your order won\'t be fulfilled and your money will be refunded.',
            'price_label' => '$5.00 / person base',
            'prep_notice' => 'This order has a prep time of '.self::PREP_TIME_DAYS.' calendar days. Availability may change when your order is confirmed.',
            'groups' => [
                [
                    'id' => 'included',
                    'label' => 'Included (Per person)',
                    'optional' => true,
                    'options' => [
                        self::includedOption('Rice'),
                        self::includedOption('Salad'),
                        self::includedOption('Raita'),
                    ],
                ],
                [
                    'id' => 'appetizers',
                    'label' => 'Appetizers',
                    'options' => [
                        self::option('Vegetable Samosas', 6.95),
                        self::option('Vegetable Pakora', 6.50),
                        self::option('Mixed Appetizers', 8.99),
                        self::option('Samosa Chaat', 7.95),
                        self::option('Papadum', 1.99),
                    ],
                ],
                [
                    'id' => 'momos',
                    'label' => 'Momos (Dumplings)',
                    'options' => [
                        self::option('Steamed Momo', 11.99),
                        self::option('Fried Momo', 13.99),
                        self::option('Jhol Momo', 13.99),
                        self::option('Chilli Momo', 13.99),
                        self::option('Sandheko Momo', 13.99),
                        self::option('Tandoori Momo', 19.99),
                        self::option('Combo Momo', 14.99),
                    ],
                ],
                [
                    'id' => 'chicken',
                    'label' => 'Chicken Entrees',
                    'options' => [
                        self::option('Butter Curry', 17.95),
                        self::option('Tikka Masala', 17.95),
                        self::option('Chicken Curry Nepali', 17.95),
                        self::option('Korma', 17.95),
                        self::option('Karahi', 17.95),
                        self::option('Jalfrezi', 17.95),
                        self::option('Vindaloo', 17.95),
                    ],
                ],
                [
                    'id' => 'lamb',
                    'label' => 'Lamb & Goat Entrees',
                    'options' => [
                        self::option('Lamb Butter Curry', 17.95),
                        self::option('Goat Curry', 19.95),
                        self::option('Lamb Kofta', 17.95),
                        self::option('Lamb Sekuwa', 17.99),
                        self::option('Lamb Biryani', 21.50),
                    ],
                ],
                [
                    'id' => 'nepali',
                    'label' => 'Nepali Special Entrees',
                    'options' => [
                        self::option('Gundruk', 14.50),
                        self::option('Aloo Rayoko Saag', 14.50),
                        self::option('Aloo Bodi Tama', 14.50),
                        self::option('Veg Dal Bhat', 14.50),
                        self::option('Masu Chiura', 17.99),
                    ],
                ],
                [
                    'id' => 'vegetarian',
                    'label' => 'Vegetarian Entrees',
                    'options' => [
                        self::option('Dal Makhani', 14.50),
                        self::option('Dal Tadka', 14.50),
                        self::option('Chana Masala', 14.50),
                        self::option('Aloo Gobi', 14.95),
                        self::option('Matar Paneer', 14.95),
                        self::option('Malai Kofta', 14.95),
                        self::option('Saag (Spinach)', 17.95),
                    ],
                ],
                [
                    'id' => 'tandoori',
                    'label' => 'Tandoori Specialties',
                    'options' => [
                        self::option('Tandoori Chicken', 18.50),
                        self::option('Chicken Sekuwa', 17.99),
                        self::option('Seekh Kebab', 18.99),
                        self::option('Tandoori Platter', 23.95),
                    ],
                ],
                [
                    'id' => 'rice_bread',
                    'label' => 'Biryani, Rice & Breads',
                    'options' => [
                        self::option('Chicken Biryani', 18.95),
                        self::option('Lamb Biryani', 21.50),
                        self::option('Vegetable Biryani', 15.95),
                        self::option('Basmati Rice', 2.50),
                        self::option('Garlic Naan', 3.95),
                        self::option('Garlic Basil Naan', 4.50),
                        self::option('Plain Naan', 2.95),
                    ],
                ],
                [
                    'id' => 'desserts',
                    'label' => 'Desserts',
                    'options' => [
                        self::option('Gulab Jamun', 4.50),
                        self::option('Ras Malai', 4.50),
                        self::option('Kulfi', 2.99),
                        self::option('Rice Pudding', 4.50),
                    ],
                ],
            ],
        ];
    }

    /** @return array{name: string, price: float} */
    private static function option(string $name, float $price): array
    {
        return ['name' => $name, 'price' => $price];
    }

    /** @return array{name: string, price: float, included: true} */
    private static function includedOption(string $name): array
    {
        return ['name' => $name, 'price' => 0.0, 'included' => true];
    }

    /** @return array<string, array<string, array{name: string, price: float}>> */
    public static function optionIndex(): array
    {
        static $index = null;

        if ($index !== null) {
            return $index;
        }

        $index = [];

        foreach (self::perPerson()['groups'] as $group) {
            foreach ($group['options'] as $option) {
                $index[$group['id']][$option['name']] = $option;
            }
        }

        return $index;
    }

    public static function optionPrice(string $groupId, string $name): ?float
    {
        return self::optionIndex()[$groupId][$name]['price'] ?? null;
    }

    /** Per-guest price: base + sum of selected dish prices. */
    public static function perPersonUnitPrice(array $selections): float
    {
        $total = self::PER_PERSON_PRICE;
        $index = self::optionIndex();

        foreach ($selections as $groupId => $items) {
            foreach ($items as $name) {
                $total += $index[$groupId][$name]['price'] ?? 0;
            }
        }

        return round($total, 2);
    }

    public static function perPersonTotal(int $guestCount, array $selections): float
    {
        return round(self::perPersonUnitPrice($selections) * max(self::MIN_GUESTS, $guestCount), 2);
    }

    /** @return array<int, array{slug: string, name: string, description: string, price: float, serves: string}> */
    public static function trays(): array
    {
        return [
            [
                'slug' => 'tray-veg-samosas',
                'name' => 'Vegetable Samosas (Tray)',
                'description' => 'Crispy pastry filled with spiced potatoes and peas.',
                'price' => 30.00,
                'serves' => 'Serves 10',
            ],
            [
                'slug' => 'tray-mixed-appetizers',
                'name' => 'Mixed Appetizers (Tray)',
                'description' => 'Assorted pakora, samosa, and papadum.',
                'price' => 45.00,
                'serves' => 'Serves 10',
            ],
            [
                'slug' => 'tray-steamed-momo',
                'name' => 'Steamed Momo (Tray)',
                'description' => 'Hand-pleated dumplings with momo sauce.',
                'price' => 55.00,
                'serves' => 'Serves 10',
            ],
            [
                'slug' => 'tray-combo-momo',
                'name' => 'Combo Momo Feast (Tray)',
                'description' => 'Steamed, fried, sandheko, and chili momo.',
                'price' => 75.00,
                'serves' => 'Serves 10',
            ],
            [
                'slug' => 'tray-butter-chicken',
                'name' => 'Butter Curry — Chicken (Tray)',
                'description' => 'Creamy tomato curry with basmati rice.',
                'price' => 85.00,
                'serves' => 'Serves 8',
            ],
            [
                'slug' => 'tray-tikka-masala',
                'name' => 'Tikka Masala — Chicken (Tray)',
                'description' => 'Char-grilled chicken in spiced masala sauce.',
                'price' => 85.00,
                'serves' => 'Serves 8',
            ],
            [
                'slug' => 'tray-goat-curry',
                'name' => 'Goat Curry (Tray)',
                'description' => 'Slow-braised goat in Nepali spices with rice.',
                'price' => 95.00,
                'serves' => 'Serves 8',
            ],
            [
                'slug' => 'tray-veg-entree',
                'name' => 'Vegetarian Entree Combo (Tray)',
                'description' => 'Dal makhani, aloo gobi, and saag with rice.',
                'price' => 75.00,
                'serves' => 'Serves 8',
            ],
            [
                'slug' => 'tray-chicken-biryani',
                'name' => 'Chicken Biryani (Tray)',
                'description' => 'Fragrant basmati with spiced chicken.',
                'price' => 80.00,
                'serves' => 'Serves 8',
            ],
            [
                'slug' => 'tray-tandoori',
                'name' => 'Tandoori Platter (Tray)',
                'description' => 'Mixed tandoori chicken and seekh kebab.',
                'price' => 90.00,
                'serves' => 'Serves 8',
            ],
            [
                'slug' => 'tray-naan-basket',
                'name' => 'Naan Basket (Tray)',
                'description' => 'Garlic naan, plain naan, and garlic basil naan.',
                'price' => 35.00,
                'serves' => 'Serves 10',
            ],
            [
                'slug' => 'tray-gulab-jamun',
                'name' => 'Gulab Jamun (Tray)',
                'description' => 'Warm milk dumplings in rose-cardamom syrup.',
                'price' => 40.00,
                'serves' => 'Serves 10',
            ],
        ];
    }

    public static function tray(string $slug): ?array
    {
        foreach (self::trays() as $tray) {
            if ($tray['slug'] === $slug) {
                return $tray;
            }
        }

        return null;
    }
}
