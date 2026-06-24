<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {

            if (! $this->indexExists('blogs', 'blogs_slug_unique')) {
                $table->unique('slug');
            }

        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {

            if ($this->indexExists('blogs', 'blogs_slug_unique')) {
                $table->dropUnique('blogs_slug_unique');
            }

        });
    }

    /**
     * Check if index exists
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");

        foreach ($indexes as $index) {
            if ($index->Key_name === $indexName) {
                return true;
            }
        }

        return false;
    }
};