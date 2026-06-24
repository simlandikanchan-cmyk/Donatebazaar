<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #8 — Soft Deletes for Users + Organization Ownership
 *
 * Problems solved:
 *
 * A) users table had no deleted_at column.
 *    Every other major entity (campaigns, donations, events, orders, products, posts)
 *    has soft deletes. Without it, deleting a user permanently destroys their audit
 *    trail and may violate data retention requirements (GDPR, India's DPDP Act).
 *    A user should be "deactivated" not "destroyed".
 *
 *    Impact of adding deleted_at:
 *    - Add SoftDeletes trait to User model.
 *    - All existing queries using User::find() / User::all() will automatically
 *      exclude soft-deleted users. No query changes needed for that.
 *    - Auth: login should check deleted_at IS NULL (Laravel's default auth does this).
 *
 * B) organizations table had no user_id FK.
 *    Unlike ngos (which has user_id), organizations had no owner. This means:
 *    - No way to know who created/owns the organization.
 *    - organization_applications has user_id, but after approval there's no
 *      link from the resulting organization back to the applicant user.
 *    Fix: Add user_id FK (nullable — for admin-created orgs with no user owner).
 */
return new class extends Migration
{
    public function up(): void
    {
        // A) Soft deletes for users
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->after('phone_verified_at');
        });

        // B) Organization ownership
        Schema::table('organizations', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
