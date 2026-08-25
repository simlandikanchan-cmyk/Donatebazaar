<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Razorpay payment/refund IDs are strings (e.g. "pay_9xKz...", "rfnd_...").
     * The wallet ledger stores them in `reference_id`, which was unsignedBigInteger,
     * causing webhook credits/debits to crash with "Incorrect integer value"
     * (strict mode) and the donor's money to be silently lost.
     */
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropUnique('wallet_tx_unique');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('reference_id', 191)->nullable()->change();
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unique(
                ['wallet_id', 'reference_type', 'reference_id', 'source', 'type'],
                'wallet_tx_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropUnique('wallet_tx_unique');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('reference_id')->nullable()->change();
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unique(
                ['wallet_id', 'reference_type', 'reference_id', 'source', 'type'],
                'wallet_tx_unique'
            );
        });
    }
};
