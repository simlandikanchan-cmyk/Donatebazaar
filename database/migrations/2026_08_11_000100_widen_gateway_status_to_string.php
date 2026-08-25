<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gateway statuses are provider-defined and open-ended
     * (e.g. Razorpay 'processed', reconciliation 'paid'/'cancelled').
     * The fixed ENUM rejects valid provider values with
     * "Data truncated" — widen to a free-form string.
     */
    public function up(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (Schema::hasColumn('campaign_settlements', 'gateway_status')) {
                DB::statement('ALTER TABLE campaign_settlements MODIFY gateway_status VARCHAR(32) NULL');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (Schema::hasColumn('campaign_settlements', 'gateway_status')) {
                DB::statement("ALTER TABLE campaign_settlements MODIFY gateway_status ENUM(
                    'queued', 'initiated', 'accepted', 'processing',
                    'completed', 'failed', 'reversed', 'unknown'
                ) NULL");
            }
        });
    }
};
