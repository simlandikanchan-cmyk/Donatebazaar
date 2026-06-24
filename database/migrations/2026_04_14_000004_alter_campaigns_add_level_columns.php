<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreignId('required_level_id')
                ->nullable()
                ->after('paused_at')
                ->constrained('fundraiser_levels')
                ->nullOnDelete()
                ->comment('Level that was required when this campaign was created');

            $table->foreignId('level_override_by')
                ->nullable()
                ->after('required_level_id')
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Admin who bypassed the level check');

            $table->timestamp('level_override_at')
                ->nullable()
                ->after('level_override_by');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropConstrainedForeignId('required_level_id');
            $table->dropConstrainedForeignId('level_override_by');
            $table->dropColumn('level_override_at');
        });
    }
};