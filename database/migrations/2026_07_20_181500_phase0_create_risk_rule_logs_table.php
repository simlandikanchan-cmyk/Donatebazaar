<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Create risk_rule_logs (source of truth for triggered_rules per evaluation).
     * One row per evaluated rule; replaces any JSON triggered_rules blob.
     */
    public function up(): void
    {
        Schema::create('risk_rule_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_score_id')->constrained('risk_scores')->cascadeOnDelete();
            $table->string('rule_name');
            $table->string('category')->nullable();
            $table->boolean('triggered')->default(false);
            $table->integer('points')->default(0);
            $table->boolean('force_review')->default(false);
            $table->json('detail')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_rule_logs');
    }
};
