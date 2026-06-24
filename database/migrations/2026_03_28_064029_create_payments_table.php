<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->decimal('amount', 12, 2);

            // IMPORTANT (restore this)
            $table->morphs('payable'); 

            $table->string('payment_gateway')->nullable();

            // FIXED
            $table->string('transaction_id')->unique();

            //FIXED
            $table->enum('status', ['pending','success','failed','refunded'])
                  ->default('pending');

            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};