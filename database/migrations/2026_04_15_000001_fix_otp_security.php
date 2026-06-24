<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #1 — OTP Security & Duplicate Column
 *
 * Problems solved:
 *   - users.otp stored OTPs in plaintext (e.g. "258531"). If the DB leaks,
 *     any attacker can immediately use those codes.
 *   - Two OTP expiry columns existed side-by-side: otp_expire AND otp_expires_at.
 *     Only one is needed. We keep otp_expires_at (the cleaner name) and drop otp_expire.
 *
 * Strategy:
 *   - Rename otp → otp_hash, change it to nullable varchar(64) for SHA-256 hex.
 *   - In your app, always store: Hash::make($otp) or hash('sha256', $otp).
 *   - When verifying: hash('sha256', $input) === $stored_hash  (or use Hash::check).
 *   - Wipe any existing plaintext OTPs (they are all expired anyway).
 *   - Drop the redundant otp_expire column.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Wipe plaintext OTPs before renaming — they are useless after rename
        DB::table('users')->update([
            'otp'        => null,
            'otp_expire' => null,
        ]);

        Schema::table('users', function (Blueprint $table) {
            // Rename otp → otp_hash and note it must now hold a hash, not raw digits
            $table->renameColumn('otp', 'otp_hash');

            // Drop the redundant expiry column; otp_expires_at stays
            $table->dropColumn('otp_expire');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('otp_hash', 'otp');
            $table->timestamp('otp_expire')->nullable()->after('otp');
        });
    }
};
