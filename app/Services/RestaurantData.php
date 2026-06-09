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
use App\Models\TeamMember;

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
            ->map(fn (MenuItem $item) => $item->toLegacy())
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

        return $item?->toLegacy();
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
            ->map(fn (MenuItem $item) => $item->toLegacy())
            ->all();
    }

    public static function promos(): array
    {
        return Promo::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Promo $p) => [
                'id' => $p->slug,
                'badge' => $p->badge,
                'title' => $p->title,
                'detail' => $p->detail,
                'price' => $p->price_label,
                'accent' => $p->accent,
            ])
            ->all();
    }

    public static function gallery(): array
    {
        return GalleryImage::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->limit(12)
            ->pluck('caption')
            ->all();
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
                'items' => $cat->images->pluck('caption')->all(),
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
            ])->all(),
        ];
    }

    public static function giftDesigns(): array
    {
        return GiftCardDesign::query()
            ->where('is_active', true)
            ->get()
            ->map(fn (GiftCardDesign $d) => [
                'id' => $d->slug,
                'name' => $d->name,
                'sub' => $d->subtitle,
                'accent' => $d->accent,
            ])
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
                'name' => $p->name,
                'range' => $p->guest_range,
                'price' => $p->price_label,
                'items' => $p->items ?? [],
                'popular' => $p->is_popular,
            ])
            ->all();
    }
}
