<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Keep ID 1, delete ID 2 (duplicate "Kolkata")
    // First re-point any references to ID 2 → ID 1
    DB::table('users')->where('location_id', 2)->update(['location_id' => 1]);
    DB::table('campaigns')->where('location_id', 2)->update(['location_id' => 1]);
    DB::table('events')->where('location_id', 2)->update(['location_id' => 1]);
    DB::table('ngos')->where('location_id', 2)->update(['location_id' => 1]);
    
    DB::table('locations')->where('id', 2)->delete();
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
