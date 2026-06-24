<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // FEEDS
    DB::statement('CREATE INDEX idx_feed_super ON feeds(location_id, score DESC, created_at DESC)');

    // DONATIONS
    Schema::table('donations', function (Blueprint $table) {
        $table->index(['campaign_id', 'created_at'], 'idx_donations_campaign_created');
    });

    // FOLLOWERS
    Schema::table('followers', function (Blueprint $table) {
        $table->index(['follower_id', 'following_id'], 'idx_followers_fast');
    });

    // POSTS
    Schema::table('posts', function (Blueprint $table) {
        $table->index(['user_id', 'created_at'], 'idx_posts_user_created');
    });
}

public function down()
{
    DB::statement('DROP INDEX idx_feed_super ON feeds');

    Schema::table('donations', function (Blueprint $table) {
        $table->dropIndex('idx_donations_campaign_created');
    });

    Schema::table('followers', function (Blueprint $table) {
        $table->dropIndex('idx_followers_fast');
    });

    Schema::table('posts', function (Blueprint $table) {
        $table->dropIndex('idx_posts_user_created');
    });
}
};
