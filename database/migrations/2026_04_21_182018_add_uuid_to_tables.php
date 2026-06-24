<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', fn(Blueprint $t) => $t->dropColumn('uuid'));
        Schema::table('orders', fn(Blueprint $t) => $t->dropColumn('uuid'));
        Schema::table('blogs', fn(Blueprint $t) => $t->dropColumn('uuid'));
    }
};