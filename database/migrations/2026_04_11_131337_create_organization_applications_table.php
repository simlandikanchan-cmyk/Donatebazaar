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
        Schema::create('organization_applications', function (Blueprint $table) {
    $table->id();

    // User
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    // Step 1 - Organization Info
    $table->string('organization_type')->nullable();
    $table->string('name');
    $table->text('address')->nullable();
    $table->json('causes')->nullable();
    $table->string('founder_name')->nullable();
    $table->string('founder_linkedin')->nullable();

    // Step 2 - Contact Person
    $table->string('contact_name');
    $table->string('contact_phone');
    $table->string('contact_email');
    $table->string('contact_role')->nullable();

    // Step 3 - Certifications
    $table->boolean('has_80g')->default(false);
    $table->boolean('has_fcra')->default(false);

    // Step 4 - Profile
    $table->string('website')->nullable();
    $table->string('budget_range')->nullable();
    $table->string('donor_strength')->nullable();
    $table->string('employee_strength')->nullable();
    $table->boolean('has_crowdfunded')->default(false);
    $table->string('campaign_timeline')->nullable();

    // Status
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_applications');
    }
};
