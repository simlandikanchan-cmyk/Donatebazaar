<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {

            // Platform commission amount
            $table->decimal('platform_fee', 12, 2)
                ->default(0)
                ->after('total_amount');

            // Final amount after commission deduction
            $table->decimal('net_amount', 12, 2)
                ->default(0)
                ->after('platform_fee');

            // Settlement tracking status
            $table->enum('settlement_status', [
                'pending',
                'processing',
                'settled',
                'failed'
            ])->default('pending')
              ->after('payment_status');

            // Settlement reference
            // Foreign key will be added later in separate migration
            $table->unsignedBigInteger('campaign_settlement_id')
                ->nullable()
                ->after('settlement_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {

            $table->dropColumn([
                'platform_fee',
                'net_amount',
                'settlement_status',
                'campaign_settlement_id',
            ]);
        });
    }
};