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
        Schema::create('kyc_verifications', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->enum('document_type', ['pan','aadhaar','passport','other']);
    $table->string('document_number')->nullable(); // optional

    $table->string('document_url'); // uploaded file

    $table->enum('status', ['pending','approved','rejected'])
          ->default('pending');

    $table->foreignId('verified_by')
          ->nullable()
          ->constrained('users')
          ->nullOnDelete();

    $table->timestamp('verified_at')->nullable();

    $table->text('rejection_reason')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};
