<?php

namespace App\Modules\Activity\Repositories;

use App\Modules\Activity\Models\Activity;

class ActivityRepository
{
    public function create(array $data)
    {
        return Activity::create($data);
    }

public function getFeed($userId = null)
{
    return Activity::with(['user', 'campaign'])
        ->where(function ($q) use ($userId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->whereNull('user_id')
                  ->orWhereNotNull('user_id');
            }
        })
        ->latest()
        ->paginate(10);
}
}