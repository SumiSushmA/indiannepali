<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tag')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->boolean('is_veg')->default(false);
            $table->unsignedTinyInteger('spice_level')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->string('image_label')->nullable();
            $table->string('toast_pos_id')->nullable();
            $table->boolean('is_available')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('fulfillment_type');
            $table->text('address')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('channel')->default('Website');
            $table->string('status');
            $table->decimal('subtotal', 8, 2)->default(0);
            $table->decimal('tax', 8, 2)->default(0);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('tip', 8, 2)->default(0);
            $table->decimal('tip_rate', 5, 4)->nullable();
            $table->decimal('total', 8, 2)->default(0);
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('item_name');
            $table->decimal('unit_price', 8, 2);
            $table->unsignedSmallInteger('quantity');
            $table->decimal('line_total', 8, 2);
            $table->timestamps();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->unsignedTinyInteger('party_size');
            $table->date('reserved_date');
            $table->string('reserved_time');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('occasion')->nullable();
            $table->text('notes')->nullable();
            $table->string('status');
            $table->string('table_number')->nullable();
            $table->timestamps();
        });

        Schema::create('catering_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guest_range');
            $table->string('price_label');
            $table->json('items');
            $table->boolean('is_popular')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('catering_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('event_type');
            $table->date('event_date');
            $table->unsignedSmallInteger('guest_count');
            $table->text('message')->nullable();
            $table->string('status');
            $table->decimal('quoted_value', 10, 2)->nullable();
            $table->foreignId('catering_package_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status')->default('Unread');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('badge');
            $table->string('title');
            $table->text('detail');
            $table->string('price_label');
            $table->string('accent')->default('gold');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_category_id')->constrained()->cascadeOnDelete();
            $table->string('caption');
            $table->unsignedTinyInteger('grid_span')->default(1);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->unsignedTinyInteger('stars');
            $table->text('body');
            $table->string('source_tag');
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('about_stats', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('label');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('about_values', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('title');
            $table->text('body');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('tag')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('about_stories', function (Blueprint $table) {
            $table->id();
            $table->text('paragraph');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gift_card_designs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->string('accent')->default('gold');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('gift_card_design_id')->constrained()->restrictOnDelete();
            $table->decimal('face_value', 8, 2);
            $table->decimal('balance', 8, 2);
            $table->string('status');
            $table->string('recipient_name');
            $table->string('sender_name')->nullable();
            $table->text('message')->nullable();
            $table->string('delivery_method')->default('email');
            $table->string('channel')->default('Online');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });

        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('section')->unique();
            $table->text('value');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->timestamps();
        });

        Schema::create('toast_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('logged_at');
            $table->text('message');
            $table->boolean('is_success')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toast_sync_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('content_blocks');
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('gift_card_designs');
        Schema::dropIfExists('about_stories');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('about_values');
        Schema::dropIfExists('about_stats');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('gallery_images');
        Schema::dropIfExists('gallery_categories');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('catering_inquiries');
        Schema::dropIfExists('catering_packages');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menu_categories');
    }
};
