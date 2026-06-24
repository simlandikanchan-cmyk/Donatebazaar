<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * BLOG SUPPORT TABLES
 *
 * blog_tag           — pivot (reuses existing tags table)
 * blog_views         — unique view tracking (auth users + guest IP/date)
 * blog_likes         — unique like per user per blog
 * blog_comments      — threaded comments (parent_id for replies)
 *                      Uses existing polymorphic comments table pattern
 * blog_reports       — flag / report system
 * blog_status_logs   — full audit trail of every status change
 * blog_tag column    — NOT adding to tags; using dedicated pivot
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. blog_tag pivot ─────────────────────────────────────────────────
        Schema::create('blog_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->unique(['blog_id', 'tag_id']);
        });

        // ── 2. blog_views ─────────────────────────────────────────────────────
        Schema::create('blog_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->date('viewed_date');                    // one view per user/IP per day
            $table->timestamps();

            $table->index(['blog_id', 'viewed_date']);
            $table->unique(['blog_id', 'user_id', 'viewed_date'], 'uv_user_day');
            $table->unique(['blog_id', 'ip_address', 'viewed_date'], 'uv_ip_day');
        });

        // ── 3. blog_likes ─────────────────────────────────────────────────────
        Schema::create('blog_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['blog_id', 'user_id']);
        });

        // ── 4. blog_comments ──────────────────────────────────────────────────
        // Separate from the existing 'comments' polymorphic table for clean scoping
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('blog_comments')
                  ->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_approved')->default(true);  // auto-approve; false = held for moderation
            $table->softDeletes();
            $table->timestamps();

            $table->index('blog_id');
        });

        // ── 5. blog_reports ───────────────────────────────────────────────────
        Schema::create('blog_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->enum('reason', [
                'spam',
                'misinformation',
                'hate_speech',
                'inappropriate',
                'plagiarism',
                'other',
            ])->default('other');
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'dismissed'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['blog_id', 'reported_by']);    // one report per user per blog
            $table->index('status');
        });

        // ── 6. blog_status_logs — full audit trail ────────────────────────────
        Schema::create('blog_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['blog_id', 'created_at']);
        });

        // ── 7. Add verified_author flag to users (non-breaking) ───────────────
        // Instead of altering the role enum (dangerous), we add a flag column.
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_verified_author')) {
                $table->boolean('is_verified_author')->default(false)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_verified_author')) {
                $table->dropColumn('is_verified_author');
            }
        });

        Schema::dropIfExists('blog_status_logs');
        Schema::dropIfExists('blog_reports');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_likes');
        Schema::dropIfExists('blog_views');
        Schema::dropIfExists('blog_tag');
    }
};