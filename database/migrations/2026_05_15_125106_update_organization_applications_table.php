<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_applications', function (Blueprint $table) {

            // ── Step 1: Organization Info ──────────────────────────────────
            $table->string('registration_number')->nullable()->after('organization_type');
            $table->date('registration_date')->nullable()->after('registration_number');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode', 10)->nullable()->after('state');
            $table->text('mission_statement')->nullable()->after('founder_linkedin');

            // ── Step 2: Contact Person ─────────────────────────────────────
            $table->string('contact_linkedin')->nullable()->after('contact_role');
            $table->string('contact_whatsapp')->nullable()->after('contact_linkedin');

            // ── Step 3: Certifications & Legal ─────────────────────────────
            $table->string('80g_number')->nullable()->after('has_80g');
            $table->date('80g_expiry')->nullable()->after('80g_number');
            $table->string('fcra_number')->nullable()->after('has_fcra');
            $table->boolean('has_12a')->default(false)->after('fcra_number');
            $table->string('12a_number')->nullable()->after('has_12a');
            $table->boolean('has_csr_eligible')->default(false)->after('12a_number');
            $table->string('pan_number')->nullable()->after('has_csr_eligible');
            $table->string('darpan_id')->nullable()->after('pan_number');

            // ── Step 4: Documents & Profile ────────────────────────────────
            $table->string('social_facebook')->nullable()->after('website');
            $table->string('social_instagram')->nullable()->after('social_facebook');
            $table->string('social_twitter')->nullable()->after('social_instagram');
            $table->string('social_youtube')->nullable()->after('social_twitter');
            $table->integer('campaigns_completed')->nullable()->after('campaign_timeline');

            // Documents
            $table->string('doc_registration_cert')->nullable()->after('campaigns_completed');
            $table->string('doc_80g_certificate')->nullable()->after('doc_registration_cert');
            $table->string('doc_fcra_certificate')->nullable()->after('doc_80g_certificate');
            $table->string('doc_annual_report')->nullable()->after('doc_fcra_certificate');
            $table->string('doc_audited_statement')->nullable()->after('doc_annual_report');
            $table->string('doc_pan_card')->nullable()->after('doc_audited_statement');

            // Bank Details
            $table->string('bank_name')->nullable()->after('doc_pan_card');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_ifsc')->nullable()->after('bank_account_number');
            $table->string('bank_account_type')->nullable()->after('bank_ifsc');

            // References
            $table->string('reference_1_name')->nullable()->after('bank_account_type');
            $table->string('reference_1_org')->nullable()->after('reference_1_name');
            $table->string('reference_1_phone')->nullable()->after('reference_1_org');
            $table->string('reference_2_name')->nullable()->after('reference_1_phone');
            $table->string('reference_2_org')->nullable()->after('reference_2_name');
            $table->string('reference_2_phone')->nullable()->after('reference_2_org');

            // ── Status & Scoring ───────────────────────────────────────────
            $table->integer('priority_score')->default(0)->after('status');
            $table->text('rejection_reason')->nullable()->after('priority_score');
            $table->text('admin_notes')->nullable()->after('rejection_reason');
            $table->timestamp('submitted_at')->nullable()->after('admin_notes');
            $table->timestamp('reviewed_at')->nullable()->after('submitted_at');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete()->after('reviewed_at');
            $table->tinyInteger('current_step')->default(1)->after('reviewed_by');
            $table->softDeletes();

            // Modify status enum to include draft & under_review
            \DB::statement("ALTER TABLE organization_applications MODIFY COLUMN status ENUM('draft','pending','under_review','approved','rejected') DEFAULT 'draft'");
        });
    }

    public function down(): void
    {
        Schema::table('organization_applications', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'registration_number', 'registration_date', 'city', 'state', 'pincode', 'mission_statement',
                'contact_linkedin', 'contact_whatsapp',
                '80g_number', '80g_expiry', 'fcra_number', 'has_12a', '12a_number',
                'has_csr_eligible', 'pan_number', 'darpan_id',
                'social_facebook', 'social_instagram', 'social_twitter', 'social_youtube',
                'campaigns_completed',
                'doc_registration_cert', 'doc_80g_certificate', 'doc_fcra_certificate',
                'doc_annual_report', 'doc_audited_statement', 'doc_pan_card',
                'bank_name', 'bank_account_number', 'bank_ifsc', 'bank_account_type',
                'reference_1_name', 'reference_1_org', 'reference_1_phone',
                'reference_2_name', 'reference_2_org', 'reference_2_phone',
                'priority_score', 'rejection_reason', 'admin_notes',
                'submitted_at', 'reviewed_at', 'reviewed_by', 'current_step', 'deleted_at',
            ]);

            \DB::statement("ALTER TABLE organization_applications MODIFY COLUMN status ENUM('pending','approved','rejected') DEFAULT 'pending'");
        });
    }
};