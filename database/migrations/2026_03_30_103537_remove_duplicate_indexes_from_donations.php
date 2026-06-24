<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop duplicate index
        DB::statement('DROP INDEX idx_donation_campaign ON donations');
    }

    public function down(): void
    {
        // Recreate if rollback needed
        DB::statement('CREATE INDEX idx_donation_campaign ON donations (campaign_id, created_at)');
    }
};