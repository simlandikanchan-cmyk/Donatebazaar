<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Check if FK already exists before adding
        $fkExists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'blogs'
            AND COLUMN_NAME = 'author_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (empty($fkExists)) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->foreign('author_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            try {
                $table->dropForeign(['author_id']);
            } catch (\Exception $e) {}
        });
    }
};