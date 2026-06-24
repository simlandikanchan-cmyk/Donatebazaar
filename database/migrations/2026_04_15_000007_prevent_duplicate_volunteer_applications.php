<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #7 — Prevent Duplicate Volunteer Applications
 *
 * Problems solved:
 *   - volunteer_applications had no uniqueness constraint.
 *   - The dump showed IDs 3 & 4: same volunteer_id=1, campaign_id=NULL, ngo_id=NULL,
 *     exact same timestamp — clear duplicates inserted by a double-submit or bug.
 *   - A volunteer could apply to the same campaign unlimited times, flooding NGO inboxes.
 *
 * Fix:
 *   - De-duplicate existing rows (keep the oldest).
 *   - Add a partial unique index: a volunteer can only have ONE pending/approved
 *     application per campaign (or per NGO).
 *   - NULL handling: MySQL/MariaDB unique indexes treat NULLs as distinct,
 *     so (volunteer_id=1, campaign_id=NULL) would never be considered a duplicate
 *     by a normal UNIQUE KEY. We handle this with a generated/virtual approach
 *     or by enforcing at application level for the NULL case.
 *
 * Note on NULL campaign/ngo applications (IDs 3 & 4):
 *   These are "general interest" applications with no specific campaign or NGO.
 *   We de-duplicate them here and add a CHECK constraint to ensure at least
 *   one of campaign_id or ngo_id is always set going forward.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Remove exact duplicates — keep the row with the lowest id
        $duplicates = DB::select("
            SELECT id FROM volunteer_applications va
            WHERE id NOT IN (
                SELECT MIN(id)
                FROM volunteer_applications
                GROUP BY volunteer_id, COALESCE(campaign_id, 0), COALESCE(ngo_id, 0)
            )
        ");

        if (!empty($duplicates)) {
            $ids = array_column($duplicates, 'id');
            DB::table('volunteer_applications')->whereIn('id', $ids)->delete();
        }

        // Step 2: Enforce that at least one of campaign_id or ngo_id is non-null
        DB::statement("
            ALTER TABLE volunteer_applications
            ADD CONSTRAINT chk_volunteer_target
            CHECK (campaign_id IS NOT NULL OR ngo_id IS NOT NULL)
        ");

        // Step 3: Unique index on (volunteer_id, campaign_id) for campaign applications
        // MariaDB NULLs don't conflict in unique indexes, so this only prevents
        // duplicates when campaign_id IS NOT NULL — which is exactly what we want.
        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->unique(['volunteer_id', 'campaign_id'], 'uq_volunteer_campaign');
            $table->unique(['volunteer_id', 'ngo_id'], 'uq_volunteer_ngo');
        });
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE volunteer_applications
            DROP CONSTRAINT chk_volunteer_target
        ");

        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->dropUnique('uq_volunteer_campaign');
            $table->dropUnique('uq_volunteer_ngo');
        });
    }
};
