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
    Schema::create('volunteer_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('volunteer_id')->constrained()->cascadeOnDelete();
        $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('ngo_id')->nullable()->constrained()->nullOnDelete();
        $table->text('message')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamp('applied_at')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
