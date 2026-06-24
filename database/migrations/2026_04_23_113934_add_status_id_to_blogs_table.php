<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add column only if not exists
        if (!Schema::hasColumn('blogs', 'status_id')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->unsignedBigInteger('status_id')->nullable()->after('status');
            });
        }

        // Add foreign key only if not exists
        $fkExists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'blogs'
            AND COLUMN_NAME = 'status_id'
            AND REFERENCED_TABLE_NAME = 'statuses'
        ");

        if (empty($fkExists)) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->foreign('status_id')->references('id')->on('statuses');
            });
        }
    }

    public function down(): void
    {
        // Keep empty for safety
    }
};