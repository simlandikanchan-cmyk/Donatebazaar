<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('notifications')) {
        return;
    }

    if (config('database.default') === 'sqlite') {
        return;
    }

    Schema::table('notifications', function (Blueprint $table) {
        $table->string('type')->change();
    });
}

    public function down(): void
{
    if (!Schema::hasTable('notifications')) {
        return;
    }

    if (config('database.default') === 'sqlite') {
        return;
    }

    Schema::table('notifications', function (Blueprint $table) {
        $table->enum('type', [
            'like',
            'comment',
            'follow',
            'donation',
            'message',
            'order'
        ])->change();
    });
}
};