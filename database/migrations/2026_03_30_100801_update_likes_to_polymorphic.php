<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ✅ 1. ADD NEW COLUMNS FIRST
        Schema::table('likes', function (Blueprint $table) {
            if (!Schema::hasColumn('likes', 'reference_id')) {
                $table->unsignedBigInteger('reference_id')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('likes', 'reference_type')) {
                $table->string('reference_type')->nullable()->after('reference_id');
            }
        });

        // ✅ 2. COPY OLD DATA (IMPORTANT)
        DB::statement("UPDATE likes SET reference_id = post_id, reference_type = 'post'");

        // ✅ 3. DROP FOREIGN KEY FIRST (MANDATORY)
        DB::statement("ALTER TABLE likes DROP FOREIGN KEY likes_post_id_foreign");

        // ✅ 4. NOW DROP COLUMN
        Schema::table('likes', function (Blueprint $table) {
            $table->dropColumn('post_id');
        });

        // ✅ 5. ADD INDEX
        Schema::table('likes', function (Blueprint $table) {
            $table->index(['reference_id', 'reference_type'], 'likes_ref_index');
        });
    }

    public function down()
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable();
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex('likes_ref_index');
            $table->dropColumn(['reference_id', 'reference_type']);
        });
    }
};