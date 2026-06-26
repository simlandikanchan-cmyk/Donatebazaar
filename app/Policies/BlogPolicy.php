<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * BlogPolicy
 *
 * Register in AuthServiceProvider:
 *   protected $policies = [
 *       Blog::class => BlogPolicy::class,
 *   ];
 *
 * Role mapping (existing DonateBazaar users table):
 *   role = 'admin'  → full access
 *   role = 'ngo'    → create, edit own, submit
 *   role = 'donor'  → create, edit own, submit
 *   is_verified_author = true → can publish directly without admin approval
 */
class BlogPolicy
{
    use HandlesAuthorization;

    // ── Helper: is admin ──────────────────────────────────────────────────────

    private function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    // ── viewAny — list blogs ──────────────────────────────────────────────────

    /**
     * Users can see their own blogs list.
     */
    public function viewAny(User $user): bool
    {
        return true; // all authenticated users can access their dashboard
    }

    // ── view ─────────────────────────────────────────────────────────────────

    /**
     * Users can see their own blogs. Admins can see all.
     */
public function view(User $user, Blog $blog): bool
{
    // Admin can view all
    if ($this->isAdmin($user)) {
        return true;
    }

    // Owner can view own blogs
    if ($blog->author_id === $user->id) {
        return true;
    }

    // Public published blogs
    return $blog->status === Blog::STATUS_PUBLISHED;
}

    // ── create ────────────────────────────────────────────────────────────────

    public function create(User $user): bool
    {
        // Active, non-suspended users can create
        // return $user->status === 'active';

        return $user->status === 'active'
    && in_array($user->role, ['ngo', 'donor', 'admin']);

    }

    // ── update ────────────────────────────────────────────────────────────────

    /**
     * Can edit only draft or rejected blogs. Admins can edit any.
     */
    public function update(User $user, Blog $blog): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $blog->author_id === $user->id
            && in_array($blog->status, [Blog::STATUS_DRAFT, Blog::STATUS_REJECTED]);
    }

    // ── delete ────────────────────────────────────────────────────────────────

    /**
     * Users can only delete their own draft blogs. Admins can soft-delete any.
     */
    public function delete(User $user, Blog $blog): bool
    {
        if ($this->isAdmin($user)) {
            return !$blog->trashed();
        }
        // previous
        // return $blog->author_id === $user->id
        //     && $blog->status === Blog::STATUS_DRAFT;
       

        return $blog->author_id === $user->id
    && in_array($blog->status, [
        Blog::STATUS_DRAFT,
        Blog::STATUS_REJECTED
    ]);



    }

    // ── forceDelete ───────────────────────────────────────────────────────────

    public function forceDelete(User $user, Blog $blog): bool
    {
        return $this->isAdmin($user);
    }

    // ── restore ───────────────────────────────────────────────────────────────

    public function restore(User $user, Blog $blog): bool
    {
        return $this->isAdmin($user) || $blog->author_id === $user->id;
    }

    // ── submit — send for review ──────────────────────────────────────────────

    public function submit(User $user, Blog $blog): bool
    {
        return $blog->author_id === $user->id
            && in_array($blog->status, [Blog::STATUS_DRAFT, Blog::STATUS_REJECTED]);
    }

    // ── approve — admin only ──────────────────────────────────────────────────

    public function approve(User $user, Blog $blog): bool
    {
        return $this->isAdmin($user) && $blog->status === Blog::STATUS_PENDING;
    }

    // ── reject — admin only ───────────────────────────────────────────────────

    public function reject(User $user, Blog $blog): bool
    {
        return $this->isAdmin($user)
            && in_array($blog->status, [Blog::STATUS_PENDING, Blog::STATUS_FLAGGED]);
    }

    // ── feature — admin only ──────────────────────────────────────────────────

    public function feature(User $user, Blog $blog): bool
    {
        return $this->isAdmin($user) && $blog->is_publicly_visible;
    }

    // ── archive — admin only ──────────────────────────────────────────────────

    public function archive(User $user, Blog $blog): bool
    {
        return $this->isAdmin($user);
    }

    // ── comment — any authenticated active user ───────────────────────────────

    public function comment(?User $user, Blog $blog): bool
    {
        return $user !== null
            && $user->status === 'active'
            && $blog->is_publicly_visible;
    }

    // ── report — authenticated, not the author ────────────────────────────────

    public function report(User $user, Blog $blog): bool
    {
        return $user->id !== $blog->author_id && $blog->is_publicly_visible;
    }
}