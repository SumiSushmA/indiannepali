<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class StockImages
{
    /**
     * Local dish photos — each file used for matching menu / food labels only.
     *
     * @var array<string, string>
     */
    private const DISH_IMAGES = [
        'samosa chaat' => 'samosa-chaat.jpeg',
        'chicken chili' => 'chicken-chili.jpeg',
        'chicken chilli' => 'chicken-chili.jpeg',
        'lamb sekuwa' => 'lamb-sekuwa.jpeg',
        'taas' => 'lamb-sekuwa.jpeg',
        'chicken pakora' => 'chicken-pakora.jpeg',
        'combo momo' => 'combo-momo.jpeg',
        'combo momo (12 pcs)' => 'combo-momo.jpeg',
        'chilli momo' => 'chilli-momo.jpeg',
        'chili momo' => 'chilli-momo.jpeg',
        'tandoori chicken momos' => 'chilli-momo.jpeg',
        'jhol momo' => 'combo-momo.jpeg',
        'jhol (soup) momo' => 'combo-momo.jpeg',
        'chicken momo' => 'chicken-momo.jpeg',
        'momo (10 pcs)' => 'chicken-momo.jpeg',
        'aloo gobi' => 'aloo-gobi.jpeg',
        'green salad' => 'green-salad.jpeg',
        'fresh green salad' => 'green-salad.jpeg',
        'mango lassi' => 'mango-lassi.jpeg',
        'lassi' => 'lassi.jpeg',
        'korma' => 'korma.jpeg',
        'butter masala momo' => 'korma.jpeg',
        'shahi paneer' => 'shahi-paneer.jpeg',
        'shahi paneer / tofu' => 'shahi-paneer.jpeg',
        'house curry' => 'shahi-paneer.jpeg',
        'naan' => 'naan.jpeg',
        'garlic naan' => 'naan.jpeg',
        'garlic basil naan' => 'naan.jpeg',
        'goat curry' => 'goat-curry.jpeg',
        'goat curry (nepali)' => 'goat-curry.jpeg',
        'butter curry with lamb' => 'goat-curry.jpeg',
        'biryani lamb' => 'goat-curry.jpeg',
        'gundruk' => 'gundruk.jpeg',
        'dal makhani' => 'dal-makhani.jpeg',
        'daal soup' => 'dal-makhani.jpeg',
        'chana masala' => 'chana-masala.jpeg',
        'gulab jamun' => 'mango-lassi.jpeg',
    ];

    /**
     * Local photos for home marketing sections (public_html/images/).
     *
     * @var array<string, string>
     */
    private const SCENE_IMAGES = [
        'hero' => 'herosection.jpg',
        'clay oven favorites' => 'goat-curry.jpeg',
        'momo destination' => 'combo-momo.jpeg',
        'packed delivery' => 'chicken-momo.jpeg',
        'packed delivery bags' => 'chicken-momo.jpeg',
        'dining room' => 'gundruk.jpeg',
        'dining room on aurora ave' => 'gundruk.jpeg',
        'catering' => 'catering.jpg',
        'catering spread' => 'catering.jpg',
        'catering spread photo' => 'catering.jpg',
        'live momo station' => 'combo-momo.jpeg',
        'family-size trays' => 'goat-curry.jpeg',
        'office lunch setup' => 'dal-makhani.jpeg',
        'celebration feast' => 'chicken-momo.jpeg',
        'counter service' => 'naan.jpeg',
        'tandoor clay oven' => 'chicken-pakora.jpeg',
        'cozy black and red interior' => 'korma.jpeg',
        'family-friendly seating' => 'dal-makhani.jpeg',
        'dining spread' => 'dining-spread.jpeg',
        'founders at the pass' => 'dining-spread.jpeg',
        'our kitchen team' => 'goat-curry.jpeg',
        'front of house' => 'gundruk.jpeg',
        'combo momo feast' => 'combo-momo.jpeg',
        'free delivery on orders $40+' => 'chicken-momo.jpeg',
        'party of 6 — welcome drink on us' => 'mango-lassi.jpeg',
    ];

    /** @var array<string, string> */
    private const PROMO_SLUG_IMAGES = [
        'free-delivery-40' => 'chicken-momo.jpeg',
        'momo-combo' => 'combo-momo.jpeg',
        'party-welcome-drink' => 'mango-lassi.jpeg',
    ];

    /** @var array<string, string> */
    private const MENU_SLUG_IMAGES = [
        'samosa-chaat' => 'images/samosa-chaat.jpeg',
        'chicken-chili' => 'images/chicken-chili.jpeg',
        'lamb-sekuwa' => 'images/lamb-sekuwa.jpeg',
        'chicken-pakora' => 'images/chicken-pakora.jpeg',
        'momo-10' => 'images/chicken-momo.jpeg',
        'combo-momo' => 'images/combo-momo.jpeg',
        'jhol-momo' => 'images/combo-momo.jpeg',
        'chilli-momo' => 'images/chilli-momo.jpeg',
        'tandoori-momo' => 'images/chilli-momo.jpeg',
        'aloo-gobi' => 'images/aloo-gobi.jpeg',
        'green-salad' => 'images/green-salad.jpeg',
        'mango-lassi' => 'images/mango-lassi.jpeg',
        'lassi' => 'images/lassi.jpeg',
        'korma' => 'images/korma.jpeg',
        'shahi-paneer' => 'images/shahi-paneer.jpeg',
        'naan' => 'images/naan.jpeg',
        'garlic-naan' => 'images/naan.jpeg',
        'goat-curry-nepali' => 'images/goat-curry.jpeg',
        'gundruk' => 'images/gundruk.jpeg',
        'dal-makhani' => 'images/dal-makhani.jpeg',
        'chana-masala' => 'images/chana-masala.jpeg',
        'daal-soup' => 'images/dal-makhani.jpeg',
    ];

    public static function scene(?string $label): string
    {
        if (! $label) {
            return '';
        }

        $key = strtolower(trim($label));
        $filename = self::SCENE_IMAGES[$key] ?? null;

        return $filename ? self::asset($filename) : '';
    }

    public static function forLabel(?string $label): string
    {
        if (! $label) {
            return '';
        }

        $key = strtolower(trim($label));
        $filename = self::DISH_IMAGES[$key] ?? self::SCENE_IMAGES[$key] ?? null;

        return $filename ? self::asset($filename) : '';
    }

    public static function hero(): string
    {
        return self::asset(self::SCENE_IMAGES['hero']);
    }

    public static function resolve(?string $label, ?string $imagePath = null): string
    {
        if ($imagePath) {
            if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                return $imagePath;
            }

            if (str_starts_with($imagePath, 'images/') && is_file(public_path($imagePath))) {
                return asset($imagePath);
            }

            if (! str_starts_with($imagePath, 'images/') && Storage::exists($imagePath)) {
                return Storage::url($imagePath);
            }
        }

        return self::forLabel($label);
    }

    /** @return array<string, string> */
    public static function menuImageMapBySlug(): array
    {
        return self::MENU_SLUG_IMAGES;
    }

    /** @return array<string, string> */
    public static function galleryImageMapByCaption(): array
    {
        return [
            'Samosa Chaat' => 'images/samosa-chaat.jpeg',
            'Chicken Chili' => 'images/chicken-chili.jpeg',
            'Combo Momo' => 'images/combo-momo.jpeg',
            'Chilli Momo' => 'images/chilli-momo.jpeg',
            'Chicken Pakora' => 'images/chicken-pakora.jpeg',
            'Lamb Sekuwa' => 'images/lamb-sekuwa.jpeg',
            'Green Salad' => 'images/green-salad.jpeg',
            'Goat Curry' => 'images/goat-curry.jpeg',
            'Shahi Paneer' => 'images/shahi-paneer.jpeg',
            'Aloo Gobi' => 'images/aloo-gobi.jpeg',
            'Naan' => 'images/naan.jpeg',
            'Dal Makhani' => 'images/dal-makhani.jpeg',
            'Gundruk' => 'images/gundruk.jpeg',
            'Korma' => 'images/korma.jpeg',
            'Mango Lassi' => 'images/mango-lassi.jpeg',
            'Dining spread' => 'images/dining-spread.jpeg',
            'Chicken Momo' => 'images/chicken-momo.jpeg',
            'Chana Masala' => 'images/chana-masala.jpeg',
            'Veg sides' => 'images/veg-sides.jpeg',
            'Lassi' => 'images/lassi.jpeg',
            'Taas' => 'images/lamb-sekuwa.jpeg',
        ];
    }

    /** Image paths already shown in other homepage sections (hero, story, bands). */
    public static function homeReservedImagePaths(): array
    {
        return [
            'images/dining-spread.jpeg',
            'images/goat-curry.jpeg',
            'images/combo-momo.jpeg',
            'images/chicken-momo.jpeg',
            'images/gundruk.jpeg',
        ];
    }

    public static function promoImage(?string $slug, ?string $title = null, ?string $menuItemSlug = null): string
    {
        if ($slug && isset(self::PROMO_SLUG_IMAGES[$slug])) {
            return self::asset(self::PROMO_SLUG_IMAGES[$slug]);
        }

        if ($menuItemSlug && isset(self::MENU_SLUG_IMAGES[$menuItemSlug])) {
            return asset(self::MENU_SLUG_IMAGES[$menuItemSlug]);
        }

        return self::forLabel($title);
    }

    private static function asset(string $filename): string
    {
        return asset('images/'.$filename);
    }
}
