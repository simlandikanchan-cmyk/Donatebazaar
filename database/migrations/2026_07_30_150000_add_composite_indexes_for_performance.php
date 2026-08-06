<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected function hasIndex(string $table, string $index): bool
    {
        return DB::selectOne("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]) !== null;
    }

    public function up(): void
    {
        if (! $this->hasIndex('donations', 'idx_donations_campaign_created')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->index(['campaign_id', 'created_at'], 'idx_donations_campaign_created');
            });
        }

        if (! $this->hasIndex('donations', 'idx_donations_status_created')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->index(['payment_status', 'created_at'], 'idx_donations_status_created');
            });
        }

        if (! $this->hasIndex('payments', 'idx_payments_status_created')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->index(['status', 'created_at'], 'idx_payments_status_created');
            });
        }

        if (! $this->hasIndex('wallet_transactions', 'idx_wallet_transactions_wallet_type_created')) {
            Schema::table('wallet_transactions', function (Blueprint $table) {
                $table->index(['wallet_id', 'type', 'created_at'], 'idx_wallet_transactions_wallet_type_created');
            });
        }

        if (! $this->hasIndex('events', 'idx_events_campaign_status_date')) {
            Schema::table('events', function (Blueprint $table) {
                $table->index(['campaign_id', 'status', 'event_date'], 'idx_events_campaign_status_date');
            });
        }
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropIndex('idx_donations_campaign_created');
            $table->dropIndex('idx_donations_status_created');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_status_created');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_wallet_transactions_wallet_type_created');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_campaign_status_date');
        });
    }
};
