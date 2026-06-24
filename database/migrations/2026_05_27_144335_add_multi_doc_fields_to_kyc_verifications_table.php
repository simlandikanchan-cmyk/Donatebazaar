<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('kyc_verifications', function (Blueprint $table) {
        $table->string('aadhaar_url')->nullable()->after('document_url');
        $table->string('pan_url')->nullable()->after('aadhaar_url');
        $table->string('selfie_url')->nullable()->after('pan_url');
        $table->string('kyc_account_name')->nullable()->after('selfie_url');
        $table->string('kyc_account_number')->nullable()->after('kyc_account_name');
        $table->string('kyc_ifsc')->nullable()->after('kyc_account_number');
        $table->string('kyc_bank_name')->nullable()->after('kyc_ifsc');
    });
}

public function down(): void
{
    Schema::table('kyc_verifications', function (Blueprint $table) {
        $table->dropColumn(['aadhaar_url','pan_url','selfie_url','kyc_account_name','kyc_account_number','kyc_ifsc','kyc_bank_name']);
    });
}
};
