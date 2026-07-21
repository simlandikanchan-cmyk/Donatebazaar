<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Create risk_rules (config-driven rule definitions).
     * No hardcoded values; all scores/weights/thresholds come from here.
     */
    public function up(): void
    {
        Schema::create('risk_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // KYC, INSTRUMENT, LIMITS, VELOCITY, QUALITY, FRAUD, COMPLIANCE
            $table->integer('weight')->default(0);
            $table->integer('priority')->default(0);
            $table->boolean('enabled')->default(true);
            $table->boolean('force_review')->default(false);
            $table->json('threshold')->nullable();
            $table->json('configuration')->nullable();
            $table->timestamps();

            $table->index(['enabled', 'priority'], 'risk_rules_enabled_priority_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_rules');
    }
};
