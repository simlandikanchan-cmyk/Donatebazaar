<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasIndex('donations', 'donations_paid_at_index')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->index('paid_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropIndex('donations_paid_at_index');
        });
    }
};
