<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #5 — KYC Integrity & NGO Status Enum
 *
 * Problems solved:
 *
 * A) kyc_verifications.document_number was nullable.
 *    KYC without a document number is meaningless — it means you accepted
 *    an identity check with no identifying number recorded.
 *    Fix: Make it NOT NULL with a sensible default for any existing null rows,
 *    then apply the NOT NULL constraint.
 *
 * B) ngos.verification_status was VARCHAR(255).
 *    Every other status field on the platform uses an ENUM (campaigns.status,
 *    kyc_verifications.status, etc.). A free-text field here is inconsistent
 *    and allows invalid values like "Pending", "APPROVED", or typos.
 *    Fix: Convert to ENUM('pending','approved','rejected').
 *
 * C) Add explicit ON DELETE RESTRICT to campaigns.category_id.
 *    The original FK had no ON DELETE clause, meaning MariaDB would silently
 *    block the delete (default InnoDB behaviour) — but it was undocumented.
 *    Making it RESTRICT makes the intent clear and prevents accidents.
 */
return new class extends Migration
{
    public function up(): void
    {
        // A) Fix kyc_verifications.document_number
        // First: fill any existing nulls with a placeholder so we can apply NOT NULL
        \DB::table('kyc_verifications')
            ->whereNull('document_number')
            ->update(['document_number' => 'UNKNOWN-REQUIRES-UPDATE']);

        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->string('document_number')->nullable(false)->change();
        });

        // B) Fix ngos.verification_status — varchar → enum
        // MariaDB requires changing column type; cast existing values to valid enum values
        \DB::statement("
            UPDATE ngos
            SET verification_status = CASE
                WHEN LOWER(verification_status) IN ('approved') THEN 'approved'
                WHEN LOWER(verification_status) IN ('rejected') THEN 'rejected'
                ELSE 'pending'
            END
        ");

        \DB::statement("
            ALTER TABLE ngos
            MODIFY COLUMN verification_status
            ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'
        ");

        // C) Make campaigns → categories FK explicit with RESTRICT
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->string('document_number')->nullable()->change();
        });

        \DB::statement("
            ALTER TABLE ngos
            MODIFY COLUMN verification_status VARCHAR(255) NOT NULL DEFAULT 'pending'
        ");

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }
};
