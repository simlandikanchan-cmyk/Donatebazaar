<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ✅ 1. ADD NEW COLUMNS
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'reference_id')) {
                $table->unsignedBigInteger('reference_id')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('comments', 'reference_type')) {
                $table->string('reference_type')->nullable()->after('reference_id');
            }
        });

        // ✅ 2. COPY DATA
        DB::statement("UPDATE comments SET reference_id = post_id, reference_type = 'post'");

        // ✅ 3. DROP FOREIGN KEY (IMPORTANT)
        DB::statement("ALTER TABLE comments DROP FOREIGN KEY comments_post_id_foreign");

        // ✅ 4. DROP OLD COLUMN
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('post_id');
        });

        // ✅ 5. ADD INDEX
        Schema::table('comments', function (Blueprint $table) {
            $table->index(['reference_id', 'reference_type'], 'comments_ref_index');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('comments_ref_index');
            $table->dropColumn(['reference_id', 'reference_type']);
        });
    }
};