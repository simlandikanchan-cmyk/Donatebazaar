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
    Schema::create('donation_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('donation_id')->constrained()->cascadeOnDelete();
        $table->decimal('amount', 12, 2);
        $table->string('transaction_id')->unique();
        $table->string('payment_gateway')->nullable();
        $table->enum('status', ['pending','success','failed','refunded'])->default('pending');
        $table->json('meta')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_payments');
    }
};
