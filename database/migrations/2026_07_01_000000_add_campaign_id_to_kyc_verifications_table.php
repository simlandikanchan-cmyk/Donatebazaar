<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * WHY THIS MIGRATION EXISTS:
     * kyc_verifications was originally keyed by user_id only. A user with
     * multiple campaigns would have their KYC record silently overwritten
     * (status reset, approval wiped) every time they uploaded a document
     * for a different campaign. This adds campaign_id so each campaign
     * gets its own KYC record.
     */
    public function up(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->foreignId('campaign_id')
                ->nullable() // nullable for now — see backfill note below
                ->after('user_id')
                ->constrained('campaigns')
                ->nullOnDelete();
        });

        // ── Backfill existing rows ──────────────────────────────────────
        // Best-effort only: we cannot know for certain which campaign an
        // existing KYC row was originally submitted for, since the old
        // code silently overwrote that information. This backfill attaches
        // each existing KYC row to the user's SINGLE campaign, if (and
        // only if) that user has exactly one campaign. Users with multiple
        // campaigns are left with campaign_id = NULL and must be reviewed
        // manually — query for `WHERE campaign_id IS NULL` after running
        // this migration to find them.
        $rows = DB::table('kyc_verifications')->whereNull('campaign_id')->get();

        foreach ($rows as $row) {
            $campaignIds = DB::table('campaigns')
                ->where('user_id', $row->user_id)
                ->pluck('id');

            if ($campaignIds->count() === 1) {
                DB::table('kyc_verifications')
                    ->where('id', $row->id)
                    ->update(['campaign_id' => $campaignIds->first()]);
            }
        }

        // Once you've manually resolved any remaining NULL rows (ask
        // affected users to re-submit if unsure), you can tighten this
        // with a follow-up migration:
        //
        // Schema::table('kyc_verifications', function (Blueprint $table) {
        //     $table->foreignId('campaign_id')->nullable(false)->change();
        // });
        //
        // And add a unique constraint so one campaign can't have two
        // active KYC rows:
        //
        // $table->unique(['user_id', 'campaign_id']);
    }

    public function down(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('campaign_id');
        });
    }
};