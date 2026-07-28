<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (! Schema::hasIndex('donations', 'donations_settlement_status_idx')) {
                $table->index(
                    ['settlement_status', 'paid_at', 'is_refunded'],
                    'donations_settlement_status_idx'
                );
            }
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropIndex('donations_settlement_status_idx');
        });
    }
};
