<?php

namespace App\Services;

use App\Data\LiveSiteContent;
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

    /**
     * Popular dishes for homepage — at most one card per image file.
     *
     * @param  list<string>  $reservedPaths  Images used elsewhere on the homepage
     */
    public static function popularItemsForHome(array $reservedPaths, int $limit = 6): array
    {
        $used = array_fill_keys($reservedPaths, true);
        $items = [];

        $candidates = MenuItem::query()
            ->with('category')
            ->where('is_available', true)
            ->where('is_popular', true)
            ->orderBy('sort_order')
            ->limit(40)
            ->get();

        foreach ($candidates as $item) {
            $legacy = self::withToastMenu($item);
            $path = $legacy['image_path'] ?? null;

            if (! $path) {
                continue;
            }

            if (isset($used[$path])) {
                continue;
            }

            $used[$path] = true;
            $items[] = $legacy;

            if (count($items) >= $limit) {
                break;
            }
        }

        return $items;
    }

    public static function galleryPreview(int $limit = 5): array
    {
        return self::galleryPreviewExcluding([], $limit);
    }

    /**
     * Gallery strip for homepage — skips photos already shown above.
     *
     * @param  list<string>  $reservedPaths
     */
    public static function galleryPreviewForHome(array $reservedPaths, int $limit = 5): array
    {
        return self::galleryPreviewExcluding($reservedPaths, $limit);
    }

    /**
     * @param  list<string>  $reservedPaths
     */
    private static function galleryPreviewExcluding(array $reservedPaths, int $limit): array
    {
        $used = array_fill_keys($reservedPaths, true);
        $items = [];

        foreach (self::publishedGalleryImages() as $img) {
            $path = $img->image_path;
            if ($path && isset($used[$path])) {
                continue;
            }
            if ($path) {
                $used[$path] = true;
            }

            $items[] = [
                'label' => $img->caption,
                'cat' => $img->category?->name ?? '',
                'url' => StockImages::resolve($img->caption, $path),
            ];

            if (count($items) >= $limit) {
                break;
            }
        }

        return $items;
    }

    /** @return \Illuminate\Support\Collection<int, GalleryImage> */
    private static function publishedGalleryImages()
    {
        return GalleryImage::query()
            ->with('category')
            ->where('is_published', true)
            ->join('gallery_categories', 'gallery_images.gallery_category_id', '=', 'gallery_categories.id')
            ->orderBy('gallery_categories.sort_order')
            ->orderBy('gallery_images.sort_order')
            ->select('gallery_images.*')
            ->get();
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

    public static function gallery(): array
    {
        return self::galleryPreview(12);
    }

    public static function getGalleryCategories(): array
    {
        $usedPaths = [];

        return GalleryCategory::query()
            ->with(['images' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get()
            ->map(function (GalleryCategory $cat) use (&$usedPaths) {
                $items = $cat->images
                    ->filter(function (GalleryImage $img) use (&$usedPaths) {
                        $path = $img->image_path;
                        if (! $path || isset($usedPaths[$path])) {
                            return false;
                        }

                        $usedPaths[$path] = true;

                        return true;
                    })
                    ->map(fn (GalleryImage $img) => [
                        'label' => $img->caption,
                        'url' => StockImages::resolve($img->caption, $img->image_path),
                    ])
                    ->values()
                    ->all();

                return [
                    'id' => $cat->slug,
                    'name' => $cat->name,
                    'items' => $items,
                ];
            })
            ->filter(fn (array $cat) => $cat['items'] !== [])
            ->values()
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
            'hero_image' => StockImages::resolve(
                'Founders at the pass',
                Setting::get('about_hero_image_path') ?: 'images/dining-spread.jpeg'
            ),
            'story' => AboutStory::query()->orderBy('sort_order')->pluck('paragraph')->all(),
            'values' => AboutValue::query()->orderBy('sort_order')->get()->map(fn ($v) => [
                'icon' => $v->icon,
                'title' => $v->title,
                'text' => $v->body,
            ])->all(),
            'stats' => LiveSiteContent::about()['stats'],
            'team' => collect(LiveSiteContent::about()['team'])
                ->map(fn (array $member) => [
                    'name' => $member['name'],
                    'role' => $member['role'],
                    'tag' => $member['tag'],
                    'image' => StockImages::resolve($member['name'], $member['image_path'] ?? null),
                ])
                ->all(),
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
