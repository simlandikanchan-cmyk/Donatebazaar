<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #9 — Currency Column & Fix payment_status Type on Donations
 *
 * Problems solved:
 *
 * A) donations.payment_status was VARCHAR(255) while every other status field
 *    uses ENUM. Free-text status means invalid values like "PENDING", "Paid",
 *    or "success " (trailing space) can silently enter the DB.
 *    Fix: Convert to ENUM('pending','completed','failed','refunded').
 *    Note: we use 'completed' not 'success' to match donation_payments.status pattern
 *    and because "a donation is completed" reads better than "a donation is success".
 *
 * B) No currency column on any financial table.
 *    All amounts assumed INR. If a foreign donor pays via Stripe or PayPal,
 *    there's no way to record what currency they paid in, making reconciliation
 *    impossible and accounting legally risky.
 *    Fix: Add currency column (ISO 4217 code, e.g. 'INR', 'USD') to:
 *      - donations
 *      - wallets
 *      - wallet_transactions
 *    Default to 'INR' for all existing rows (safe assumption for this Indian platform).
 *
 * Note on campaigns.goal_amount / raised_amount:
 *    These are not given a currency column here because campaigns are inherently
 *    INR-denominated (Indian NGO platform). If you expand internationally,
 *    add currency to campaigns in a separate migration at that time.
 */
return new class extends Migration
{
    public function up(): void
    {
        // A) Fix donations.payment_status: varchar → enum
        // First normalise existing values
        DB::statement("
            UPDATE donations
            SET payment_status = CASE
                WHEN LOWER(payment_status) IN ('paid','success','completed') THEN 'completed'
                WHEN LOWER(payment_status) IN ('fail','failed')              THEN 'failed'
                WHEN LOWER(payment_status) IN ('refund','refunded')          THEN 'refunded'
                ELSE 'pending'
            END
        ");

        DB::statement("
            ALTER TABLE donations
            MODIFY COLUMN payment_status
            ENUM('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending'
        ");

        // B) Add currency columns
        Schema::table('donations', function (Blueprint $table) {
            $table->char('currency', 3)->default('INR')->after('total_amount');
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->char('currency', 3)->default('INR')->after('balance');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->char('currency', 3)->default('INR')->after('amount');
        });

        Schema::table('donation_payments', function (Blueprint $table) {
            $table->char('currency', 3)->default('INR')->after('amount');
        });

        Schema::table('order_payments', function (Blueprint $table) {
            $table->char('currency', 3)->default('INR')->after('amount');
        });
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE donations
            MODIFY COLUMN payment_status VARCHAR(255) NOT NULL DEFAULT 'pending'
        ");

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::table('donation_payments', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
