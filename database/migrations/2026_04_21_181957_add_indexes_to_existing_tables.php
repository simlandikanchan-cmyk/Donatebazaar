<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_campaign_user_status');
            $table->index(['category_id', 'created_at'], 'idx_campaign_category_created');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_blog_status_created');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'idx_orders_user_created');
        });

        Schema::table('campaign_analytics', function (Blueprint $table) {
            $table->index(['campaign_id', 'created_at'], 'idx_campaign_time');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->index('user_id', 'idx_cart_user');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex('idx_campaign_user_status');
            $table->dropIndex('idx_campaign_category_created');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex('idx_blog_status_created');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_user_created');
        });

        Schema::table('campaign_analytics', function (Blueprint $table) {
            $table->dropIndex('idx_campaign_time');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex('idx_cart_user');
        });
    }
};