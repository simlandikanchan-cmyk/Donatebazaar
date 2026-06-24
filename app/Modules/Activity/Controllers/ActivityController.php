<?php

namespace App\Modules\Activity\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Activity\Services\ActivityService;
use App\Modules\Activity\Requests\StoreActivityRequest;
use App\Modules\Activity\Resources\ActivityResource;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $service;

    public function __construct(ActivityService $service)
    {
        $this->service = $service;
    }

    // WEB (BLADE UI)
    public function index()
    {
        $activities = $this->service->getFeed();

        return view('feed', compact('activities'));
    }

    // API (JSON)
    public function apiFeed()
    {
        $feed = $this->service->getFeed();

        return ActivityResource::collection($feed);
    }

//     public function getFeed()
// {
//     return Activity::latest()->paginate(10);
// }

    public function store(StoreActivityRequest $request)
    {
        $activity = $this->service->createActivity([
            'user_id' => auth()->id(),
            ...$request->validated()
        ]);

        return new ActivityResource($activity);
    }
}