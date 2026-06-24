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

            if (! $this->indexExists('blogs', 'blogs_author_id_index')) {
                $table->index('author_id');
            }

            if (! $this->indexExists('blogs', 'blogs_status_index')) {
                $table->index('status');
            }

            if (! $this->indexExists('blogs', 'blogs_published_at_index')) {
                $table->index('published_at');
            }

            if (! $this->indexExists('blogs', 'blogs_deleted_at_index')) {
                $table->index('deleted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {

            if ($this->indexExists('blogs', 'blogs_author_id_index')) {
                $table->dropIndex('blogs_author_id_index');
            }

            if ($this->indexExists('blogs', 'blogs_status_index')) {
                $table->dropIndex('blogs_status_index');
            }

            if ($this->indexExists('blogs', 'blogs_published_at_index')) {
                $table->dropIndex('blogs_published_at_index');
            }

            if ($this->indexExists('blogs', 'blogs_deleted_at_index')) {
                $table->dropIndex('blogs_deleted_at_index');
            }
        });
    }

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