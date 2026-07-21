<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class VolunteerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $volunteer = $user->volunteer;

        if (! $volunteer) {
            return view('volunteer.dashboard', [
                'volunteer' => null,
                'isVerified' => false,
                'stats' => ['active' => 0, 'completed' => 0, 'total' => 0, 'applications' => 0],
                'activeAssignments' => collect(),
                'completedAssignments' => collect(),
                'applications' => collect(),
            ]);
        }

        $activeAssignments = $volunteer->assignments()
            ->with(['event' => fn ($q) => $q->with('campaign:id,title'), 'campaign:id,title'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        $completedAssignments = $volunteer->assignments()
            ->with(['event' => fn ($q) => $q->with('campaign:id,title'), 'campaign:id,title'])
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        $applications = $volunteer->applications()
            ->with('campaign:id,title,slug,cover_image')
            ->latest('applied_at')
            ->get();

        $stats = [
            'active' => $activeAssignments->count(),
            'completed' => $completedAssignments->count(),
            'total' => $activeAssignments->count() + $completedAssignments->count(),
            'applications' => $applications->count(),
        ];

        return view('volunteer.dashboard', compact(
            'volunteer',
            'activeAssignments',
            'completedAssignments',
            'applications',
            'stats',
        ));
    }
}
