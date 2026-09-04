<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the immutable financial_ledger table.
     *
     * This is an append-only, single-writer journal that records every financial
     * event (donation captured, gateway fee captured, refund, payout/settlement).
     * It is the reconciliation source of truth used to verify that local
     * accounting matches the underlying donations, wallets and settlements.
     *
     * Rows are never updated or deleted by application code. All money columns
     * are stored in the configured currency (INR) with 2 decimal places.
     */
    public function up(): void
    {
        Schema::create('financial_ledger', function (Blueprint $table) {
            $table->id();

            // Financial event type, e.g. 'donation_captured', 'gateway_fee_captured',
            // 'refund_processed', 'payout_completed'.
            $table->string('event', 40);

            // Sum that moved in the local ledger (gross flow for the event).
            $table->decimal('amount', 12, 2)->default(0);

            $table->string('currency', 3)->default('INR');

            // Polymorphic reference to the business row (Donation, Refund,
            // CampaignSettlement, WalletTransaction, ...).
            $table->string('reference_type')->nullable()->index();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->index(['reference_type', 'reference_id']);
            $table->index('reference_id');

            // Provider/settlement reference for cross-checking (payment id,
            // refund id, transfer id, ...).
            $table->string('gateway_reference')->nullable()->index();

            // Idempotency: the same event for the same business reference may
            // only be recorded once in the ledger.
            $table->string('idempotency_key', 64)->nullable()->unique();

            // Balance snapshot before/after this event, for audit trailing.
            $table->decimal('balance_before', 12, 2)->nullable();
            $table->decimal('balance_after', 12, 2)->nullable();

            // Structured metadata (fee breakdown, bearer, actor, ...).
            $table->json('metadata')->nullable();

            $table->timestamp('occurred_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_ledger');
    }
};
