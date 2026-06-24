<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE recurring_donations
            MODIFY COLUMN frequency
            ENUM('daily', 'weekly', 'monthly', 'quarterly')
            NOT NULL
        ");
    }

    public function down(): void
    {
        // Revert back to original two values
        // WARNING: any rows with 'daily' or 'quarterly' will cause this to fail
        // Make sure no such rows exist before rolling back
        DB::statement("
            ALTER TABLE recurring_donations
            MODIFY COLUMN frequency
            ENUM('weekly', 'monthly')
            NOT NULL
        ");
    }
};