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
        Schema::table('job_posts', function (Blueprint $table) {
            // Department (Engineering, Design, Marketing, HR)
            $table->string('department')->nullable()->after('type');

            // Experience required (e.g. 2-5 Years)
            $table->string('experience_required')->nullable()->after('salary');

            // Required skills stored as JSON
            $table->json('skills')->nullable()->after('experience_required');

            // Number of open positions
            $table->unsignedInteger('vacancies')->default(1)->after('skills');

            // Highlight important jobs
            $table->boolean('featured')->default(false)->after('vacancies');

            // SEO metadata
            $table->string('meta_title')->nullable()->after('featured');
            $table->text('meta_description')->nullable()->after('meta_title');

            // Useful indexes
            $table->index('department');
            $table->index('featured');
            $table->index('application_deadline');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['department']);
            $table->dropIndex(['featured']);
            $table->dropIndex(['application_deadline']);
            $table->dropIndex(['published_at']);

            // Drop columns
            $table->dropColumn([
                'department',
                'experience_required',
                'skills',
                'vacancies',
                'featured',
                'meta_title',
                'meta_description',
            ]);
        });
    }
};