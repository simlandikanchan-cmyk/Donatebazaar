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
            // Set when the donation's reserved wallet funds are released
            // (matured) into available balance.
            if (! Schema::hasColumn('donations', 'released_at')) {
                $table->timestamp('released_at')->nullable()->after('refunded_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (Schema::hasColumn('donations', 'released_at')) {
                $table->dropColumn('released_at');
            }
        });
    }
};
