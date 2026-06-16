<?php

namespace App\Data;

/**
 * Catering catalog aligned with indiannepalikitchen.com Square Online.
 * Per-person builder ($5/guest, 20 minimum) + tray menu.
 */
class CateringMenu
{
    public const MIN_GUESTS = 20;

    public const PER_PERSON_PRICE = 5.00;

    public static function perPerson(): array
    {
        return [
            'title' => 'Catering Menu (20 people required)',
            'description' => 'Our catering menu offers a variety of delectable dishes to choose from, with a base price of $5.00 per person. A minimum of 20 people is required for all catering orders. If you order for less than 20 people, your order won\'t be fulfilled and your money will be refunded.',
            'price_label' => '$5.00 / person',
            'groups' => [
                [
                    'id' => 'appetizers',
                    'label' => 'Appetizers',
                    'options' => ['Vegetable Samosas', 'Vegetable Pakora', 'Mixed Appetizers', 'Samosa Chaat', 'Papadum'],
                ],
                [
                    'id' => 'momos',
                    'label' => 'Momos (Dumplings)',
                    'options' => ['Steamed Momo', 'Fried Momo', 'Jhol Momo', 'Chilli Momo', 'Sandheko Momo', 'Tandoori Momo', 'Combo Momo'],
                ],
                [
                    'id' => 'chicken',
                    'label' => 'Chicken Entrees',
                    'options' => ['Butter Curry', 'Tikka Masala', 'Chicken Curry Nepali', 'Korma', 'Karahi', 'Jalfrezi', 'Vindaloo'],
                ],
                [
                    'id' => 'lamb',
                    'label' => 'Lamb & Goat Entrees',
                    'options' => ['Lamb Butter Curry', 'Goat Curry', 'Lamb Kofta', 'Lamb Sekuwa', 'Lamb Biryani'],
                ],
                [
                    'id' => 'nepali',
                    'label' => 'Nepali Special Entrees',
                    'options' => ['Gundruk', 'Aloo Rayoko Saag', 'Aloo Bodi Tama', 'Veg Dal Bhat', 'Masu Chiura'],
                ],
                [
                    'id' => 'vegetarian',
                    'label' => 'Vegetarian Entrees',
                    'options' => ['Dal Makhani', 'Dal Tadka', 'Chana Masala', 'Aloo Gobi', 'Matar Paneer', 'Malai Kofta', 'Saag (Spinach)'],
                ],
                [
                    'id' => 'tandoori',
                    'label' => 'Tandoori Specialties',
                    'options' => ['Tandoori Chicken', 'Chicken Sekuwa', 'Seekh Kebab', 'Tandoori Platter'],
                ],
                [
                    'id' => 'rice_bread',
                    'label' => 'Biryani, Rice & Breads',
                    'options' => ['Chicken Biryani', 'Lamb Biryani', 'Vegetable Biryani', 'Basmati Rice', 'Garlic Naan', 'Garlic Basil Naan', 'Plain Naan'],
                ],
                [
                    'id' => 'desserts',
                    'label' => 'Desserts',
                    'options' => ['Gulab Jamun', 'Ras Malai', 'Kulfi', 'Rice Pudding'],
                ],
            ],
        ];
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
