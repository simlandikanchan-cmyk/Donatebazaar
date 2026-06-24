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
    Schema::create('cart_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('cart_id')
              ->constrained()
              ->cascadeOnDelete();

        //  REMOVE polymorphic
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

        // ✅ prevent duplicates
        $table->unique(['cart_id', 'product_id']);
        $table->unique(['cart_id', 'user_product_id']);
    });
}
};