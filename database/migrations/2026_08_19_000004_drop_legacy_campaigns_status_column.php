<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The campaign lifecycle is driven by `campaign_state` (see Campaign model
     * STATE_* constants). `campaigns.status` was a legacy enum that is no longer
     * read or written by any application code (the only reference was a removed
     * admin partial). Drop it to remove the ambiguity.
     */
    public function up(): void
    {
        if (Schema::hasColumn('campaigns', 'status')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('campaigns', 'status')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->enum('status', [
                    'pending', 'active', 'paused', 'expired', 'rejected', 'completed',
                ])->default('pending');
            });
        }
    }
};
