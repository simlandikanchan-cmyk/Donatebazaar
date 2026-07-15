<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations.
     */
    public function up(): void
    {
        Schema::create('product_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('campaign_products')
                ->cascadeOnDelete();

            $table->integer('quantity')->default(1);

            $table->string('session_id')->nullable();

            $table->foreignId('donation_id')
                ->nullable()
                ->constrained('donations')
                ->nullOnDelete();

            $table->timestamp('expires_at');

            $table->timestamps();

            $table->index(['product_id', 'expires_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reservations');
    }
};
