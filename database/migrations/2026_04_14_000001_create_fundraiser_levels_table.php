<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fundraiser_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level_number')->unique()->comment('1=Starter, 2=Trusted, 3=Verified, 4=Champion');
            $table->string('level_name', 50);
            $table->string('description')->nullable();
            $table->decimal('max_goal_amount', 14, 2)->comment('Max campaign goal allowed at this level');
            $table->unsignedTinyInteger('max_active_campaigns')->default(1)->comment('Max simultaneous live campaigns');
            $table->unsignedTinyInteger('min_campaigns_completed')->default(0)->comment('Campaigns user must have completed to qualify');
            $table->decimal('min_raised_percent', 5, 2)->default(0.00)->comment('Min % of goal raised on previous campaign to qualify');
            $table->boolean('requires_admin_approval')->default(false)->comment('If true, admin must manually approve the upgrade');
            $table->enum('kyc_requirement', ['none', 'basic', 'full', 'org'])->default('none');
            $table->string('badge_color', 20)->nullable()->comment('UI hex color for badge');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fundraiser_levels');
    }
};