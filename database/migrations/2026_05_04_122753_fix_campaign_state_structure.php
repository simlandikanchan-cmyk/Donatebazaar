<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure campaign_state column exists
        if (!Schema::hasColumn('campaigns', 'campaign_state')) {
            return;
        }

        // -----------------------------------------------------------------
        // STEP 1: Temporarily allow all possible values
        // -----------------------------------------------------------------
        DB::statement("
            ALTER TABLE campaigns
            MODIFY campaign_state ENUM(
                'pending',
                'active',
                'approved',
                'paused',
                'inactive',
                'expired',
                'rejected',
                'completed'
            ) NOT NULL DEFAULT 'pending'
        ");

        // -----------------------------------------------------------------
        // STEP 2: Fix NULL or empty values
        // -----------------------------------------------------------------
        DB::statement("
            UPDATE campaigns
            SET campaign_state = 'pending'
            WHERE campaign_state IS NULL
               OR campaign_state = ''
        ");

        // Convert inactive → expired
        DB::statement("
            UPDATE campaigns
            SET campaign_state = 'expired'
            WHERE campaign_state = 'inactive'
        ");

        // Convert approved → active
        DB::statement("
            UPDATE campaigns
            SET campaign_state = 'active'
            WHERE campaign_state = 'approved'
        ");

        // -----------------------------------------------------------------
        // STEP 3: Migrate old status column only if it exists
        // -----------------------------------------------------------------
        if (Schema::hasColumn('campaigns', 'status')) {
            DB::statement("
                UPDATE campaigns
                SET campaign_state = 'pending'
                WHERE status = 'pending'
            ");

            DB::statement("
                UPDATE campaigns
                SET campaign_state = 'active'
                WHERE status = 'approved'
            ");

            DB::statement("
                UPDATE campaigns
                SET campaign_state = 'rejected'
                WHERE status = 'rejected'
            ");

            DB::statement("
                UPDATE campaigns
                SET campaign_state = 'completed'
                WHERE status = 'completed'
            ");
        }

        // -----------------------------------------------------------------
        // STEP 4: Final clean enum
        // -----------------------------------------------------------------
        DB::statement("
            ALTER TABLE campaigns
            MODIFY campaign_state ENUM(
                'pending',
                'active',
                'paused',
                'expired',
                'rejected',
                'completed'
            ) NOT NULL DEFAULT 'pending'
        ");

        // -----------------------------------------------------------------
        // STEP 5: Drop old status column if it exists
        // -----------------------------------------------------------------
        if (Schema::hasColumn('campaigns', 'status')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down(): void
    {
        // Optional rollback logic
    }
};