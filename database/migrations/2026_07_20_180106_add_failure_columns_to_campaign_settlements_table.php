<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (! Schema::hasColumn('campaign_settlements', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('gateway_reference');
            }
            if (! Schema::hasColumn('campaign_settlements', 'failed_at')) {
                $table->timestamp('failed_at')->nullable()->after('processed_at');
            }
            if (! Schema::hasColumn('campaign_settlements', 'failed_reason')) {
                $table->text('failed_reason')->nullable()->after('failed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (Schema::hasColumn('campaign_settlements', 'failed_reason')) {
                $table->dropColumn('failed_reason');
            }
            if (Schema::hasColumn('campaign_settlements', 'failed_at')) {
                $table->dropColumn('failed_at');
            }
            if (Schema::hasColumn('campaign_settlements', 'processed_at')) {
                $table->dropColumn('processed_at');
            }
        });
    }
};
