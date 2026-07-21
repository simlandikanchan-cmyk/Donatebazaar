<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0.1 — FINAL schema optimization (indexes only).
     * Additive, online-safe, rollback-safe. No business logic.
     *
     * Uniqueness decisions (documented in Phase 0.1 review):
     *  - correlation_id: NOT globally unique (one settlement = one id, but
     *    we intentionally allow re-evaluation/correlation reuse across retries;
     *    uniqueness would block legitimate retry flows). Indexed, not unique.
     *  - trace_id: NOT unique (spans across multiple systems/rows). Indexed only.
     *  - idempotency_key: UNIQUE (enforced in payout_attempts already).
     *  - gateway_reference: NOT unique (provider may reuse/retry refs; the
     *    idempotency_key is the true dedupe guard). Indexed for lookup.
     */
    public function up(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (! Schema::hasIndex('campaign_settlements', 'cs_status_index')) {
                $table->index('status', 'cs_status_index');
            }
            if (! Schema::hasIndex('campaign_settlements', 'cs_status_gateway_index')) {
                $table->index(['status', 'gateway_status'], 'cs_status_gateway_index');
            }
            if (! Schema::hasIndex('campaign_settlements', 'cs_next_retry_index')) {
                $table->index('next_retry_at', 'cs_next_retry_index');
            }
            // correlation_id already indexed in Phase 0; add trace_id.
            if (! Schema::hasIndex('campaign_settlements', 'cs_trace_id_index')) {
                $table->index('trace_id', 'cs_trace_id_index');
            }
        });

        Schema::table('risk_scores', function (Blueprint $table) {
            if (! Schema::hasIndex('risk_scores', 'rs_evaluated_at_index')) {
                $table->index('evaluated_at', 'rs_evaluated_at_index');
            }
            // (settlement_id, evaluated_at) is covered by existing
            // (settlement_id, created_at) composite for most access patterns;
            // add explicit composite for evaluation-time reporting.
            if (! Schema::hasIndex('risk_scores', 'rs_settlement_evaluated_index')) {
                $table->index(['settlement_id', 'evaluated_at'], 'rs_settlement_evaluated_index');
            }
        });

        Schema::table('payout_attempts', function (Blueprint $table) {
            if (! Schema::hasIndex('payout_attempts', 'pa_settlement_status_index')) {
                $table->index(['settlement_id', 'status'], 'pa_settlement_status_index');
            }
            if (! Schema::hasIndex('payout_attempts', 'pa_gateway_reference_index')) {
                $table->index('gateway_reference', 'pa_gateway_reference_index');
            }
            // idempotency_key already UNIQUE in Phase 0; no change.
        });

        Schema::table('settlement_state_logs', function (Blueprint $table) {
            if (! Schema::hasIndex('settlement_state_logs', 'ssl_settlement_created_index')) {
                $table->index(['settlement_id', 'created_at'], 'ssl_settlement_created_index');
            }
            // correlation_id already indexed in Phase 0; add trace_id.
            if (! Schema::hasIndex('settlement_state_logs', 'ssl_trace_id_index')) {
                $table->index('trace_id', 'ssl_trace_id_index');
            }
        });

        Schema::table('risk_rule_logs', function (Blueprint $table) {
            if (! Schema::hasIndex('risk_rule_logs', 'rrl_created_at_index')) {
                $table->index('created_at', 'rrl_created_at_index');
            }
            // risk_score_id already indexed via FK in Phase 0; no change.
        });
    }

    public function down(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (Schema::hasIndex('campaign_settlements', 'cs_status_index')) {
                $table->dropIndex('cs_status_index');
            }
            if (Schema::hasIndex('campaign_settlements', 'cs_status_gateway_index')) {
                $table->dropIndex('cs_status_gateway_index');
            }
            if (Schema::hasIndex('campaign_settlements', 'cs_next_retry_index')) {
                $table->dropIndex('cs_next_retry_index');
            }
            if (Schema::hasIndex('campaign_settlements', 'cs_trace_id_index')) {
                $table->dropIndex('cs_trace_id_index');
            }
        });

        Schema::table('risk_scores', function (Blueprint $table) {
            if (Schema::hasIndex('risk_scores', 'rs_evaluated_at_index')) {
                $table->dropIndex('rs_evaluated_at_index');
            }
            if (Schema::hasIndex('risk_scores', 'rs_settlement_evaluated_index')) {
                $table->dropIndex('rs_settlement_evaluated_index');
            }
        });

        Schema::table('payout_attempts', function (Blueprint $table) {
            if (Schema::hasIndex('payout_attempts', 'pa_settlement_status_index')) {
                $table->dropIndex('pa_settlement_status_index');
            }
            if (Schema::hasIndex('payout_attempts', 'pa_gateway_reference_index')) {
                $table->dropIndex('pa_gateway_reference_index');
            }
        });

        Schema::table('settlement_state_logs', function (Blueprint $table) {
            if (Schema::hasIndex('settlement_state_logs', 'ssl_settlement_created_index')) {
                $table->dropIndex('ssl_settlement_created_index');
            }
            if (Schema::hasIndex('settlement_state_logs', 'ssl_trace_id_index')) {
                $table->dropIndex('ssl_trace_id_index');
            }
        });

        Schema::table('risk_rule_logs', function (Blueprint $table) {
            if (Schema::hasIndex('risk_rule_logs', 'rrl_created_at_index')) {
                $table->dropIndex('rrl_created_at_index');
            }
        });
    }
};
