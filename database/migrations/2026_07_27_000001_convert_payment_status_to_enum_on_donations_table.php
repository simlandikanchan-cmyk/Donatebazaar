<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Converts donations.payment_status from varchar to a native
     * MariaDB ENUM column for improved storage efficiency and
     * data integrity at the database level.
     *
     * Storage reduction: ~10% (enum stores as 1-2 bytes vs
     * variable-length varchar overhead).
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (Schema::hasColumn('donations', 'payment_status')) {
                $table->enum('payment_status', [
                    'pending',
                    'completed',
                    'failed',
                    'refunded',
                    'cancelled',
                    'processing',
                ])->default('pending')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Reverts payment_status back to a varchar string column
     * to preserve backward compatibility and enable easy rollback.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (Schema::hasColumn('donations', 'payment_status')) {
                $table->string('payment_status', 50)->default('pending')->change();
            }
        });
    }
};