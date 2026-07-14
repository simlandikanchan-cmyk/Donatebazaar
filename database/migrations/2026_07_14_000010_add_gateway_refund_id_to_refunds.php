<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            // donation_payments is unused/empty in this app, so the FK cannot
            // be satisfied — allow refunds without a donation_payment row.
            $table->foreignId('donation_payment_id')
                ->nullable()
                ->change();

            $table->string('gateway_refund_id', 255)
                ->nullable()
                ->unique()
                ->after('donation_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropUnique(['gateway_refund_id']);
            $table->dropColumn('gateway_refund_id');

            $table->foreignId('donation_payment_id')
                ->nullable(false)
                ->change();
        });
    }
};
