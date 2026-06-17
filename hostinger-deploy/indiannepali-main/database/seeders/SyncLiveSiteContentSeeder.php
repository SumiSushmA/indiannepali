<?php

namespace Database\Seeders;

use App\Data\LiveSiteContent;
use App\Models\AboutStat;
use App\Models\AboutStory;
use App\Models\AboutValue;
use App\Models\ContentBlock;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class SyncLiveSiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->syncSettings();
        $this->syncContentBlocks();
        $this->syncMenu();
        $this->syncAbout();
        $this->syncReviews();
        $this->syncPromos();
        $this->syncGallery();
    }

    private function syncSettings(): void
    {
        foreach (LiveSiteContent::settings() as $key => $value) {
            Setting::set($key, $value);
        }
    }

    private function syncContentBlocks(): void
    {
        foreach (LiveSiteContent::contentBlocks() as $section => $value) {
            ContentBlock::updateOrCreate(
                ['section' => $section],
                ['value' => $value, 'type' => 'Text']
            );
        }
    }

    private function syncMenu(): void
    {
        MenuItem::query()->delete();
        MenuCategory::query()->delete();

        $menu = LiveSiteContent::menu();
        $categoryIds = [];

        foreach ($menu['categories'] as $index => $category) {
            $model = MenuCategory::create([
                'slug' => $category['id'],
                'name' => $category['name'],
                'tag' => $category['tag'],
                'description' => $category['desc'],
                'sort_order' => $index,
                'is_active' => true,
            ]);
            $categoryIds[$category['id']] = $model->id;
        }

        foreach ($menu['items'] as $index => $item) {
            MenuItem::create([
                'menu_category_id' => $categoryIds[$item['cat']],
                'slug' => $item['id'],
                'name' => $item['name'],
                'description' => $item['desc'],
                'price' => $item['price'],
                'is_veg' => $item['veg'],
                'spice_level' => $item['spice'],
                'is_popular' => ! empty($item['popular']),
                'image_label' => $item['img'],
                'is_available' => true,
                'sort_order' => $index,
            ]);
        }
    }

    private function syncAbout(): void
    {
        $about = LiveSiteContent::about();

        AboutStory::query()->delete();
        foreach ($about['story'] as $index => $paragraph) {
            AboutStory::create(['paragraph' => $paragraph, 'sort_order' => $index]);
        }

        AboutValue::query()->delete();
        foreach ($about['values'] as $index => $value) {
            AboutValue::create([
                'icon' => $value['icon'],
                'title' => $value['title'],
                'body' => $value['text'],
                'sort_order' => $index,
            ]);
        }

        AboutStat::query()->delete();
        foreach ($about['stats'] as $index => [$val, $label]) {
            AboutStat::create(['value' => $val, 'label' => $label, 'sort_order' => $index]);
        }

        TeamMember::query()->delete();
        foreach ($about['team'] as $index => $member) {
            TeamMember::create([
                'name' => $member['name'],
                'role' => $member['role'],
                'tag' => $member['tag'],
                'sort_order' => $index,
                'is_published' => true,
            ]);
        }
    }

    private function syncReviews(): void
    {
        Review::query()->delete();

        foreach (LiveSiteContent::reviews() as $index => $review) {
            Review::create([
                'author_name' => $review['name'],
                'stars' => $review['stars'],
                'body' => $review['text'],
                'source_tag' => $review['tag'],
                'is_featured' => true,
                'sort_order' => $index,
            ]);
        }
    }

    private function syncPromos(): void
    {
        Promo::query()->delete();

        foreach (LiveSiteContent::promos() as $index => $promo) {
            Promo::create([
                'slug' => $promo['id'],
                'badge' => $promo['badge'],
                'title' => $promo['title'],
                'detail' => $promo['detail'],
                'price_label' => $promo['price'],
                'accent' => $promo['accent'],
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }
    }

    private function syncGallery(): void
    {
        GalleryImage::query()->delete();
        GalleryCategory::query()->delete();

        foreach (LiveSiteContent::galleryCategories() as $catIndex => $category) {
            $galleryCategory = GalleryCategory::create([
                'slug' => $category['id'],
                'name' => $category['name'],
                'sort_order' => $catIndex,
            ]);

            foreach ($category['items'] as $imgIndex => $caption) {
                GalleryImage::create([
                    'gallery_category_id' => $galleryCategory->id,
                    'caption' => $caption,
                    'sort_order' => $imgIndex,
                    'is_published' => true,
                ]);
            }
        }
    }
}
