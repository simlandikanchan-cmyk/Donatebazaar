<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow the polymorphic `followers` table to hold campaign follows.
     *
     * The original table constrained `following_id` to the `users` table,
     * which prevented storing campaign follows (following_type = 'campaign').
     * We drop that FK (followers are always users, but the followed entity
     * can be a user OR a campaign) and extend the unique index to include
     * following_type so a user can follow both a user and a campaign with
     * the same numeric id without colliding.
     */
    public function up(): void
    {
        Schema::table('followers', function (Blueprint $table) {
            $table->dropForeign(['following_id']);
            $table->dropUnique(['follower_id', 'following_id']);
            $table->unique(['follower_id', 'following_id', 'following_type'], 'followers_unique');
        });
    }

    public function down(): void
    {
        Schema::table('followers', function (Blueprint $table) {
            $table->dropUnique('followers_unique');
            $table->unique(['follower_id', 'following_id']);
            $table->foreign('following_id')->constrained('users')->cascadeOnDelete();
        });
    }
};
