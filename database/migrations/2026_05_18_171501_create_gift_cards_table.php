<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->decimal('amount', 10, 2);
            $table->string('theme', 20)->default('purple');
            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('recipient_name');
            $table->string('recipient_email');
            $table->text('message')->nullable();
            $table->timestamp('send_at');
            $table->enum('status', ['pending','sent','redeemed','expired','cancelled'])
                  ->default('pending');
            $table->string('payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->enum('payment_status', ['pending','completed','failed'])
                  ->default('pending');
            $table->foreignId('redeemed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('redeemed_on_campaign')->nullable();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};