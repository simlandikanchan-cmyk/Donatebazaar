<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function getOrganizationByUser(int $userId): ?Organization
    {
        return Organization::where('user_id', $userId)->first();
    }

    public function getBlogsByAuthor(int $authorId, int $limit = 3): Collection
    {
        return \App\Models\Blog::where('author_id', $authorId)
            ->latest()
            ->take($limit)
            ->get(['id', 'title', 'status', 'views_count', 'created_at', 'published_at']);
    }

    public function getEventsByUser(int $userId, int $limit = 5): Collection
    {
        return \App\Models\Event::where('user_id', $userId)
            ->whereIn('status', ['active', 'pending'])
            ->where('event_date', '>=', now()->subDay())
            ->latest('event_date')
            ->take($limit)
            ->get();
    }

    public function getUserRegisteredEvents(User $user, int $limit = 5): Collection
    {
        return $user->eventRegistrations()
            ->with('event')
            ->whereHas('event', fn($q) => $q->whereIn('status', ['active', 'pending'])->where('event_date', '>=', now()->subDay()))
            ->latest()
            ->take($limit)
            ->get();
    }
}
