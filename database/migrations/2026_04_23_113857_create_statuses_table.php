<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
public function up(): void
{
    if (!Schema::hasTable('statuses')) {

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

    }
}

    public function down(): void
    {
        // DO NOT drop in production upgrades
    }
};