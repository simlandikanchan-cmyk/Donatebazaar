<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `users.role` and `users.status` are filtered on frequently (admin
     * listings, authorization checks, active-user scopes). Index them.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role') && ! Schema::hasIndex('users', 'users_role_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('role', 'users_role_index');
            });
        }

        if (Schema::hasColumn('users', 'status') && ! Schema::hasIndex('users', 'users_status_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('status', 'users_status_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_role_index');
            });
        }

        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_status_index');
            });
        }
    }
};
