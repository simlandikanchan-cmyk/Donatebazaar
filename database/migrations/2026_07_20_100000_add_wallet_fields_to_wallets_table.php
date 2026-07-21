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
        Schema::table('wallets', function (Blueprint $table) {
            // Make the legacy single-owner column nullable so the wallet can
            // instead be owned polymorphically via owner_type / owner_id.
            if (Schema::hasColumn('wallets', 'user_id')) {
                $table->foreignId('user_id')->nullable()->change();
            }

            if (! Schema::hasColumn('wallets', 'owner_type')) {
                $table->string('owner_type')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('wallets', 'owner_id')) {
                $table->unsignedBigInteger('owner_id')->nullable()->after('owner_type');
            }

            if (! Schema::hasColumn('wallets', 'reserved_balance')) {
                $table->decimal('reserved_balance', 12, 2)->default(0)->after('balance');
            }

            if (! Schema::hasColumn('wallets', 'pending_settlement_balance')) {
                $table->decimal('pending_settlement_balance', 12, 2)->default(0)->after('reserved_balance');
            }

            if (! Schema::hasColumn('wallets', 'currency')) {
                $table->char('currency', 3)->default('INR')->after('pending_settlement_balance');
            }
        });

        // Unique index on the polymorphic owner pair (only one wallet per owner).
        Schema::table('wallets', function (Blueprint $table) {
            $table->unique(['owner_type', 'owner_id'], 'wallets_owner_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropUnique('wallets_owner_unique');
            $table->dropIndex(['owner_type', 'owner_id']);

            if (Schema::hasColumn('wallets', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('wallets', 'pending_settlement_balance')) {
                $table->dropColumn('pending_settlement_balance');
            }
            if (Schema::hasColumn('wallets', 'reserved_balance')) {
                $table->dropColumn('reserved_balance');
            }
            if (Schema::hasColumn('wallets', 'owner_id')) {
                $table->dropColumn('owner_id');
            }
            if (Schema::hasColumn('wallets', 'owner_type')) {
                $table->dropColumn('owner_type');
            }

            if (Schema::hasColumn('wallets', 'user_id')) {
                $table->foreignId('user_id')->nullable(false)->change();
            }
        });
    }
};
