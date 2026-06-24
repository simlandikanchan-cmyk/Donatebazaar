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
    Schema::create('blog_metrics', function (Blueprint $table) {
        $table->unsignedBigInteger('blog_id')->primary();
        $table->unsignedBigInteger('views_count')->default(0);
        $table->unsignedBigInteger('likes_count')->default(0);
        $table->unsignedBigInteger('comments_count')->default(0);

        $table->foreign('blog_id')
              ->references('id')->on('blogs')
              ->cascadeOnDelete();
    });
}

public function down()
{
    Schema::dropIfExists('blog_metrics');
}

};
