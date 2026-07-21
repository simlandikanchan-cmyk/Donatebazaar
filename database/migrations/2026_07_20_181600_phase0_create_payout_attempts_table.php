<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Create payout_attempts (one row per gateway call; idempotency key unique).
     */
    public function up(): void
    {
        Schema::create('payout_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained('campaign_settlements')->cascadeOnDelete();
            $table->foreignId('payout_account_id')->nullable()->constrained('payout_accounts')->nullOnDelete();
            $table->integer('attempt_number')->default(1);
            $table->string('idempotency_key')->unique();
            $table->string('gateway')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->enum('status', [
                'queued', 'initiated', 'accepted', 'processing',
                'completed', 'failed', 'reversed', 'unknown',
            ])->nullable();
            $table->string('request_payload_hash')->nullable();
            $table->string('response_payload_hash')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['settlement_id', 'attempt_number'], 'payout_attempts_settlement_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_attempts');
    }
};
