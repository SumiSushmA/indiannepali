<?php

namespace App\Services;

use App\Models\AboutStat;
use App\Models\AboutStory;
use App\Models\AboutValue;
use App\Models\CateringPackage;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\GiftAmount;
use App\Models\GiftCardDesign;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Services\Toast\ToastMenuCatalog;
use App\Support\StockImages;

class RestaurantData
{
    public static function getMenu(): array
    {
        $categories = MenuCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (MenuCategory $c) => [
                'id' => $c->slug,
                'name' => $c->name,
                'tag' => $c->tag,
                'desc' => $c->description,
            ])
            ->all();

        $items = MenuItem::query()
            ->with('category')
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (MenuItem $item) => self::withToastMenu($item))
            ->all();

        return ['categories' => $categories, 'items' => $items];
    }

    public static function menu(): array
    {
        return self::getMenu();
    }

    public static function findItem(string $slug): ?array
    {
        $item = MenuItem::query()
            ->with('category')
            ->where('slug', $slug)
            ->where('is_available', true)
            ->first();

        return $item ? self::withToastMenu($item) : null;
    }

    public static function popularItems(int $limit = 6): array
    {
        return MenuItem::query()
            ->with('category')
            ->where('is_available', true)
            ->where('is_popular', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(fn (MenuItem $item) => self::withToastMenu($item))
            ->all();
    }

    private static function withToastMenu(MenuItem $item): array
    {
        $legacy = $item->toLegacy();
        $toastItem = app(ToastMenuCatalog::class)->findForMenuItem($item);

        if (! $toastItem) {
            return $legacy;
        }

        $legacy['name'] = $toastItem['name'];
        $legacy['toast_pos_id'] = $toastItem['guid'];

        if ($toastItem['price'] !== null) {
            $legacy['price'] = $toastItem['price'];
        }

        if (filled($toastItem['description'])) {
            $legacy['desc'] = $toastItem['description'];
        }

        return $legacy;
    }

    public static function promos(): array
    {
        return Promo::query()
            ->visible()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Promo $p) => $p->toCustomerArray())
            ->all();
    }

    public static function galleryPreview(int $limit = 5): array
    {
        return GalleryImage::query()
            ->with('category')
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(fn (GalleryImage $img) => [
                'label' => $img->caption,
                'cat' => $img->category?->name ?? '',
                'url' => StockImages::resolve($img->caption, $img->image_path),
            ])
            ->all();
    }

    public static function gallery(): array
    {
        return self::galleryPreview(12);
    }

    public static function getGalleryCategories(): array
    {
        return GalleryCategory::query()
            ->with(['images' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get()
            ->map(fn (GalleryCategory $cat) => [
                'id' => $cat->slug,
                'name' => $cat->name,
                'items' => $cat->images->map(fn (GalleryImage $img) => [
                    'label' => $img->caption,
                    'url' => StockImages::resolve($img->caption, $img->image_path),
                ])->all(),
            ])
            ->all();
    }

    public static function galleryCats(): array
    {
        return self::getGalleryCategories();
    }

    public static function reviews(): array
    {
        return Review::query()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Review $r) => [
                'name' => $r->author_name,
                'stars' => $r->stars,
                'text' => $r->body,
                'tag' => $r->source_tag,
            ])
            ->all();
    }

    public static function about(): array
    {
        return [
            'hero_image' => StockImages::resolve('Founders at the pass', Setting::get('about_hero_image_path')),
            'story' => AboutStory::query()->orderBy('sort_order')->pluck('paragraph')->all(),
            'values' => AboutValue::query()->orderBy('sort_order')->get()->map(fn ($v) => [
                'icon' => $v->icon,
                'title' => $v->title,
                'text' => $v->body,
            ])->all(),
            'stats' => AboutStat::query()->orderBy('sort_order')->get()->map(fn ($s) => [$s->value, $s->label])->all(),
            'team' => TeamMember::query()->where('is_published', true)->orderBy('sort_order')->get()->map(fn ($t) => [
                'name' => $t->name,
                'role' => $t->role,
                'tag' => $t->tag,
                'image' => StockImages::resolve($t->name, $t->image_path),
            ])->all(),
        ];
    }

    public static function giftDesigns(): array
    {
        return GiftCardDesign::query()
            ->where('is_active', true)
            ->get()
            ->map(fn (GiftCardDesign $d) => $d->toLegacy())
            ->all();
    }

    public static function giftAmounts(): array
    {
        return GiftAmount::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('amount')
            ->map(fn ($amount) => (int) $amount)
            ->all();
    }

    public static function cateringPackages(): array
    {
        return CateringPackage::query()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (CateringPackage $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'range' => $p->guest_range,
                'price' => $p->price_label,
                'items' => $p->items ?? [],
                'popular' => $p->is_popular,
            ])
            ->all();
    }
}
