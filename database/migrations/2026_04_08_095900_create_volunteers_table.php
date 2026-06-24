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
    Schema::create('volunteers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->text('bio')->nullable();
        $table->json('skills')->nullable();
        $table->enum('availability', ['full_time', 'part_time', 'weekends'])->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('country')->default('India');
        $table->boolean('is_verified')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
