<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_applications', function (Blueprint $table) {
            $table->text('pan_number')->nullable()->change();
            $table->text('bank_name')->nullable()->change();
            $table->text('bank_account_number')->nullable()->change();
            $table->text('bank_ifsc')->nullable()->change();
            $table->text('bank_account_type')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('organization_applications', function (Blueprint $table) {
            $table->string('pan_number')->nullable()->change();
            $table->string('bank_name')->nullable()->change();
            $table->string('bank_account_number')->nullable()->change();
            $table->string('bank_ifsc')->nullable()->change();
            $table->string('bank_account_type')->nullable()->change();
        });
    }
};