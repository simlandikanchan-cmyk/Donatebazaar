<?php

namespace App\Actions\Blog;

use App\Models\Blog;
use App\Models\BlogView;

class RecordBlogViewAction
{
    public function execute(Blog $blog, ?int $userId, string $ip): void
    {
        $exists = BlogView::where('blog_id', $blog->id)
            ->where('ip_address', $ip)
            ->whereDate('created_at', today())
            ->exists();

        if ($exists) {
            return;
        }

        BlogView::create([
            'blog_id' => $blog->id,
            'user_id' => $userId,
            'ip_address' => $ip,
            'viewed_date' => today(),
        ]);

        $blog->increment('views_count');
    }
}