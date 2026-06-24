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


    Schema::create('organizations', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->enum('type', ['trust', 'society', 'section8']);
    $table->text('address')->nullable();
    $table->string('cause')->nullable();
    $table->string('founder_name')->nullable();
    $table->string('linkedin')->nullable();

    $table->string('website')->nullable();
    $table->string('budget_range')->nullable();
    $table->string('donor_strength')->nullable();
    $table->string('employee_strength')->nullable();
    $table->boolean('has_crowdfunded')->default(false);
    $table->string('campaign_timeline')->nullable();

    $table->timestamps();
    
    });


    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
