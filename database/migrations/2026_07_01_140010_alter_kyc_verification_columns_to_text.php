<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->text('document_number')->nullable()->change();
            $table->text('kyc_account_name')->nullable()->change();
            $table->text('kyc_account_number')->nullable()->change();
            $table->text('kyc_ifsc')->nullable()->change();
            $table->text('kyc_bank_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('kyc_verifications', function (Blueprint $table) {
            $table->string('document_number')->nullable()->change();
            $table->string('kyc_account_name')->nullable()->change();
            $table->string('kyc_account_number')->nullable()->change();
            $table->string('kyc_ifsc')->nullable()->change();
            $table->string('kyc_bank_name')->nullable()->change();
        });
    }
};