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
        Schema::table('organizations', function (Blueprint $table) {
            // Configurable reserve window (days) before a credited donation
            // becomes withdrawable from the org/user wallet.
            if (! Schema::hasColumn('organizations', 'wallet_hold_days')) {
                $table->integer('wallet_hold_days')->default(7);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'wallet_hold_days')) {
                $table->dropColumn('wallet_hold_days');
            }
        });
    }
};
