<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('donations', 'refund_idempotency_key')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->string('refund_idempotency_key', 64)->nullable()->after('refunded_at')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('donations', 'refund_idempotency_key')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropIndex(['refund_idempotency_key']);
                $table->dropColumn('refund_idempotency_key');
            });
        }
    }
};
