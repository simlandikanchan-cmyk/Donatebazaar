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
        Schema::create('donations', function (Blueprint $table) {
    $table->id();
    $table->index('payment_status');
    $table->index('donation_type');
    // Campaign Relation
    $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();

    // User Relation (nullable for guest donation)
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

    // Donor Info (for guest or custom name)
    $table->string('donor_name')->nullable();
    $table->string('donor_email')->nullable();
    $table->string('donor_phone')->nullable();

    // Hybrid Type
    $table->enum('donation_type', ['money', 'product'])->default('money');

    // Amount
    $table->decimal('total_amount', 12, 2);

    // Payment Information
    $table->string('payment_id')->nullable();
    $table->string('order_id')->nullable(); // Razorpay order id
    $table->string('payment_method')->nullable(); // card, upi, netbanking
    $table->string('payment_gateway')->nullable(); // razorpay, stripe
    $table->string('payment_status')->default('pending'); // pending, completed, failed, refunded

    // Receipt & Invoice
    $table->string('receipt_number')->nullable();
    $table->timestamp('paid_at')->nullable();

    // Refund Support
    $table->boolean('is_refunded')->default(false);
    $table->timestamp('refunded_at')->nullable();

    // Anonymous Option
    $table->boolean('is_anonymous')->default(false);

    // Notes (admin or donor message)
    $table->text('message')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
