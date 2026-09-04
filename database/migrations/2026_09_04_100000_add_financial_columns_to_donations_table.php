<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add gateway fee and refund-tracking columns to donations.
     *
     * These are additive and nullable so the existing schema and behaviour are
     * preserved. Gateway fees are only populated once the real value is fetched
     * from the provider (Phase 3); they are never overwritten with estimates.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Actual Razorpay processing fee and GST in rupees. NULL = not yet
            // captured (never backed into with an estimate).
            $table->decimal('gateway_fee', 12, 2)->nullable()->after('platform_fee');
            $table->decimal('gateway_tax', 12, 2)->nullable()->after('gateway_fee');

            // Who bears the gateway fee at the time of settlement.
            // 'platform' | 'campaign_owner' | null
            $table->string('gateway_fee_bearer', 20)->nullable()->after('gateway_tax');

            // Fee capture state: 'captured' | 'unavailable' | 'pending' | null
            $table->string('fee_capture_status', 20)->nullable()->after('gateway_fee_bearer');

            // Total refunded rupees across all refunds for this donation.
            $table->decimal('refunded_amount', 12, 2)->default(0)->after('is_refunded');

            // Net amount actually paid out (settled) for this donation.
            $table->decimal('payout_amount', 12, 2)->nullable()->after('refunded_amount');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn([
                'gateway_fee',
                'gateway_tax',
                'gateway_fee_bearer',
                'fee_capture_status',
                'refunded_amount',
                'payout_amount',
            ]);
        });
    }
};
