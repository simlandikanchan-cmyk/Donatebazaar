<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fundraiser_level_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('from_level_id')->nullable()->constrained('fundraiser_levels')->nullOnDelete();
            $table->foreignId('to_level_id')->constrained('fundraiser_levels');
            $table->string('reason')->nullable()->comment('e.g. Campaign #12 raised 78% of goal');
            $table->enum('triggered_by', ['system', 'admin'])->default('system');
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fundraiser_level_history');
    }
};