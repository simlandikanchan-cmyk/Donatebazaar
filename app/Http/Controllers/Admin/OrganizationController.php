<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrganizationApplicationStatus;
use App\Models\OrganizationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'submitted_at');
        $dir = $request->input('direction', 'desc');

        $allowedSorts = ['name', 'organization_type', 'status', 'submitted_at'];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'submitted_at';
        }
        $dir = $dir === 'asc' ? 'asc' : 'desc';

        $query = OrganizationApplication::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $organizations = $query
            ->orderBy($sort, $dir)
            ->paginate(12);

        $total = OrganizationApplication::count();
        $cntPending = OrganizationApplication::where('status', 'pending')->count();
        $cntReview = OrganizationApplication::where('status', 'under_review')->count();
        $cntApproved = OrganizationApplication::where('status', 'approved')->count();
        $cntRejected = OrganizationApplication::where('status', 'rejected')->count();

        return view('admin.organizations.index', compact(
            'organizations', 'search', 'status', 'sort', 'dir',
            'total', 'cntPending', 'cntReview', 'cntApproved', 'cntRejected'
        ));
    }

    /**
     * Admin-side NGO onboarding form.
     */
    public function create()
    {
        return view('admin.organizations.create');
    }

    /**
     * Store an NGO application created by an admin (onboarded directly).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_type' => 'required|string|in:NGO,Trust,Society,Section-8',
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'causes' => 'nullable|array',
            'causes.*' => 'string',
            'founder_name' => 'nullable|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'contact_role' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'budget_range' => 'nullable|string|max:255',
            'donor_strength' => 'nullable|string|max:255',
            'employee_strength' => 'nullable|string|max:255',
            'campaign_timeline' => 'nullable|string|max:255',
            'has_crowdfunded' => 'nullable|boolean',
            'admin_notes' => 'nullable|string',
        ]);

        $application = OrganizationApplication::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'status' => 'approved',
            'current_step' => 4,
            'submitted_at' => now(),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]));

        $recipient = $validated['contact_email'];
        if ($recipient) {
            Mail::to($recipient)->send(new OrganizationApplicationStatus($application));
        }

        return redirect()->route('admin.organizations.index')
            ->with('success', 'NGO onboarded successfully.');
    }
}
