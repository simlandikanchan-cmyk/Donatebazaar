<?php

namespace App\Actions\Blog;

use App\Models\Blog;
use App\Models\BlogLike;

class ToggleBlogLikeAction
{
    public function execute(Blog $blog, int $userId): bool
    {
        $existing = BlogLike::where('blog_id', $blog->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {

            $existing->delete();

            $blog->decrement('likes_count');

            return false;
        }

        BlogLike::create([
            'blog_id' => $blog->id,
            'user_id' => $userId,
        ]);

        $blog->increment('likes_count');

        return true;
    }
}