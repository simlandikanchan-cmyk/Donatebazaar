<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Create settlement_state_logs (audit trail of every state transition).
     */
    public function up(): void
    {
        Schema::create('settlement_state_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained('campaign_settlements')->cascadeOnDelete();
            $table->string('from_state')->nullable();
            $table->string('to_state');
            $table->string('actor_type')->nullable(); // system, admin, gateway, risk_engine
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('correlation_id')->nullable()->index();
            $table->string('trace_id')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlement_state_logs');
    }
};
