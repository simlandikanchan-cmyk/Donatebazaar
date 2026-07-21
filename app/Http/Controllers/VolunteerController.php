<?php

namespace App\Http\Controllers;

use App\Mail\VolunteerApplicationReceived;
use App\Models\Campaign;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Services\VolunteerApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VolunteerController extends Controller
{
    /**
     * Public volunteer application page (viewable by anyone).
     */
    public function create()
    {
        $campaigns = Campaign::active()->latest()->limit(50)->get();
        $cities = config('india.cities', []);

        return view('volunteer.apply', compact('campaigns', 'cities'));
    }

    /**
     * Store a volunteer application (requires an authenticated user).
     */
    public function store(Request $request)
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in to submit a volunteer application.');
        }

        $data = $request->validate([
            'campaign_id' => ['nullable', 'integer', 'exists:campaigns,id'],
            'message' => ['nullable', 'string', 'max:1000'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'skills' => ['nullable', 'string', 'max:500'],
            'availability' => ['nullable', 'string', 'in:full_time,part_time,weekends'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $volunteer = Volunteer::firstOrCreate(['user_id' => auth()->id()]);

        $volunteer->update([
            'phone' => $data['phone'] ?? $volunteer->phone,
            'bio' => $data['bio'] ?? null,
            'skills' => $data['skills'] ? array_map('trim', explode(',', $data['skills'])) : null,
            'availability' => $data['availability'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'country' => $data['country'] ?? 'India',
        ]);

        $exists = VolunteerApplication::where('volunteer_id', $volunteer->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already have a pending or approved application.');
        }

        $application = VolunteerApplication::create([
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $data['campaign_id'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        try {
            Mail::to($volunteer->user->email)->send(
                new VolunteerApplicationReceived($application)
            );
        } catch (\Throwable $e) {
            Log::warning('Volunteer application confirmation email failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Your volunteer application has been submitted!');
    }

    /**
     * List volunteers who applied to a campaign (admin/organizer only).
     */
    public function campaignVolunteers($id)
    {
        $campaign = Campaign::findOrFail($id);

        if (auth()->user()->role !== 'admin' && $campaign->user_id !== auth()->id()) {
            abort(403, 'Access denied.');
        }

        $applications = VolunteerApplication::with('volunteer.user')
            ->where('campaign_id', $id)
            ->latest()
            ->get();

        return view('volunteer.campaign', compact('campaign', 'applications'));
    }

    /**
     * Admin: approve/reject a volunteer application.
     */
    public function updateStatus(Request $request, $id, VolunteerApplicationService $service)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Admins only.');
        }

        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $application = VolunteerApplication::with('volunteer.user')->findOrFail($id);

        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be updated.');
        }

        $service->processStatusChange($application, $data['status']);

        $message = $data['status'] === 'approved'
            ? 'Application approved. Volunteer is now verified.'
            : 'Application rejected.';

        return back()->with('success', $message);
    }
}
