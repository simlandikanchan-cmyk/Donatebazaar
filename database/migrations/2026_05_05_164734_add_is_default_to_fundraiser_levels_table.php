<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the column only if it does not already exist
        if (!Schema::hasColumn('fundraiser_levels', 'is_default')) {
            Schema::table('fundraiser_levels', function (Blueprint $table) {
                $table->boolean('is_default')->default(false);
            });
        }

        // Ensure at least one row is marked as default
        $hasDefault = DB::table('fundraiser_levels')
            ->where('is_default', true)
            ->exists();

        if (! $hasDefault) {
            $firstLevel = DB::table('fundraiser_levels')
                ->orderBy('id')
                ->first();

            if ($firstLevel) {
                DB::table('fundraiser_levels')
                    ->where('id', $firstLevel->id)
                    ->update(['is_default' => true]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('fundraiser_levels', 'is_default')) {
            Schema::table('fundraiser_levels', function (Blueprint $table) {
                $table->dropColumn('is_default');
            });
        }
    }
};