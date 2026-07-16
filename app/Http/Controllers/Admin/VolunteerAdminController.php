<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Models\VolunteerAssignment;
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
            ->when($request->state, function ($q) use ($request) {
                $q->where('state', $request->state);
            })
            ->when($request->city, function ($q) use ($request) {
                $q->where('city', $request->city);
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
        $events = Event::where('status', Event::STATUS_ACTIVE)
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date')
            ->get(['id', 'title', 'event_date', 'start_time', 'end_time']);
        return view('admin.volunteers.show', compact('volunteer', 'events'));
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

    // ── Volunteer Assignments ──────────────────────────────────────

    public function assignments(Request $request)
    {
        $query = VolunteerAssignment::with(['volunteer.user', 'event', 'campaign'])
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->search, function ($q) use ($request) {
                $s = '%' . $request->search . '%';
                $q->where(function ($wq) use ($s) {
                    $wq->where('role', 'like', $s)
                       ->orWhereHas('volunteer.user', function ($uq) use ($s) {
                           $uq->where('name', 'like', $s)->orWhere('email', 'like', $s);
                       });
                });
            })
            ->latest();

        $assignments = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => VolunteerAssignment::count(),
            'active'    => VolunteerAssignment::where('status', 'active')->count(),
            'completed' => VolunteerAssignment::where('status', 'completed')->count(),
        ];

        return view('admin.volunteers.assignments', compact('assignments', 'stats'));
    }

    public function assignmentCreate(Request $request)
    {
        $volunteers = Volunteer::with('user')->orderBy('id')->get();
        $events = Event::orderByDesc('event_date')->get(['id', 'title', 'event_date']);
        $campaigns = Campaign::orderBy('title')->get(['id', 'title']);

        $preselected = $request->volunteer_id ? Volunteer::with('user')->find($request->volunteer_id) : null;

        return view('admin.volunteers.assignment_create', compact('volunteers', 'events', 'campaigns', 'preselected'));
    }

    public function assignmentStore(Request $request)
    {
        $data = $request->validate([
            'volunteer_id' => 'required|exists:volunteers,id',
            'event_id'    => 'nullable|exists:events,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'role'        => 'required|string|max:120',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => 'required|in:active,completed,cancelled',
        ]);

        if (empty($data['event_id']) && empty($data['campaign_id'])) {
            return back()->withInput()->with('error', 'Assign to either an event or a campaign.');
        }

        VolunteerAssignment::create($data);

        return redirect()->route('admin.volunteer_assignments.index')
            ->with('success', 'Assignment created.');
    }

    public function assignmentEdit(VolunteerAssignment $assignment)
    {
        $volunteers = Volunteer::with('user')->orderBy('id')->get();
        $events = Event::orderByDesc('event_date')->get(['id', 'title', 'event_date']);
        $campaigns = Campaign::orderBy('title')->get(['id', 'title']);

        return view('admin.volunteers.assignment_edit', compact('assignment', 'volunteers', 'events', 'campaigns'));
    }

    public function assignmentUpdate(Request $request, VolunteerAssignment $assignment)
    {
        $data = $request->validate([
            'volunteer_id' => 'required|exists:volunteers,id',
            'event_id'    => 'nullable|exists:events,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'role'        => 'required|string|max:120',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => 'required|in:active,completed,cancelled',
        ]);

        $assignment->update($data);

        return redirect()->route('admin.volunteer_assignments.index')
            ->with('success', 'Assignment updated.');
    }

    public function assignmentDestroy(VolunteerAssignment $assignment)
    {
        $assignment->delete();

        return back()->with('success', 'Assignment removed.');
    }
}
