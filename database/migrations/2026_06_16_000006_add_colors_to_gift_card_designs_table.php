<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gift_card_designs', function (Blueprint $table) {
            $table->string('bg_start', 20)->nullable()->after('accent');
            $table->string('bg_mid', 20)->nullable()->after('bg_start');
            $table->string('bg_end', 20)->nullable()->after('bg_mid');
            $table->string('text_color', 20)->nullable()->after('bg_end');
        });

        DB::table('gift_card_designs')->where('accent', 'gold')->update([
            'bg_start' => '#c9922a',
            'bg_mid' => '#e8c56a',
            'bg_end' => '#f8e8b8',
            'text_color' => '#3a2810',
        ]);
        DB::table('gift_card_designs')->where('accent', 'spice')->update([
            'bg_start' => '#963042',
            'bg_mid' => '#d46a62',
            'bg_end' => '#fae8e0',
            'text_color' => '#3a1810',
        ]);
        DB::table('gift_card_designs')->where('accent', 'leaf')->update([
            'bg_start' => '#3d7a52',
            'bg_mid' => '#6dad82',
            'bg_end' => '#e4f5ea',
            'text_color' => '#1a2e20',
        ]);
    }

    public function down(): void
    {
        Schema::table('gift_card_designs', function (Blueprint $table) {
            $table->dropColumn(['bg_start', 'bg_mid', 'bg_end', 'text_color']);
        });
    }
};
