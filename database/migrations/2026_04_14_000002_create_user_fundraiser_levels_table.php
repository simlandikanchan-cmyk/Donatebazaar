<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_fundraiser_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('current_level_id')->default(1)->constrained('fundraiser_levels');
            $table->unsignedInteger('total_campaigns_completed')->default(0);
            $table->decimal('total_amount_raised', 14, 2)->default(0.00);
            $table->timestamp('upgrade_requested_at')->nullable()->comment('When user last requested an upgrade');
            $table->foreignId('upgrade_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('upgrade_reviewed_at')->nullable();
            $table->timestamp('level_upgraded_at')->nullable();
            $table->enum('status', ['active', 'upgrade_pending', 'suspended'])->default('active');
            $table->text('notes')->nullable()->comment('Admin notes on the user level');
            $table->timestamps();

            $table->unique('user_id');
            $table->index('current_level_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_fundraiser_levels');
    }
};