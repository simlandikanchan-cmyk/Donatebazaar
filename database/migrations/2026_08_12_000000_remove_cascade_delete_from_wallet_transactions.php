<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign('wallet_transactions_wallet_id_foreign');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign('wallet_transactions_wallet_id_foreign');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets')
                ->onDelete('cascade');
        });
    }
};
