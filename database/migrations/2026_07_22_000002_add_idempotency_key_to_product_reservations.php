<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_reservations', function (Blueprint $table) {
            $table->string('idempotency_key')->nullable()->after('session_id');
            $table->unique(['idempotency_key', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::table('product_reservations', function (Blueprint $table) {
            $table->dropUnique('product_reservations_idempotency_key_product_id_unique');
            $table->dropColumn('idempotency_key');
        });
    }
};
