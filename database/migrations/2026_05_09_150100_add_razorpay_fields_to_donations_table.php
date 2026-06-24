<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {

            $table->string('order_id')->nullable()->after('total_amount');
            $table->string('payment_id')->nullable()->after('order_id');
            $table->string('signature')->nullable()->after('payment_id');

        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {

            $table->dropColumn([
                'order_id',
                'payment_id',
                'signature'
            ]);

        });
    }
};