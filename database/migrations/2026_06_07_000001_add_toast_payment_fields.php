<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_provider')->default('mock')->after('payment_status');
            $table->string('toast_order_guid')->nullable()->after('payment_provider');
            $table->string('toast_payment_guid')->nullable()->after('toast_order_guid');
            $table->string('payment_reference')->nullable()->after('toast_payment_guid');
        });

        Schema::table('gift_cards', function (Blueprint $table) {
            $table->string('payment_status')->default('paid')->after('channel');
            $table->string('payment_provider')->default('mock')->after('payment_status');
            $table->string('toast_order_guid')->nullable()->after('payment_provider');
            $table->string('toast_payment_guid')->nullable()->after('toast_order_guid');
            $table->string('payment_reference')->nullable()->after('toast_payment_guid');
            $table->string('card_last4')->nullable()->after('payment_reference');
            $table->string('card_brand')->nullable()->after('card_last4');
        });
    }

    public function down(): void
    {
        Schema::table('gift_cards', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_provider',
                'toast_order_guid',
                'toast_payment_guid',
                'payment_reference',
                'card_last4',
                'card_brand',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_provider',
                'toast_order_guid',
                'toast_payment_guid',
                'payment_reference',
            ]);
        });
    }
};
