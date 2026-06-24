<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feeds', function (Blueprint $table) {
    $table->index(['location_id', 'created_at', 'score'], 'idx_feed_main');
});

Schema::table('donations', function (Blueprint $table) {
    $table->index(['campaign_id', 'created_at'], 'idx_donation_campaign');
});

Schema::table('campaigns', function (Blueprint $table) {
    $table->index(['status', 'is_featured', 'is_urgent'], 'idx_campaign_status');
});

Schema::table('followers', function (Blueprint $table) {
    $table->index(['follower_id', 'following_id'], 'idx_followers_lookup');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
