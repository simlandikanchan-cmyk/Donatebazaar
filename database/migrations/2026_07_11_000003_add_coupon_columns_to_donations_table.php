<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Intended amount before any discount (equals total_amount when no coupon)
            $table->decimal('original_amount', 12, 2)
                  ->nullable()
                  ->after('total_amount');

            $table->decimal('discount_amount', 12, 2)
                  ->default(0)
                  ->after('original_amount');

            $table->foreignId('coupon_id')
                  ->nullable()
                  ->constrained('coupons')
                  ->nullOnDelete()
                  ->after('discount_amount');

            $table->string('coupon_code')
                  ->nullable()
                  ->after('coupon_id');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn([
                'original_amount',
                'discount_amount',
                'coupon_id',
                'coupon_code',
            ]);
        });
    }
};
