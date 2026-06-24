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
        Schema::create('campaign_media', function (Blueprint $table) {
    $table->id();

    $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();

    $table->string('file_url');

    $table->enum('type', ['cover','image','video'])->default('image');

    $table->boolean('is_primary')->default(false); // cover image

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_media');
    }
};
