<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enforce a single KYC record per (user, campaign).
     *
     * WHY: KYC uploads are scoped per campaign (user_id + campaign_id), but the
     * table currently allows duplicate rows for the same (user_id, campaign_id).
     * This migration deterministically keeps ONE authoritative row per group and
     * then adds a UNIQUE(user_id, campaign_id) index so the invariant holds.
     *
     * Legacy rows with campaign_id = NULL are preserved untouched (they are not
     * involved in the uniqueness constraint) and never deleted arbitrarily.
     */
    public function up(): void
    {
        // ── 1. Deterministic deduplication (non-NULL campaign_id only) ─────
        // Keep the most authoritative/current row per (user_id, campaign_id):
        //   - status priority: approved > rejected > pending
        //   - tie-break: verified_at desc, then updated_at desc, then id desc
        $groups = DB::table('kyc_verifications')
            ->select('user_id', 'campaign_id')
            ->whereNotNull('campaign_id')
            ->groupBy('user_id', 'campaign_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $statusRank = [
            'approved' => 3,
            'rejected' => 2,
            'pending' => 1,
        ];

        foreach ($groups as $group) {
            $keepId = DB::table('kyc_verifications')
                ->where('user_id', $group->user_id)
                ->where('campaign_id', $group->campaign_id)
                ->orderByRaw(
                    'CASE status '
                    ."WHEN 'approved' THEN {$statusRank['approved']} "
                    ."WHEN 'rejected' THEN {$statusRank['rejected']} "
                    ."WHEN 'pending' THEN {$statusRank['pending']} "
                    .'ELSE 0 END DESC'
                )
                ->orderByDesc('verified_at')
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->value('id');

            // Delete duplicate rows, keeping the authoritative one.
            DB::table('kyc_verifications')
                ->where('user_id', $group->user_id)
                ->where('campaign_id', $group->campaign_id)
                ->where('id', '!=', $keepId)
                ->delete();
        }

        // ── 2. Add the unique constraint ───────────────────────────────────
        // MySQL allows multiple NULLs in a unique index, so legacy rows with
        // campaign_id = NULL are preserved and do not conflict.
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->unique(['user_id', 'campaign_id'], 'kyc_verifications_user_campaign_unique');
        });
    }

    public function down(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->dropUnique('kyc_verifications_user_campaign_unique');
        });
    }
};
