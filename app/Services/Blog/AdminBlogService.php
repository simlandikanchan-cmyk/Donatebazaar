<?php

namespace App\Services\Blog;

use App\Mail\BlogStatusMail;
use App\Models\Blog;
use Illuminate\Support\Facades\Mail;

class AdminBlogService
{
    public function approve(Blog $blog, int $adminId, ?string $note = null): Blog
    {
        $blog->transitionTo(Blog::STATUS_PUBLISHED, $adminId, $note);

        try {
            Mail::to($blog->author)->send(new BlogStatusMail($blog, 'published'));
        } catch (\Throwable $e) {
            \Log::warning('Blog approve mail failed: '.$e->getMessage());
        }

        return $blog;
    }

    public function reject(Blog $blog, int $adminId, string $reason): Blog
    {
        $blog->transitionTo(Blog::STATUS_REJECTED, $adminId, $reason);

        Mail::to($blog->author)->send(new BlogStatusMail($blog, 'rejected', $reason));

        return $blog;
    }

    public function toggleFeature(Blog $blog): array
    {
        if ($blog->is_featured) {
            $blog->update(['is_featured' => false, 'featured_at' => null]);
            $msg = 'Blog removed from carousel.';
        } else {
            $maxOrder = Blog::where('is_featured', true)->max('carousel_order') ?? 0;
            $blog->update([
                'is_featured' => true,
                'carousel_order' => $maxOrder + 1,
                'featured_at' => now(),
            ]);
            $msg = 'Blog added to carousel.';
        }

        return ['blog' => $blog->fresh(), 'message' => $msg];
    }

    public function archive(Blog $blog, int $adminId): Blog
    {
        $blog->transitionTo(Blog::STATUS_ARCHIVED, $adminId, 'Archived by admin.');

        return $blog;
    }

    public function flag(Blog $blog, int $adminId, ?string $note = null): Blog
    {
        $blog->transitionTo(
            Blog::STATUS_FLAGGED,
            $adminId,
            $note ?? 'Manually flagged by admin.'
        );

        return $blog;
    }

    public function bulkPublish(array $ids, int $adminId): array
    {
        $pending = Blog::with('author')
            ->whereIn('id', $ids)
            ->where('status', Blog::STATUS_PENDING)
            ->get();

        Blog::whereIn('id', $ids)->update([
            'status' => Blog::STATUS_PUBLISHED,
            'published_at' => now(),
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);

        foreach ($pending as $blog) {
            try {
                Mail::to($blog->author)->send(new BlogStatusMail($blog, 'published'));
            } catch (\Throwable $e) {
                \Log::warning('Bulk publish mail failed: '.$e->getMessage());
            }
        }

        return ['count' => count($ids), 'pending_count' => $pending->count()];
    }
}
