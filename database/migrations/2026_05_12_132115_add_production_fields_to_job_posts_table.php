<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title');
            $table->boolean('is_remote')->default(false)->after('location');
            $table->date('application_deadline')->nullable()->after('salary');
            $table->timestamp('published_at')->nullable()->after('status');
            $table->unsignedInteger('views_count')->default(0)->after('published_at');
            $table->unsignedInteger('applications_count')->default(0)->after('views_count');
            $table->softDeletes();

            $table->index('slug');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);

            $table->dropColumn([
                'slug',
                'is_remote',
                'application_deadline',
                'published_at',
                'views_count',
                'applications_count',
                'deleted_at',
            ]);
        });
    }
};