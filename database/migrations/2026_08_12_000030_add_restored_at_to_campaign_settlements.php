<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (! Schema::hasColumn('campaign_settlements', 'restored_at')) {
                $table->timestamp('restored_at')->nullable()->after('paid_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (Schema::hasColumn('campaign_settlements', 'restored_at')) {
                $table->dropColumn('restored_at');
            }
        });
    }
};
