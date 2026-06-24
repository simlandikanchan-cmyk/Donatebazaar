<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // BLOGS TABLE
        Schema::table('blogs', function (Blueprint $table) {

            if (! $this->foreignKeyExists('blogs', 'blogs_author_id_foreign')) {
                $table->foreign('author_id')
                      ->references('id')->on('users')
                      ->cascadeOnDelete();
            }

            if (! $this->foreignKeyExists('blogs', 'blogs_category_id_foreign')) {
                $table->foreign('category_id')
                      ->references('id')->on('categories')
                      ->nullOnDelete();
            }

        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {

            if ($this->foreignKeyExists('blogs', 'blogs_author_id_foreign')) {
                $table->dropForeign('blogs_author_id_foreign');
            }

            if ($this->foreignKeyExists('blogs', 'blogs_category_id_foreign')) {
                $table->dropForeign('blogs_category_id_foreign');
            }

        });
    }

    /**
     * Check if foreign key exists
     */
    private function foreignKeyExists(string $table, string $foreignKeyName): bool
    {
        $dbName = DB::getDatabaseName();

        $result = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND CONSTRAINT_NAME = ?
        ", [$dbName, $table, $foreignKeyName]);

        return count($result) > 0;
    }
};