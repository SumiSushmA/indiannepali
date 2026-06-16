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
                'is_available' => $item['id'] !== 'c2',
                'sort_order' => $index,
            ]);
        }
    }

    private function seedPromos(): void
    {
        foreach (LiveSiteContent::promos() as $index => $promo) {
            Promo::create([
                'slug' => $promo['id'],
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
            ]);
        }
    }

    private function seedReviews(): void
    {
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
                    'grid_span' => $spans[($catIndex * 3 + $imgIndex) % count($spans)],
                    'sort_order' => $imgIndex,
                    'is_published' => true,
                ]);
            }
        }
    }

    private function seedAbout(): void
    {
        $about = LiveSiteContent::about();

        foreach ($about['story'] as $index => $paragraph) {
            AboutStory::create([
                'paragraph' => $paragraph,
                'sort_order' => $index,
            ]);
        }

        foreach ($about['stats'] as $index => $stat) {
            AboutStat::create([
                'value' => $stat[0],
                'label' => $stat[1],
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

    private function seedGiftDesigns(): void
    {
        foreach (SeedData::giftDesigns() as $design) {
            GiftCardDesign::create([
                'slug' => $design['id'],
                'name' => $design['name'],
                'subtitle' => $design['sub'],
                'accent' => $design['accent'],
                'is_active' => true,
            ]);
        }
    }

    private function seedGiftAmounts(): void
    {
        foreach ([25, 50, 75, 100, 150, 250] as $index => $amount) {
            GiftAmount::create([
                'amount' => $amount,
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }
    }

    private function seedCateringPackages(): void
    {
        $packages = [
            ['name' => 'The Gathering', 'range' => '20–40 guests', 'price' => 'from $22/guest', 'items' => ['Choice of 3 curries', 'Two momo varieties', 'Biryani & rice', 'Naan basket & achar', 'One dessert'], 'popular' => false],
            ['name' => 'The Celebration', 'range' => '40–120 guests', 'price' => 'from $28/guest', 'items' => ['Choice of 5 curries', 'Live momo station', 'Tandoor platter', 'Two biryani', 'Breads, sides & 2 desserts', 'Chafing & service ware'], 'popular' => true],
            ['name' => 'The Banquet', 'range' => '120–300 guests', 'price' => 'custom', 'items' => ['Full custom menu', 'On-site chefs & servers', 'Live stations', 'Beverage & chai service', 'Setup, service & cleanup', 'Dedicated event lead'], 'popular' => false],
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
            ContentBlock::create([
                'section' => $section,
                'value' => $value,
                'type' => 'Text',
            ]);
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
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => $user['role'],
                'status' => $statusMap[$user['status']] ?? 'active',
                'last_active_at' => $lastActiveMap[$user['last']] ?? null,
            ]);
        }
    }

    private function seedOrders(): void
    {
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
