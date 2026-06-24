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
    Schema::table('followers', function (Blueprint $table) {
        $table->string('following_type')->after('following_id')->default('user');

        $table->index(['follower_id', 'following_id', 'following_type']);
    });
}

public function down()
{
    Schema::table('followers', function (Blueprint $table) {
        $table->dropColumn('following_type');
    });
}
};
