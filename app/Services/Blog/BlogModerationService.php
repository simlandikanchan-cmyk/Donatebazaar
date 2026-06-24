<?php

namespace App\Services\Blog;

use App\Models\Blog;
use App\Models\BlogStatusLog;

class BlogModerationService
{
    public function approve(Blog $blog, int $adminId): Blog
    {
        $oldStatus = $blog->status;

        $blog->update([
            'status' => 'published',
            'published_at' => now(),
            'approved_at' => now(),
            'approved_by' => $adminId,
        ]);

        BlogStatusLog::create([
            'blog_id' => $blog->id,
            'changed_by' => $adminId,
            'from_status' => $oldStatus,
            'to_status' => 'published',
            'note' => 'Blog approved and published',
        ]);

        return $blog;
    }

    public function reject(
        Blog $blog,
        int $adminId,
        ?string $note = null
    ): Blog {

        $oldStatus = $blog->status;

        $blog->update([
            'status' => 'rejected',
        ]);

        BlogStatusLog::create([
            'blog_id' => $blog->id,
            'changed_by' => $adminId,
            'from_status' => $oldStatus,
            'to_status' => 'rejected',
            'note' => $note,
        ]);

        return $blog;
    }
}