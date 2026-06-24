<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #2 — Consolidate Dual Role Systems
 *
 * Problems solved:
 *   - users had BOTH:
 *       a) role ENUM('admin','ngo','donor')   ← simple, works fine for 3 roles
 *       b) role_id FK → roles table            ← Laravel permission-style, empty in practice
 *   - Two systems with no sync = auth bugs waiting to happen. Any query checking
 *     the wrong column gives wrong results silently.
 *
 * Decision: KEEP the enum column (it has live data), DROP role_id.
 *
 * Why keep enum over roles table?
 *   - The roles table is empty — no data to lose.
 *   - role_permission table is also empty.
 *   - The enum has 6 real users with correct values.
 *   - For a donation platform with 3 fixed roles, a roles table adds complexity
 *     with no benefit. If you later need granular permissions, use Spatie
 *     laravel-permission on top — it manages its own tables cleanly.
 *
 * If you want the roles table approach instead:
 *   - Reverse this migration.
 *   - Seed roles: Admin, NGO, Donor.
 *   - Copy users.role enum values into role_id.
 *   - Then drop the enum column.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the FK constraint first, then the column
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        // role_permission and roles are now unused — drop them
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }

    public function down(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};
