<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Modules\Activity\Services\ActivityService;


class DonationController extends Controller
{
    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        // Store donation
        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'donor_name' => 'Guest Donor',
            'donor_email' => 'guest@example.com',
            'amount' => $request->amount,
            'payment_status' => 'completed'
        ]);

        //Increase raised amount
        $campaign->increment('raised_amount', $request->amount);

        //CREATE ACTIVITY (IMPORTANT)
        app(ActivityService::class)->createDonationActivity($donation);

        return back()->with('success', 'Thank you for supporting this campaign!');
    }
}