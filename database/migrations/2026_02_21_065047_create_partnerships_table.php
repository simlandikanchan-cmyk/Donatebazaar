<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
Schema::create('partnerships', function (Blueprint $table) {
    $table->id();

    $table->string('name');
    $table->string('email');
    $table->string('phone')->nullable();

    $table->string('organization_name');
    $table->string('website')->nullable();

    $table->enum('partnership_type', [
        'csr',
        'event',
        'product',
        'corporate',
        'media',
        'other'
    ]);

    $table->text('message')->nullable();

    // Upload company document
    $table->string('document')->nullable();

    // Admin review
    $table->enum('status', [
        'pending',
        'approved',
        'rejected'
    ])->default('pending');

    $table->text('admin_notes')->nullable();

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('partnerships');
    }
};