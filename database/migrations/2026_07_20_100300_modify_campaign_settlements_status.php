<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The status enum already has live production values
     * ('pending', 'processing', 'paid', 'failed'). We WIDEN it
     * (add members, never remove) so existing rows stay valid.
     * MariaDB allows adding enum members in place.
     */
    public function up(): void
    {
        if (Schema::hasColumn('campaign_settlements', 'status')) {
            DB::statement("ALTER TABLE campaign_settlements MODIFY COLUMN status ENUM('pending','processing','paid','failed','pending_approval','approved','rejected') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (! Schema::hasColumn('campaign_settlements', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('campaign_settlements', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (! Schema::hasColumn('campaign_settlements', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('campaign_settlements', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            if (! Schema::hasColumn('campaign_settlements', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
            if (! Schema::hasColumn('campaign_settlements', 'gateway_reference')) {
                $table->string('gateway_reference')->nullable()->after('rejection_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_settlements', function (Blueprint $table) {
            if (Schema::hasColumn('campaign_settlements', 'gateway_reference')) {
                $table->dropColumn('gateway_reference');
            }
            if (Schema::hasColumn('campaign_settlements', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('campaign_settlements', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            if (Schema::hasColumn('campaign_settlements', 'rejected_by')) {
                $table->dropForeign(['rejected_by']);
                $table->dropColumn('rejected_by');
            }
            if (Schema::hasColumn('campaign_settlements', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('campaign_settlements', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
        });

        // Narrow the enum back to the original set (only safe if no rows
        // use the new statuses — guarded by checking first).
        $usesNew = DB::table('campaign_settlements')
            ->whereIn('status', ['pending_approval', 'approved', 'rejected'])
            ->exists();

        if (! $usesNew && Schema::hasColumn('campaign_settlements', 'status')) {
            DB::statement("ALTER TABLE campaign_settlements MODIFY COLUMN status ENUM('pending','processing','paid','failed') NOT NULL DEFAULT 'pending'");
        }
    }
};
