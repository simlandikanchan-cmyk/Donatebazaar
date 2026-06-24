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
        Schema::create('shipments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();

    $table->string('tracking_id')->nullable();
    $table->string('carrier')->nullable();

    $table->enum('status', [
        'pending',
        'shipped',
        'in_transit',
        'delivered',
        'cancelled'
    ])->default('pending');

    $table->timestamp('shipped_at')->nullable();
    $table->timestamp('delivered_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
