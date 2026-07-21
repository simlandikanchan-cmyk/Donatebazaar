<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 — Additive.
     * Create settlement_metadata (schema-free provider-specific metadata).
     */
    public function up(): void
    {
        Schema::create('settlement_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained('campaign_settlements')->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index(['settlement_id', 'key'], 'settlement_metadata_settlement_key_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlement_metadata');
    }
};
