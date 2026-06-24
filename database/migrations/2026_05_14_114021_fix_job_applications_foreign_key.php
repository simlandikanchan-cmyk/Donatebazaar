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
    Schema::table('job_applications', function (Blueprint $table) {
        $table->dropForeign(['job_id']);
        $table->foreign('job_id')
              ->references('id')
              ->on('job_posts')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('job_applications', function (Blueprint $table) {
        $table->dropForeign(['job_id']);
        $table->foreign('job_id')
              ->references('id')
              ->on('job_listings')
              ->onDelete('cascade');
    });
}

};
