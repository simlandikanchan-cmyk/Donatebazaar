<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_products', function (Blueprint $table) {
            $table->integer('reserved_quantity')->default(0)->after('remaining_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_products', function (Blueprint $table) {
            $table->dropColumn('reserved_quantity');
        });
    }
};
