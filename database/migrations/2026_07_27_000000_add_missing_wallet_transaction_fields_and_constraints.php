<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
                    'settlement_reversal',
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

            if (! Schema::hasIndex('wallet_transactions', 'wallet_tx_wallet_created')) {
                $table->index(['wallet_id', 'created_at'], 'wallet_tx_wallet_created');
            }

            if (! Schema::hasIndex('wallet_transactions', 'wallet_tx_unique')) {
                $table->unique(
                    ['wallet_id', 'reference_type', 'reference_id', 'source', 'type'],
                    'wallet_tx_unique'
                );
            }
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropUnique('wallet_tx_unique');
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
