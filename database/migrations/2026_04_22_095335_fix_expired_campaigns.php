<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only run if required columns exist
        if (
            Schema::hasColumn('campaigns', 'end_date') &&
            Schema::hasColumn('campaigns', 'campaign_state')
        ) {
            DB::statement("
                UPDATE campaigns
                SET campaign_state = 'paused',
                    updated_at = NOW()
                WHERE end_date < CURDATE()
                  AND campaign_state = 'active'
            ");
        }
    }

    public function down(): void
    {
        // No rollback needed
    }
};