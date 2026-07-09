<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Models\Campaign;

class VolunteerController extends Controller
{
    /**
     * Public volunteer application page (viewable by anyone).
     */
    public function create()
    {
        $campaigns = Campaign::active()->latest()->limit(50)->get();

        return view('volunteer.apply', compact('campaigns'));
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
            'ngo_id'      => ['nullable', 'integer', 'exists:ngos,id'],
            'message'     => ['nullable', 'string', 'max:1000'],
        ]);

        $volunteer = Volunteer::firstOrCreate(['user_id' => auth()->id()]);

        $ngoId = $data['ngo_id'] ?? null;
        if (! empty($data['campaign_id'])) {
            $campaign = Campaign::find($data['campaign_id']);
            if ($campaign && ! is_null($campaign->ngo_id ?? null)) {
                $ngoId = $campaign->ngo_id;
            }
        }

        $exists = VolunteerApplication::where('volunteer_id', $volunteer->id)
            ->where('campaign_id', $data['campaign_id'] ?? null)
            ->where('ngo_id', $ngoId)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already applied.');
        }

        VolunteerApplication::create([
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $data['campaign_id'] ?? null,
            'ngo_id'      => $ngoId,
            'message'     => $data['message'] ?? null,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Your volunteer application has been submitted!');
    }

    /**
     * List volunteers who applied to a campaign (authenticated users).
     */
    public function campaignVolunteers($id)
    {
        $campaign = Campaign::findOrFail($id);

        $applications = VolunteerApplication::with('volunteer.user')
            ->where('campaign_id', $id)
            ->latest()
            ->get();

        return view('volunteer.campaign', compact('campaign', 'applications'));
    }
}
