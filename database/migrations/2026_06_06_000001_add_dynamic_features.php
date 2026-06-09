<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_label');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('caption');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('paid')->after('total');
            $table->string('card_last4')->nullable()->after('payment_status');
            $table->string('card_brand')->nullable()->after('card_last4');
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('subscribed_at');
            $table->timestamps();
        });

        Schema::create('gift_amounts', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_amounts');
        Schema::dropIfExists('newsletter_subscribers');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'card_last4', 'card_brand']);
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
