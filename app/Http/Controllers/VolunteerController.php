<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Models\VolunteerAssignment;

class VolunteerController extends Controller
{
    public function apply(Request $request)
    {
        $user = auth()->user();

        $volunteer = $user->volunteer;

        if (!$volunteer) {
            $volunteer = Volunteer::create([
                'user_id' => $user->id,
            ]);
        }

        $alreadyApplied = VolunteerApplication::where([
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $request->campaign_id
        ])->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Already applied');
        }

        VolunteerApplication::create([
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $request->campaign_id,
            'message' => $request->message,
        ]);


        VolunteerApplication::create
        
        ([
        'volunteer_id' => $volunteer->id,
        'campaign_id' => $request->campaign_id,
        'ngo_id' => $campaign->ngo_id ?? null,
        'message' => $request->message,
       
       ]);

        return back()->with('success', 'Applied successfully');
    }
}



