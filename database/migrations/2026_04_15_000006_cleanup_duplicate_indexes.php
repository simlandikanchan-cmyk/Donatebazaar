<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #6 — Index Cleanup & Missing Indexes
 *
 * Problems solved:
 *
 * A) Duplicate indexes on campaigns:
 *      campaigns_status_is_featured_is_urgent_index  (status, is_featured, is_urgent)
 *      idx_campaign_status                           (status, is_featured, is_urgent)
 *    Identical coverage — one is wasted. Every INSERT/UPDATE pays the cost of
 *    maintaining both. Drop the shorter-named one (idx_campaign_status).
 *
 * B) Three overlapping indexes on followers:
 *      followers_follower_id_following_id_unique       (follower_id, following_id) — UNIQUE
 *      idx_followers_lookup                            (follower_id, following_id)
 *      idx_followers_fast                              (follower_id, following_id)
 *    The UNIQUE index already serves the lookup purpose. Drop both plain duplicates.
 *    The composite (follower_id, following_id, following_type) index is distinct — keep it.
 *
 * C) Missing composite index on donations (campaign_id, payment_status):
 *    The most common query on a donation platform is:
 *      "show me all successful donations for campaign X"
 *    There is an index on (campaign_id, created_at) and a separate one on (payment_status),
 *    but no composite that covers both. Add it.
 *
 * D) Missing index on recurring_donations.status:
 *    The scheduler needs to find all 'active' recurring donations to bill.
 *    Without an index, this is a full table scan.
 *
 * E) Missing index on users.provider_id:
 *    Social login (Google/Facebook) lookups use provider + provider_id to find
 *    an existing user. Without an index this is O(n) on every OAuth login.
 */
return new class extends Migration
{
    public function up(): void
    {
        // A) Remove duplicate campaign status indexes
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex('idx_campaign_status');
            // Keep: campaigns_status_is_featured_is_urgent_index
        });

        // B) Remove duplicate follower indexes
        Schema::table('followers', function (Blueprint $table) {
            $table->dropIndex('idx_followers_lookup');
            $table->dropIndex('idx_followers_fast');
            // Keep: followers_follower_id_following_id_unique (covers lookup)
            // Keep: followers_follower_id_following_id_following_type_index (distinct)
        });

        // C) Composite index for donation filtering
        Schema::table('donations', function (Blueprint $table) {
            $table->index(['campaign_id', 'payment_status'], 'idx_donations_campaign_status');
        });

        // D) Index for recurring donation scheduler
        Schema::table('recurring_donations', function (Blueprint $table) {
            $table->index(['status', 'next_billing_date'], 'idx_recurring_active_billing');
        });

        // E) Index for social auth lookups
        Schema::table('users', function (Blueprint $table) {
            $table->index(['provider', 'provider_id'], 'idx_users_social_auth');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->index(['status', 'is_featured', 'is_urgent'], 'idx_campaign_status');
        });

        Schema::table('followers', function (Blueprint $table) {
            $table->index(['follower_id', 'following_id'], 'idx_followers_lookup');
            $table->index(['follower_id', 'following_id'], 'idx_followers_fast');
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->dropIndex('idx_donations_campaign_status');
        });

        Schema::table('recurring_donations', function (Blueprint $table) {
            $table->dropIndex('idx_recurring_active_billing');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_social_auth');
        });
    }
};
