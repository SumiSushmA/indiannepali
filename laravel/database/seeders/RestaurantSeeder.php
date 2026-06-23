<?php

namespace Database\Seeders;

use App\Models\AboutStat;
use App\Models\AboutStory;
use App\Models\AboutValue;
use App\Models\CateringInquiry;
use App\Models\CateringPackage;
use App\Models\ContactMessage;
use App\Models\ContentBlock;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\GiftAmount;
use App\Models\GiftCard;
use App\Models\GiftCardDesign;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promo;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\ToastSyncLog;
use App\Models\User;
use App\Data\LiveSiteContent;
use App\Data\SeedData;
use App\Support\StockImages;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMenu();
        $this->seedPromos();
        $this->seedReviews();
        $this->seedGallery();
        $this->seedAbout();
        $this->seedGiftDesigns();
        $this->seedGiftAmounts();
        $this->seedCateringPackages();
        $this->seedContentBlocks();
        $this->seedSettings();
        $this->seedUsers();
        $this->seedOrders();
        $this->seedReservations();
        $this->seedCateringInquiries();
        $this->seedContactMessages();
        $this->seedGiftCards();
        $this->seedToastSyncLogs();
    }

    private function seedMenu(): void
    {
        $menu = LiveSiteContent::menu();
        $categoryIds = [];

        foreach ($menu['categories'] as $index => $category) {
            $model = MenuCategory::updateOrCreate(
                ['slug' => $category['id']],
                [
                    'name' => $category['name'],
                    'tag' => $category['tag'],
                    'description' => $category['desc'],
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
            $categoryIds[$category['id']] = $model->id;
        }

        $menuImages = StockImages::menuImageMapBySlug();

        foreach ($menu['items'] as $index => $item) {
            MenuItem::updateOrCreate(
                ['slug' => $item['id']],
                [
                    'menu_category_id' => $categoryIds[$item['cat']],
                    'name' => $item['name'],
                    'description' => $item['desc'],
                    'price' => $item['price'],
                    'is_veg' => $item['veg'],
                    'spice_level' => $item['spice'],
                    'is_popular' => ! empty($item['popular']),
                    'image_label' => $item['img'],
                    'image_path' => $menuImages[$item['id']] ?? null,
                    'is_available' => $item['id'] !== 'c2',
                    'sort_order' => $index,
                ]
            );
        }
    }

    private function seedPromos(): void
    {
        foreach (LiveSiteContent::promos() as $index => $promo) {
            Promo::updateOrCreate(
                ['slug' => $promo['id']],
                [
                    'badge' => $promo['badge'],
                    'title' => $promo['title'],
                    'detail' => $promo['detail'],
                    'price_label' => $promo['price'],
                    'accent' => $promo['accent'],
                    'offer_type' => $promo['offer_type'] ?? 'limited_time',
                    'cta_type' => $promo['cta_type'] ?? 'menu',
                    'cta_label' => $promo['cta_label'] ?? null,
                    'menu_item_slug' => $promo['menu_item_slug'] ?? null,
                    'terms' => $promo['terms'] ?? null,
                    'min_order_amount' => $promo['min_order_amount'] ?? null,
                    'min_party_size' => $promo['min_party_size'] ?? null,
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }

    private function seedReviews(): void
    {
        if (Review::query()->exists()) {
            return;
        }

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

    private function seedGallery(): void
    {
        $spans = [1, 2, 1, 1, 2, 1, 1, 1, 2, 1, 1, 1];
        $galleryImages = StockImages::galleryImageMapByCaption();
        $seenCaptions = [];
        $usedImagePaths = [];

        foreach (LiveSiteContent::galleryCategories() as $catIndex => $category) {
            $galleryCategory = GalleryCategory::updateOrCreate(
                ['slug' => $category['id']],
                [
                    'name' => $category['name'],
                    'sort_order' => $catIndex,
                ]
            );

            foreach ($category['items'] as $imgIndex => $caption) {
                $imagePath = $galleryImages[$caption] ?? null;
                if ($imagePath && isset($usedImagePaths[$imagePath])) {
                    continue;
                }

                $seenCaptions[] = $caption;
                if ($imagePath) {
                    $usedImagePaths[$imagePath] = true;
                }

                GalleryImage::updateOrCreate(
                    [
                        'gallery_category_id' => $galleryCategory->id,
                        'caption' => $caption,
                    ],
                    [
                        'image_path' => $imagePath,
                        'grid_span' => $spans[($catIndex * 3 + $imgIndex) % count($spans)],
                        'sort_order' => $imgIndex,
                        'is_published' => true,
                    ]
                );
            }
        }

        GalleryImage::query()
            ->whereNotIn('caption', $seenCaptions)
            ->delete();
    }

    private function syncAboutStats(): void
    {
        $stats = LiveSiteContent::about()['stats'];

        foreach ($stats as $index => $stat) {
            AboutStat::updateOrCreate(
                ['label' => $stat[1]],
                [
                    'value' => $stat[0],
                    'sort_order' => $index,
                ]
            );
        }

        AboutStat::query()
            ->whereNotIn('label', array_column($stats, 1))
            ->delete();
    }

    private function syncAboutTeam(): void
    {
        $team = LiveSiteContent::about()['team'];
        $names = array_column($team, 'name');

        if ($names === []) {
            TeamMember::query()->update(['is_published' => false]);

            return;
        }

        foreach ($team as $index => $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name']],
                [
                    'role' => $member['role'],
                    'tag' => $member['tag'],
                    'image_path' => $member['image_path'] ?? null,
                    'sort_order' => $index,
                    'is_published' => true,
                ]
            );
        }

        TeamMember::query()
            ->whereNotIn('name', $names)
            ->update(['is_published' => false]);
    }

    private function seedAbout(): void
    {
        $about = LiveSiteContent::about();

        $this->syncAboutStats();
        $this->syncAboutTeam();

        if (AboutStory::query()->exists()) {
            return;
        }

        foreach ($about['story'] as $index => $paragraph) {
            AboutStory::create([
                'paragraph' => $paragraph,
                'sort_order' => $index,
            ]);
        }

        foreach ($about['values'] as $index => $value) {
            AboutValue::create([
                'icon' => $value['icon'],
                'title' => $value['title'],
                'body' => $value['text'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedGiftDesigns(): void
    {
        foreach (SeedData::giftDesigns() as $design) {
            GiftCardDesign::updateOrCreate(
                ['slug' => $design['id']],
                [
                    'name' => $design['name'],
                    'subtitle' => $design['sub'],
                    'accent' => $design['accent'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedGiftAmounts(): void
    {
        foreach ([25, 50, 75, 100, 150, 250] as $index => $amount) {
            GiftAmount::updateOrCreate(
                ['amount' => $amount],
                [
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }

    private function seedCateringPackages(): void
    {
        if (CateringPackage::query()->exists()) {
            return;
        }

        $packages = [
            ['name' => 'The Gathering', 'range' => '20–40 guests', 'price' => 'from $22/guest', 'items' => ['Choice of 3 curries', 'Two momo varieties', 'Biryani & rice', 'Naan basket & achar', 'One dessert'], 'popular' => false],
            ['name' => 'The Celebration', 'range' => '40–120 guests', 'price' => 'from $28/guest', 'items' => ['Choice of 5 curries', 'Live momo station', 'Tandoor platter', 'Two biryani', 'Breads, sides & 2 desserts', 'Chafing & service ware'], 'popular' => true],
            ['name' => 'The Banquet', 'range' => '120+ guests', 'price' => 'custom', 'items' => ['Full custom menu', 'On-site chefs & servers', 'Live stations', 'Beverage & chai service', 'Setup, service & cleanup', 'Dedicated event lead'], 'popular' => false],
        ];

        foreach ($packages as $index => $package) {
            CateringPackage::create([
                'name' => $package['name'],
                'guest_range' => $package['range'],
                'price_label' => $package['price'],
                'items' => $package['items'],
                'is_popular' => $package['popular'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedContentBlocks(): void
    {
        foreach (LiveSiteContent::contentBlocks() as $section => $value) {
            ContentBlock::updateOrCreate(
                ['section' => $section],
                [
                    'value' => $value,
                    'type' => 'Text',
                ]
            );
        }
    }

    private function seedSettings(): void
    {
        $settings = LiveSiteContent::settings();

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }

    private function seedUsers(): void
    {
        $users = SeedData::users();
        $lastActiveMap = [
            '2m ago' => now()->subMinutes(2),
            '1h ago' => now()->subHour(),
            'Today' => now()->startOfDay()->addHours(10),
            'Yesterday' => now()->subDay(),
            '—' => null,
            '3 wks ago' => now()->subWeeks(3),
        ];
        $statusMap = [
            'Active' => 'active',
            'Invited' => 'invited',
            'Inactive' => 'inactive',
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                    'status' => $statusMap[$user['status']] ?? 'active',
                    'last_active_at' => $lastActiveMap[$user['last']] ?? null,
                ]
            );
        }
    }

    private function seedOrders(): void
    {
        if (Order::query()->exists()) {
            return;
        }

        $orders = SeedData::orders();
        $menuItemsByName = MenuItem::all()->keyBy('name');

        foreach ($orders as $orderData) {
            $placedAt = now()->subMinutes($orderData['mins']);
            $subtotal = $orderData['total'] * 0.85;
            $tax = round($subtotal * 0.0875, 2);
            $deliveryFee = strtolower($orderData['type']) === 'delivery' ? 3.99 : 0;

            $order = Order::create([
                'order_number' => $orderData['id'],
                'customer_name' => $orderData['customer'],
                'customer_email' => strtolower(str_replace(' ', '.', $orderData['customer'])) . '@email.com',
                'customer_phone' => '(415) 555-0' . rand(100, 999),
                'fulfillment_type' => strtolower($orderData['type']),
                'address' => strtolower($orderData['type']) === 'delivery' ? '418 Saffron Lane, Apt 2' : null,
                'channel' => $orderData['channel'],
                'status' => $orderData['status'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'delivery_fee' => $deliveryFee,
                'tip' => 0,
                'total' => $orderData['total'],
                'placed_at' => $placedAt,
            ]);

            foreach ($orderData['items'] as $item) {
                $menuItem = $menuItemsByName->get($item['name']);
                $unitPrice = $menuItem ? (float) $menuItem->price : 15.00;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem?->id,
                    'item_name' => $item['name'],
                    'unit_price' => $unitPrice,
                    'quantity' => $item['qty'],
                    'line_total' => $unitPrice * $item['qty'],
                ]);
            }
        }
    }

    private function seedReservations(): void
    {
        if (Reservation::query()->exists()) {
            return;
        }

        $reservations = SeedData::reservations();

        foreach ($reservations as $reservation) {
            Reservation::create([
                'reference' => $reservation['id'],
                'party_size' => $reservation['party'],
                'reserved_date' => $reservation['date'],
                'reserved_time' => $reservation['time'],
                'customer_name' => $reservation['name'],
                'customer_email' => strtolower(str_replace(' ', '.', $reservation['name'])) . '@email.com',
                'customer_phone' => $reservation['phone'],
                'occasion' => $reservation['occasion'] === '—' ? null : $reservation['occasion'],
                'status' => $reservation['status'],
                'table_number' => $reservation['table'],
            ]);
        }
    }

    private function seedCateringInquiries(): void
    {
        if (CateringInquiry::query()->exists()) {
            return;
        }

        $inquiries = SeedData::cateringInquiries();
        $packages = CateringPackage::all();

        foreach ($inquiries as $index => $inquiry) {
            CateringInquiry::create([
                'reference' => $inquiry['id'],
                'customer_name' => $inquiry['name'],
                'customer_email' => strtolower(str_replace(' ', '.', $inquiry['name'])) . '@email.com',
                'customer_phone' => '(415) 555-0' . (200 + $index),
                'event_type' => $inquiry['event'],
                'event_date' => $inquiry['date'],
                'guest_count' => $inquiry['guests'],
                'message' => null,
                'status' => $inquiry['status'],
                'quoted_value' => $inquiry['value'],
                'catering_package_id' => $packages->get($index % $packages->count())?->id,
                'created_at' => now()->subDays($inquiry['days']),
                'updated_at' => now()->subDays($inquiry['days']),
            ]);
        }
    }

    private function seedContactMessages(): void
    {
        if (ContactMessage::query()->exists()) {
            return;
        }

        $messages = SeedData::contactMessages();

        foreach ($messages as $message) {
            ContactMessage::create([
                'reference' => $message['id'],
                'customer_name' => $message['name'],
                'customer_email' => $message['email'],
                'subject' => $message['subject'],
                'message' => $message['preview'],
                'status' => $message['status'],
                'read_at' => $message['status'] !== 'Unread' ? now()->subDays($message['days']) : null,
                'created_at' => now()->subDays($message['days']),
                'updated_at' => now()->subDays($message['days']),
            ]);
        }
    }

    private function seedGiftCards(): void
    {
        if (GiftCard::query()->exists()) {
            return;
        }

        $cards = SeedData::giftCards();
        $designsByName = GiftCardDesign::all()->keyBy('name');

        foreach ($cards as $card) {
            GiftCard::create([
                'code' => $card['code'],
                'gift_card_design_id' => $designsByName->get($card['design'])->id,
                'face_value' => $card['face'],
                'balance' => $card['balance'],
                'status' => $card['status'],
                'recipient_name' => $card['recipient'],
                'sender_name' => null,
                'channel' => $card['channel'],
                'issued_at' => Carbon::parse($card['issued']),
            ]);
        }
    }

    private function seedToastSyncLogs(): void
    {
        if (ToastSyncLog::query()->exists()) {
            return;
        }

        $logs = SeedData::toastLogs();
        $today = now()->startOfDay();

        foreach ($logs as $log) {
            [$hour, $minute] = explode(':', $log['t']);

            ToastSyncLog::create([
                'logged_at' => $today->copy()->setTime((int) $hour, (int) $minute),
                'message' => $log['m'],
                'is_success' => $log['ok'],
            ]);
        }
    }
}
