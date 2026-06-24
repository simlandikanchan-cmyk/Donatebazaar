<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/fix_uuid_auto_generation.php

// In your fix_uuid_auto_generation migration
public function up()
{
    // Fix blogs
    $ids = DB::table('blogs')->whereNull('uuid')->pluck('id');
    foreach ($ids as $id) {
        DB::table('blogs')->where('id', $id)->update(['uuid' => Str::uuid()]);
    }

    // Fix campaigns
    $ids = DB::table('campaigns')->whereNull('uuid')->pluck('id');
    foreach ($ids as $id) {
        DB::table('campaigns')->where('id', $id)->update(['uuid' => Str::uuid()]);
    }

    // Fix orders
    $ids = DB::table('orders')->whereNull('uuid')->pluck('id');
    foreach ($ids as $id) {
        DB::table('orders')->where('id', $id)->update(['uuid' => Str::uuid()]);
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
