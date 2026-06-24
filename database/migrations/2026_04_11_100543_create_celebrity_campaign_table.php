<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A celebrity can endorse/support many campaigns,
     * and a campaign can have many celebrity endorsers.
     */
    public function up(): void
    {
        Schema::create('celebrity_campaign', function (Blueprint $table) {
            $table->id();

            $table->foreignId('celebrity_id')
                  ->constrained('celebrities')
                  ->cascadeOnDelete();

            $table->foreignId('campaign_id')
                  ->constrained('campaigns')
                  ->cascadeOnDelete();

            // Role of the celebrity in this campaign
            $table->enum('role', ['endorser', 'ambassador', 'organizer', 'donor'])
                  ->default('endorser');

            $table->text('message')->nullable();   // Optional personal message
            $table->boolean('is_active')->default(true);
            $table->timestamp('endorsed_at')->nullable();

            $table->timestamps();

            // Prevent duplicate rows
            $table->unique(['celebrity_id', 'campaign_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('celebrity_campaign');
    }
};