<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM(
            'pending', 'approved', 'active', 'completed', 'cancelled', 'expired', 'draft'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("UPDATE events SET status = 'pending' WHERE status NOT IN ('pending','approved','completed','cancelled')");
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM(
            'pending', 'approved', 'completed', 'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }
};
