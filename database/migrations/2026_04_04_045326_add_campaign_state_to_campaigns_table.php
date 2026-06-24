<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('campaigns', function (Blueprint $table) {
        $table->enum('campaign_state', ['active', 'paused'])
              ->default('active')
              ->after('status');

        $table->text('pause_reason')->nullable();
        $table->timestamp('paused_at')->nullable();
    });
}

public function down(): void
{
    Schema::table('campaigns', function (Blueprint $table) {
        $table->dropColumn(['campaign_state', 'pause_reason', 'paused_at']);
    });
}
};
