<?php

use App\Enums\NotificationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('notification_type', 100);
            $table->string('channel', 20);
            $table->boolean('enabled')->default(true);
            $table->string('frequency', 20)->default('immediate');
            $table->timestamps();

            $table->unique(['user_id', 'notification_type', 'channel'], 'uq_user_notif_type_channel');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
