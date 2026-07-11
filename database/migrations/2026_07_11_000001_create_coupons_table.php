<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();

            // null user_id => public promo code usable by anyone
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // null campaign_id => valid for any campaign
            $table->foreignId('campaign_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->enum('discount_type', ['fixed', 'percent'])
                  ->default('fixed');

            $table->decimal('discount_value', 12, 2);

            $table->decimal('min_amount', 12, 2)->nullable();
            $table->decimal('max_discount', 12, 2)->nullable();

            // null usage_limit => unlimited redemptions
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);

            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);

            // single-use flag for assigned (user-specific) coupons
            $table->timestamp('redeemed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
