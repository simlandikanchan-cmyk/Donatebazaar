<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {

        //  Email nullable
        if (Schema::hasColumn('users', 'email')) {
            $table->string('email')->nullable()->change();
        }

        //  Phone
        if (!Schema::hasColumn('users', 'phone')) {
            $table->string('phone')->unique()->nullable()->after('email');
        }

        // Password nullable
        if (Schema::hasColumn('users', 'password')) {
            $table->string('password')->nullable()->change();
        }

        //  OTP
        if (!Schema::hasColumn('users', 'otp')) {
            $table->string('otp')->nullable();
        }

        if (!Schema::hasColumn('users', 'otp_expires_at')) {
            $table->timestamp('otp_expires_at')->nullable();
        }

        //  Role
        if (!Schema::hasColumn('users', 'role')) {
            $table->enum('role', ['admin','ngo','donor'])->default('donor');
        }

        //  Status
        if (!Schema::hasColumn('users', 'status')) {
            $table->enum('status', ['active','suspended'])->default('active');
        }

        //  Google login
        if (!Schema::hasColumn('users', 'provider')) {
            $table->string('provider')->nullable();
        }

        if (!Schema::hasColumn('users', 'provider_id')) {
            $table->string('provider_id')->nullable();
        }

        //  Tracking
        if (!Schema::hasColumn('users', 'last_login_at')) {
            $table->timestamp('last_login_at')->nullable();
        }

        if (!Schema::hasColumn('users', 'phone_verified_at')) {
            $table->timestamp('phone_verified_at')->nullable();
        }
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'phone',
                'otp',
                'otp_expires_at',
                'provider',
                'provider_id',
                'last_login_at',
                'phone_verified_at'
            ]);

            //  Don’t revert email/password changes in down (safe practice)
        });
    }
};