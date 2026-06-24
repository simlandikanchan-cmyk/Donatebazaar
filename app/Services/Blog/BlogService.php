<?php

namespace App\Services\Blog;

use App\Models\Blog;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogService
{
    /*
    |--------------------------------------------------------------------------
    | Create Blog
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data,
        int $userId,
        Request $request
    ): Blog {

        // Generate slug
        $slug = Str::slug($data['title']);

        $count = Blog::where(
            'slug',
            'LIKE',
            $slug . '%'
        )->count();

        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $data['slug'] = $slug;

        // Upload image
        if ($request->hasFile('cover_image')) {

            $data['cover_image'] = $request
                ->file('cover_image')
                ->store(
                    'blogs/covers',
                    'public'
                );
        }

        // User
        $user = auth()->user();

        // Status
        if ($request->action === 'draft') {

            $data['status'] = Blog::STATUS_DRAFT;

        } elseif ($request->action === 'publish') {

            $data['status'] = $user->is_verified_author
                ? Blog::STATUS_PUBLISHED
                : Blog::STATUS_PENDING;

        } else {

            $submitNow = $request->boolean('submit_now');

            $data['status'] = $this->resolveInitialStatus(
                $user,
                $submitNow
            );
        }

        // Author
        $data['author_id'] = $userId;
        $data['author_role'] = $user->role;

        // Remove tags
        unset($data['tag_ids']);

        // Create
        $blog = Blog::create($data);

        // Sync tags
        if ($request->filled('tag_ids')) {

            $blog->tags()->sync(
                $request->input('tag_ids')
            );
        }

        // Log
        $blog->statusLogs()->create([
            'changed_by' => $userId,
            'from_status' => null,
            'to_status' => $blog->status,
            'note' => 'Blog created.',
        ]);

        return $blog;
    }

    /*
    |--------------------------------------------------------------------------
    | Update Blog
    |--------------------------------------------------------------------------
    */

    public function update(
        Blog $blog,
        array $data,
        Request $request
    ): Blog {

        // Image
        if ($request->hasFile('cover_image')) {

            if ($blog->cover_image) {

                Storage::disk('public')
                    ->delete($blog->cover_image);
            }

            $data['cover_image'] = $request
                ->file('cover_image')
                ->store(
                    'blogs/covers',
                    'public'
                );
        }

        // Slug
        if (isset($data['title'])) {

            $slug = Str::slug($data['title']);

            $count = Blog::where(
                'slug',
                'LIKE',
                $slug . '%'
            )

            ->where(
                'id',
                '!=',
                $blog->id
            )

            ->count();

            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }

            $data['slug'] = $slug;
        }

        // Status
        $user = auth()->user();

        if ($request->action === 'draft') {

            $data['status'] = Blog::STATUS_DRAFT;

        } elseif ($request->action === 'publish') {

            $data['status'] = $user->is_verified_author
                ? Blog::STATUS_PUBLISHED
                : Blog::STATUS_PENDING;

        } else {

            $submitNow = $request->boolean('submit_now');

            $data['status'] = $this->resolveInitialStatus(
                $user,
                $submitNow
            );
        }

        unset($data['tag_ids']);

        $blog->update($data);

        // Tags
        if ($request->filled('tag_ids')) {

            $blog->tags()->sync(
                $request->input('tag_ids')
            );
        }

        return $blog->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Submit Blog
    |--------------------------------------------------------------------------
    */

    public function submit(
        Blog $blog,
        $user
    ): Blog {

        $newStatus = $user->is_verified_author
            ? Blog::STATUS_PUBLISHED
            : Blog::STATUS_PENDING;

        $blog->transitionTo(
            $newStatus,
            $user->id,
            'Submitted by author.'
        );

        return $blog->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Status
    |--------------------------------------------------------------------------
    */

    private function resolveInitialStatus(
        $user,
        bool $submitNow
    ): string {

        if (!$submitNow) {
            return Blog::STATUS_DRAFT;
        }

        if (
            $user->role === 'admin'
            || $user->is_verified_author
        ) {
            return Blog::STATUS_PUBLISHED;
        }

        return Blog::STATUS_PENDING;
    }
}