<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Add risk + correlation + retry columns to campaign_settlements.
     *
     * NOTE: triggered_rules is intentionally NOT stored here.
     * It lives in risk_rule_logs (source of truth).
     */
    public function up(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (! Schema::hasColumn('campaign_settlements', 'correlation_id')) {
                $table->string('correlation_id')->nullable()->after('gateway_status')->index();
            }
            if (! Schema::hasColumn('campaign_settlements', 'trace_id')) {
                $table->string('trace_id')->nullable()->after('correlation_id');
            }
            if (! Schema::hasColumn('campaign_settlements', 'risk_score')) {
                $table->integer('risk_score')->nullable()->after('trace_id');
            }
            if (! Schema::hasColumn('campaign_settlements', 'risk_verdict')) {
                $table->enum('risk_verdict', [
                    'auto_approved', 'manual_review', 'rejected',
                ])->nullable()->after('risk_score');
            }
            if (! Schema::hasColumn('campaign_settlements', 'risk_version')) {
                $table->integer('risk_version')->nullable()->after('risk_verdict');
            }
            if (! Schema::hasColumn('campaign_settlements', 'evaluated_at')) {
                $table->timestamp('evaluated_at')->nullable()->after('risk_version');
            }
            if (! Schema::hasColumn('campaign_settlements', 'payout_account_id')) {
                $table->foreignId('payout_account_id')->nullable()->after('evaluated_at')
                    ->constrained('payout_accounts')->nullOnDelete();
            }
            if (! Schema::hasColumn('campaign_settlements', 'retry_count')) {
                $table->integer('retry_count')->default(0)->after('payout_account_id');
            }
            if (! Schema::hasColumn('campaign_settlements', 'next_retry_at')) {
                $table->timestamp('next_retry_at')->nullable()->after('retry_count');
            }

            if (! Schema::hasIndex('campaign_settlements', 'campaign_settlements_risk_verdict_status_index')) {
                $table->index(['risk_verdict', 'status'], 'campaign_settlements_risk_verdict_status_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            $cols = [
                'next_retry_at', 'retry_count', 'payout_account_id',
                'evaluated_at', 'risk_version', 'risk_verdict',
                'risk_score', 'trace_id', 'correlation_id',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('campaign_settlements', $col)) {
                    if ($col === 'payout_account_id') {
                        $table->dropForeign(['payout_account_id']);
                    }
                    $table->dropColumn($col);
                }
            }
        });
    }
};
