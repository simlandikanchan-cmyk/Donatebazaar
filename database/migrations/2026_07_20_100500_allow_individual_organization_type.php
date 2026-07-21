<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Standalone fundraisers (plain users without a registered NGO) still
     * need a campaign_settlements row to request a payout, and that table is
     * org-scoped (organization_id). We let such users auto-get a personal
     * "individual" organization. Widen the type enum to allow 'individual'
     * and make it nullable so a personal org can be created without faking
     * an NGO legal type.
     */
    public function up(): void
    {
        if (Schema::hasColumn('organizations', 'type')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->enum('type', ['trust', 'society', 'section8', 'individual'])
                    ->nullable()
                    ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('organizations', 'type')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->enum('type', ['trust', 'society', 'section8'])
                    ->nullable(false)
                    ->change();
            });
        }
    }
};
