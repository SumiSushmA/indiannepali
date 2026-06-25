<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('promos')->whereIn('slug', [
            'free-delivery-40',
            'momo-combo',
            'party-welcome-drink',
            'p1',
            'p2',
            'p3',
            'order-online',
            'catering',
        ])->delete();
    }

    public function down(): void
    {
        // Placeholder promos are not restored — add offers through the admin panel.
    }
};
