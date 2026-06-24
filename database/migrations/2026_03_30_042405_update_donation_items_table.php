<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_items', function (Blueprint $table) {

            // Step 1: drop foreign key first
            $table->dropForeign(['product_id']);

            // Step 2: drop column
            $table->dropColumn('product_id');

            // Step 3: add polymorphic fields
            $table->unsignedBigInteger('productable_id')->after('donation_id');
            $table->string('productable_type')->after('productable_id');

            // Optional index (recommended)
            $table->index(['productable_id', 'productable_type'], 'donation_items_productable_index');
        });
    }

    public function down(): void
    {
        Schema::table('donation_items', function (Blueprint $table) {

            $table->dropIndex('donation_items_productable_index');

            $table->dropColumn(['productable_id', 'productable_type']);

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        });
    }
};