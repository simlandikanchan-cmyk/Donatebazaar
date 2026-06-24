<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('partnerships', function (Blueprint $table) {

            // ── Organization Intelligence ──
            $table->string('organization_type')->nullable()->after('organization_name');
            $table->string('organization_size')->nullable()->after('organization_type');
            $table->string('location')->nullable()->after('organization_size');

            // ── Partnership Intent ──
            $table->string('goal')->nullable()->after('partnership_type');
            $table->string('timeline')->nullable()->after('goal');

            // ── Smart Scoring ──
            $table->integer('priority_score')->default(0)->after('status');

            // ── Admin Tracking ──
            $table->timestamp('reviewed_at')->nullable()->after('admin_notes');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('reviewed_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('partnerships', function (Blueprint $table) {

            $table->dropColumn([

                'organization_type',
                'organization_size',
                'location',
                'goal',
                'timeline',
                'priority_score',
                'reviewed_at',
                
            ]);

            $table->dropForeign(['reviewed_by']);
            $table->dropColumn('reviewed_by');
        });
    }
};