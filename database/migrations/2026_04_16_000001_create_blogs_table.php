<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * BLOGS TABLE
 *
 * Extends existing DonateBazaar schema. Does NOT alter users, categories, or tags tables.
 *
 * Roles mapped to existing users.role enum: 'admin' | 'ngo' | 'donor'
 * verified_author is stored as a separate column (not on users.role) to avoid
 * breaking existing role-based guards.
 *
 * Status flow:
 *   draft → pending → approved → published → archived
 *                  ↘ rejected  (author can re-edit → pending)
 *   Any approved/published blog can be → flagged (still visible, admin notified)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            // ── Identity ─────────────────────────────────────────────────────
            $table->id();
            $table->foreignId('author_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Snapshot of the user's role at creation time (preserved if role changes)
            $table->enum('author_role', ['admin', 'ngo', 'donor'])->default('donor');

            // ── Content ──────────────────────────────────────────────────────
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('cover_image')->nullable();
            $table->unsignedTinyInteger('read_time_minutes')->nullable();

            // ── Categorisation ───────────────────────────────────────────────
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->onDelete('set null');

            // ── Workflow ─────────────────────────────────────────────────────
            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'rejected',
                'published',
                'archived',
                'flagged',
            ])->default('draft')->index();

            // ── Moderation ───────────────────────────────────────────────────
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // ── Carousel / Feature ───────────────────────────────────────────
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedSmallInteger('carousel_order')->default(0);
            $table->timestamp('featured_at')->nullable();

            // ── Engagement (denormalised for perf) ───────────────────────────
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('shares_count')->default(0);
            $table->unsignedInteger('reports_count')->default(0);

            // ── SEO ──────────────────────────────────────────────────────────
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // ── Publishing ───────────────────────────────────────────────────
            $table->timestamp('published_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        // Full-text search index on title + excerpt + content
        DB::statement(
            'ALTER TABLE blogs ADD FULLTEXT INDEX blogs_fulltext_idx (title, excerpt, content)'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};