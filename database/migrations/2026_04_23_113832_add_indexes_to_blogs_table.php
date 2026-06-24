<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Get existing indexes
        $indexes = collect(DB::select("SHOW INDEX FROM blogs"))
            ->pluck('Key_name')
            ->toArray();

        Schema::table('blogs', function (Blueprint $table) use ($indexes) {

            if (!in_array('blogs_slug_index', $indexes)) {
                $table->index('slug');
            }

            if (!in_array('blogs_uuid_unique', $indexes)) {
                $table->unique('uuid');
            }

            if (!in_array('blogs_status_index', $indexes)) {
                $table->index('status');
            }

            if (!in_array('blogs_author_id_index', $indexes)) {
                $table->index('author_id');
            }

            if (!in_array('blogs_category_id_index', $indexes)) {
                $table->index('category_id');
            }

            if (!in_array('blogs_created_at_index', $indexes)) {
                $table->index('created_at');
            }
        });
    }

    public function down(): void
    {
        // Optional rollback (safe)
    }
};