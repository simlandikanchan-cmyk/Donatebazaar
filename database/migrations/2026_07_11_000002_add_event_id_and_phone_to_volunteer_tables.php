<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            if (! Schema::hasColumn('volunteers', 'phone')) {
                $table->string('phone', 20)->nullable()->after('user_id');
            }
        });

        Schema::table('volunteer_assignments', function (Blueprint $table) {
            if (! Schema::hasColumn('volunteer_assignments', 'event_id')) {
                $table->foreignId('event_id')->after('volunteer_id')->nullable()->constrained()->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::table('volunteer_assignments', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};
