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
    Schema::create('volunteer_assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('volunteer_id')->constrained()->cascadeOnDelete();
        $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
        $table->string('role')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->enum('status', ['active', 'completed'])->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_assignments');
    }
};
