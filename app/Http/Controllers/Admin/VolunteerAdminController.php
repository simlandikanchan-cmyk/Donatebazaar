<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Services\VolunteerApplicationService;
use Illuminate\Http\Request;

class VolunteerAdminController extends Controller
{
    protected $volunteerApplicationService;

    public function __construct(VolunteerApplicationService $volunteerApplicationService)
    {
        $this->volunteerApplicationService = $volunteerApplicationService;
    }

    public function index(Request $request)
    {
        $query = Volunteer::with('user')
            ->when($request->search, function ($q) use ($request) {
                $s = '%' . $request->search . '%';
                $q->where(function ($wq) use ($s) {
                    $wq->whereHas('user', function ($uq) use ($s) {
                        $uq->where('name', 'like', $s)
                           ->orWhere('email', 'like', $s);
                    })
                    ->orWhere('city', 'like', $s)
                    ->orWhere('phone', 'like', $s);
                });
            })
            ->latest();

        $volunteers = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => Volunteer::count(),
            'verified' => Volunteer::where('is_verified', true)->count(),
            'pending'  => VolunteerApplication::where('status', 'pending')->count(),
        ];

        return view('admin.volunteers.index', compact('volunteers', 'stats'));
    }

    public function show(Volunteer $volunteer)
    {
        $volunteer->load('user', 'applications', 'assignments');
        return view('admin.volunteers.show', compact('volunteer'));
    }

    public function applications(Request $request)
    {
        $applications = VolunteerApplication::with(['volunteer.user', 'campaign'])
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            }, function ($q) {
                $q->where('status', 'pending');
            })
            ->when($request->search, function ($q) use ($request) {
                $s = '%' . $request->search . '%';
                $q->whereHas('volunteer.user', function ($uq) use ($s) {
                    $uq->where('name', 'like', $s)
                       ->orWhere('email', 'like', $s);
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'    => VolunteerApplication::count(),
            'pending'  => VolunteerApplication::where('status', 'pending')->count(),
            'approved' => VolunteerApplication::where('status', 'approved')->count(),
            'rejected' => VolunteerApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.volunteers.applications', compact('applications', 'stats'));
    }

    public function applicationShow(VolunteerApplication $application)
    {
        $application->load('volunteer.user', 'campaign');
        return view('admin.volunteers.applications_show', compact('application'));
    }

    public function applicationApprove(VolunteerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be approved.');
        }

        $application->load('volunteer.user');
        $this->volunteerApplicationService->processStatusChange($application, 'approved');

        return back()->with('success', 'Application approved. Volunteer is now verified.');
    }

    public function applicationReject(VolunteerApplication $application)
    {
        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be rejected.');
        }

        $application->load('volunteer.user');
        $this->volunteerApplicationService->processStatusChange($application, 'rejected');

        return back()->with('success', 'Application rejected.');
    }
}
