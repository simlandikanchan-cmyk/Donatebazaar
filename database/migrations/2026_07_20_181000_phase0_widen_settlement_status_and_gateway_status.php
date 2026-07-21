<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Widen campaign_settlements.status enum (add new members, keep legacy)
     * and add the separate gateway_status enum column.
     *
     * Settlement status = business workflow.
     * Gateway status = payment provider state (never mixed).
     */
    public function up(): void
    {
        if (Schema::hasColumn('campaign_settlements', 'status')) {
            DB::statement("ALTER TABLE campaign_settlements MODIFY COLUMN status ENUM(
                'pending','processing','paid','failed','pending_approval','approved','rejected',
                'requested','risk_evaluation','auto_approved','manual_review','cancelled','retry_pending'
            ) NOT NULL DEFAULT 'pending'");
        }

        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (! Schema::hasColumn('campaign_settlements', 'gateway_status')) {
                $table->enum('gateway_status', [
                    'queued', 'initiated', 'accepted', 'processing',
                    'completed', 'failed', 'reversed', 'unknown',
                ])->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (Schema::hasColumn('campaign_settlements', 'gateway_status')) {
                $table->dropColumn('gateway_status');
            }
        });

        // Narrow back only if no rows use the new statuses.
        $usesNew = DB::table('campaign_settlements')
            ->whereIn('status', [
                'requested', 'risk_evaluation', 'auto_approved',
                'manual_review', 'cancelled', 'retry_pending',
            ])
            ->exists();

        if (! $usesNew && Schema::hasColumn('campaign_settlements', 'status')) {
            DB::statement("ALTER TABLE campaign_settlements MODIFY COLUMN status ENUM(
                'pending','processing','paid','failed','pending_approval','approved','rejected'
            ) NOT NULL DEFAULT 'pending'");
        }
    }
};
