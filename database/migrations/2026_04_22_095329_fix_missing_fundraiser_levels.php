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
    $adminIds = DB::table('users')
        ->whereNotIn('id', DB::table('user_fundraiser_levels')->pluck('user_id'))
        ->pluck('id');

    foreach ($adminIds as $userId) {
        DB::table('user_fundraiser_levels')->insert([
            'user_id'           => $userId,
            'current_level_id'  => 1,
            'status'            => 'active',
            'level_upgraded_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
