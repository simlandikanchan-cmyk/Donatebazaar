<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE events
            MODIFY COLUMN status
            ENUM(
                'pending',
                'active',
                'completed',
                'cancelled',
                'expired'
            )
            DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE events
            MODIFY COLUMN status
            ENUM(
                'pending',
                'active',
                'completed',
                'cancelled'
            )
            DEFAULT 'pending'
        ");
    }
};