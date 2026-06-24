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
    Schema::table('donation_items', function (Blueprint $table) {
        $table->unsignedBigInteger('product_id')->nullable();
        $table->unsignedBigInteger('user_product_id')->nullable();

        $table->dropColumn(['productable_id', 'productable_type']);

        $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        $table->foreign('user_product_id')->references('id')->on('user_products')->nullOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
