<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('campaigns', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('campaigns', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');

    $table->string('title');
    $table->text('description');
    $table->string('slug')->unique();

    $table->string('cover_image')->nullable();
    $table->string('video_url')->nullable();

    $table->decimal('goal_amount', 12, 2);
    $table->decimal('raised_amount', 12, 2)->default(0);

    $table->foreignId('category_id')->nullable()->constrained();

    $table->string('location')->nullable();

    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();

    $table->boolean('is_featured')->default(false);
    $table->boolean('is_urgent')->default(false);

    $table->enum('status', ['pending','approved','rejected','completed'])->default('pending');

    $table->timestamps();
    $table->softDeletes();
   
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
