<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Check existing primary key
        $primary = DB::select("
            SELECT COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'blog_tag'
            AND CONSTRAINT_NAME = 'PRIMARY'
        ");

        // If already has composite PK → do nothing
        if (count($primary) == 2) {
            return;
        }

        // If has single PK (like id), skip or handle manually
        if (count($primary) == 1) {
            return; // safer for now
        }

        // If no primary key → add composite
        Schema::table('blog_tag', function (Blueprint $table) {
            $table->primary(['blog_id', 'tag_id']);
        });
    }

    public function down(): void {}
};