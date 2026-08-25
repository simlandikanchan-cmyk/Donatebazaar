<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('campaign_settlements', 'payout_idempotency_key')) {
            Schema::table('campaign_settlements', function (Blueprint $table) {
                $table->string('payout_idempotency_key', 64)->nullable()->after('restored_at')->index();
            });
        }

        if (! Schema::hasColumn('refunds', 'notes')) {
            Schema::table('refunds', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('reason');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('campaign_settlements', 'payout_idempotency_key')) {
            Schema::table('campaign_settlements', function (Blueprint $table) {
                $table->dropIndex(['payout_idempotency_key']);
                $table->dropColumn('payout_idempotency_key');
            });
        }

        if (Schema::hasColumn('refunds', 'notes')) {
            Schema::table('refunds', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }
    }
};
