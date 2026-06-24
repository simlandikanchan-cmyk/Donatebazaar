<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // CORRECT APPROACH (NO POLYMORPHIC)
            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->nullOnDelete();

            $table->foreignId('user_product_id')
                  ->nullable()
                  ->constrained('user_products')
                  ->nullOnDelete();

            $table->integer('quantity');
            $table->decimal('price', 10, 2);

            $table->timestamps();

            // Prevent duplicates values
            $table->unique(['order_id', 'product_id']);
            $table->unique(['order_id', 'user_product_id']);
        });


        
    }






public function down(): void {
Schema::dropIfExists('order_items');

}





};