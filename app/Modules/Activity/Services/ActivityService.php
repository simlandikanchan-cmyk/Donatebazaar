<?php

namespace App\Modules\Activity\Services;

use App\Modules\Activity\Repositories\ActivityRepository;

class ActivityService
{
    protected $repo;

    public function __construct(ActivityRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createActivity($data)
    {
        return $this->repo->create($data);
    }

    public function getFeed($userId = null)
    {
        return $this->repo->getFeed($userId);
    }

    // 🔥 AUTO ACTIVITY CREATOR (IMPORTANT)
public function createDonationActivity($donation)
{
    return $this->repo->create([
        'user_id' => auth()->id(), //
        'campaign_id' => $donation->campaign_id,
        'type' => 'donation',
        'title' => 'New Donation ',
        'description' => 'Donated ₹'.$donation->amount,
        'meta' => [
            'amount' => $donation->amount
        ]
    ]);
}
}