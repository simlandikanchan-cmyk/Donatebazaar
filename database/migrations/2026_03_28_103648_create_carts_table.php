<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // User relation
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Optional: for guest users (future-ready)
            $table->string('session_id')->nullable();

            // Cart status (important for checkout flow)
            $table->enum('status', ['active', 'converted', 'abandoned'])
                  ->default('active');

            // Total (optional but useful)
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};