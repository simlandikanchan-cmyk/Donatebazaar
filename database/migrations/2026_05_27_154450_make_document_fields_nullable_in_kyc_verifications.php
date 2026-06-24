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
        $table->string('document_type')->nullable()->change();
        $table->string('document_number')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('kyc_verifications', function (Blueprint $table) {
        $table->string('document_type')->nullable(false)->change();
        $table->string('document_number')->nullable(false)->change();
    });
}
};
