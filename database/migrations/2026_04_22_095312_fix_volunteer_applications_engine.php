<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/fix_volunteer_applications_engine.php
public function up()
{
    DB::statement('ALTER TABLE volunteer_applications ENGINE=InnoDB');
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
