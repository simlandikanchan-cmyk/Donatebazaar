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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('wallet_transactions', 'source')) {
                $table->enum('source', [
                    'donation',
                    'refund',
                    'settlement',
                    'gift_card',
                    'coupon',
                    'adjustment',
                ])->after('type');
            }

            if (! Schema::hasColumn('wallet_transactions', 'balance_after')) {
                $table->decimal('balance_after', 12, 2)->default(0)->after('source');
            }

            if (! Schema::hasColumn('wallet_transactions', 'status')) {
                $table->enum('status', ['pending', 'completed', 'failed'])
                    ->default('completed')
                    ->after('balance_after');
            }

            if (! Schema::hasColumn('wallet_transactions', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        if (! Schema::hasIndex('wallet_transactions', ['wallet_id', 'created_at'])) {
            Schema::table('wallet_transactions', function (Blueprint $table) {
                $table->index(['wallet_id', 'created_at'], 'wallet_tx_wallet_created');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex('wallet_tx_wallet_created');

            if (Schema::hasColumn('wallet_transactions', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('wallet_transactions', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('wallet_transactions', 'balance_after')) {
                $table->dropColumn('balance_after');
            }
            if (Schema::hasColumn('wallet_transactions', 'source')) {
                $table->dropColumn('source');
            }
        });
    }
};
