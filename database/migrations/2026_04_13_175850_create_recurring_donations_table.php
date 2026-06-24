<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('frequency', ['weekly', 'monthly']);
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');
            $table->string('razorpay_subscription_id')->nullable();
            $table->string('razorpay_plan_id')->nullable();
            $table->timestamp('next_billing_date')->nullable();
            $table->timestamp('last_billed_at')->nullable();
            $table->integer('billing_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_donations');
    }
};