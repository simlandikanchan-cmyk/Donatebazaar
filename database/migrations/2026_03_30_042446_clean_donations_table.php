<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {

            $table->dropColumn([
                'payment_id',
                'order_id',
                'payment_method',
                'payment_gateway'
            ]);

            // OPTIONAL: keep payment_status OR remove it also
            // $table->dropColumn('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {

            $table->string('payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_gateway')->nullable();
        });
    }
};