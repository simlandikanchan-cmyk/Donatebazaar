<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Create risk_config (versioned global risk configuration).
     * Seeded with v1 defaults by a separate seeder (not in Phase 0 logic).
     */
    public function up(): void
    {
        Schema::create('risk_config', function (Blueprint $table) {
            $table->id();
            $table->integer('risk_version')->unique();
            $table->integer('approval_threshold');
            $table->integer('manual_review_threshold');
            $table->json('velocity_limits')->nullable();
            $table->string('aml_version')->nullable();
            $table->integer('fraud_threshold')->nullable();
            $table->json('configurable_limits')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_config');
    }
};
