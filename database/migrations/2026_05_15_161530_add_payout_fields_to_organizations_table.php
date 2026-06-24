<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {

            $table->decimal('total_settled', 12, 2)
                ->default(0)
                ->after('raised_amount');

            $table->decimal('pending_settlement', 12, 2)
                ->default(0)
                ->after('total_settled');

            $table->decimal('platform_earnings', 12, 2)
                ->default(0)
                ->after('pending_settlement');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'total_settled',
                'pending_settlement',
                'platform_earnings'
            ]);
        });
    }
};