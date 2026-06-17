<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->string('offer_type')->default('limited_time')->after('accent');
            $table->string('cta_type')->default('menu')->after('offer_type');
            $table->string('cta_label')->nullable()->after('cta_type');
            $table->string('menu_item_slug')->nullable()->after('cta_label');
            $table->text('terms')->nullable()->after('menu_item_slug');
            $table->date('starts_at')->nullable()->after('terms');
            $table->date('ends_at')->nullable()->after('starts_at');
            $table->decimal('min_order_amount', 8, 2)->nullable()->after('ends_at');
            $table->unsignedTinyInteger('min_party_size')->nullable()->after('min_order_amount');
        });

        DB::table('promos')->whereIn('slug', ['order-online', 'catering'])->delete();

        DB::table('promos')->where('slug', 'momo-combo')->update([
            'badge' => 'Combo deal',
            'title' => 'Combo Momo Feast',
            'detail' => 'Steamed, fried, sandheko, and chili momo in one order — four styles, one great price.',
            'price_label' => '$14.99',
            'accent' => 'spice',
            'offer_type' => 'combo_meal',
            'cta_type' => 'order_item',
            'cta_label' => 'Order this combo',
            'menu_item_slug' => 'combo-momo',
            'terms' => 'Valid for pickup and delivery. Cannot be combined with other offers.',
            'updated_at' => now(),
        ]);

        if (! DB::table('promos')->where('slug', 'free-delivery-40')->exists()) {
            DB::table('promos')->insert([
                'slug' => 'free-delivery-40',
                'badge' => 'Spend & save',
                'title' => 'Free delivery on orders $40+',
                'detail' => 'Order $40 or more for delivery and the delivery fee is on us — perfect for family dinners.',
                'price_label' => '$40 min',
                'accent' => 'gold',
                'offer_type' => 'spend_save',
                'cta_type' => 'menu',
                'cta_label' => 'Start your order',
                'menu_item_slug' => null,
                'terms' => 'Applies to delivery orders only. Before tax and tip.',
                'starts_at' => null,
                'ends_at' => null,
                'min_order_amount' => 40,
                'min_party_size' => null,
                'is_active' => true,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! DB::table('promos')->where('slug', 'party-welcome-drink')->exists()) {
            DB::table('promos')->insert([
                'slug' => 'party-welcome-drink',
                'badge' => 'Dine-in perk',
                'title' => 'Party of 6 — welcome drink on us',
                'detail' => 'Reserve a table for six or more and each guest receives a complimentary welcome drink.',
                'price_label' => '6+ guests',
                'accent' => 'leaf',
                'offer_type' => 'reservation_perk',
                'cta_type' => 'reserve',
                'cta_label' => 'Reserve for 6+',
                'menu_item_slug' => null,
                'terms' => 'Dine-in only. Mention this offer when seated. Non-alcoholic welcome drink per guest.',
                'starts_at' => null,
                'ends_at' => null,
                'min_order_amount' => null,
                'min_party_size' => 6,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('promos')->where('slug', 'momo-combo')->update(['sort_order' => 1]);
    }

    public function down(): void
    {
        DB::table('promos')->whereIn('slug', ['free-delivery-40', 'party-welcome-drink'])->delete();

        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn([
                'offer_type',
                'cta_type',
                'cta_label',
                'menu_item_slug',
                'terms',
                'starts_at',
                'ends_at',
                'min_order_amount',
                'min_party_size',
            ]);
        });
    }
};
