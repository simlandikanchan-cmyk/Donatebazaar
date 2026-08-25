<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Business rule: a Razorpay order maps to exactly one donation and a payment
     * id maps to exactly one donation. Both columns are nullable (pending
     * donations have neither yet) and MySQL permits multiple NULLs in a unique
     * index, so nullable unique indexes are safe for new and unpaid legacy rows.
     *
     * Duplicate non-null values are surfaced before the index is added so a
     * deploy against legacy data fails loudly instead of silently.
     */
    public function up(): void
    {
        if (! Schema::hasTable('donations')) {
            return;
        }

        if (Schema::hasColumn('donations', 'order_id')) {
            $total = DB::table('donations')->whereNotNull('order_id')->count();
            $distinct = DB::table('donations')->whereNotNull('order_id')->distinct()->count('order_id');

            if ($total !== $distinct) {
                throw new \RuntimeException('Cannot add unique index on donations.order_id: '.($total - $distinct).' duplicate order_id values exist. Clean them first.');
            }

            Schema::table('donations', function (Blueprint $table) {
                $table->unique('order_id', 'donations_order_id_unique');
            });
        }

        if (Schema::hasColumn('donations', 'payment_id')) {
            $total = DB::table('donations')->whereNotNull('payment_id')->count();
            $distinct = DB::table('donations')->whereNotNull('payment_id')->distinct()->count('payment_id');

            if ($total !== $distinct) {
                throw new \RuntimeException('Cannot add unique index on donations.payment_id: '.($total - $distinct).' duplicate payment_id values exist. Clean them first.');
            }

            Schema::table('donations', function (Blueprint $table) {
                $table->unique('payment_id', 'donations_payment_id_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('donations', 'order_id')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropUnique('donations_order_id_unique');
            });
        }

        if (Schema::hasColumn('donations', 'payment_id')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropUnique('donations_payment_id_unique');
            });
        }
    }
};
