<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Create risk_scores (per-settlement evaluation snapshot = risk source of truth).
     * Settlement status/tables store only a mirror summary; detail is in risk_rule_logs.
     */
    public function up(): void
    {
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained('campaign_settlements')->cascadeOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->integer('risk_version');
            $table->integer('risk_score');
            $table->enum('risk_verdict', ['auto_approved', 'manual_review', 'rejected']);
            $table->timestamp('evaluated_at');
            $table->timestamps();

            $table->index(['organization_id', 'risk_score'], 'risk_scores_org_score_index');
            $table->index(['settlement_id', 'created_at'], 'risk_scores_settlement_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
