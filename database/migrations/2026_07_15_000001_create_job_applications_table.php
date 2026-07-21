<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The JobPostApplication model hard-codes $table = 'job_applications',
     * but the older create_job_post_applications_table migration only creates
     * 'job_post_applications'. On a fresh migrate the dashboard (which counts
     * JobPostApplication) and the job-applications admin screen 500 because the
     * table the model expects is missing. This guarantees it exists.
     */
    public function up(): void
    {
        if (Schema::hasTable('job_applications')) {
            return;
        }

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('cv_path')->nullable();
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['pending', 'shortlisted', 'rejected'])
                ->default('pending');
            $table->timestamps();

            $table->foreign('job_id')
                ->references('id')
                ->on('job_posts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Only drop if WE created it; leave a pre-existing table alone.
        if (Schema::hasTable('job_applications')
            && ! Schema::hasTable('job_post_applications')) {
            Schema::dropIfExists('job_applications');
        }
    }
};
