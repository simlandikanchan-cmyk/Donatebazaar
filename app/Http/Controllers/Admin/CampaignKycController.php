<?php

// Add these methods to your existing Admin CampaignController
// ---------------------------------------------------------------
// This is NOT a standalone file — merge into your existing
// App\Http\Controllers\Admin\CampaignController (or equivalent)
// ---------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\KycVerification;
use App\Models\User;
use App\Notifications\KycRequestedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampaignKycController extends Controller
{
    /**
     * Send a KYC request to the campaign owner.
     * Route: POST /admin/campaigns/{campaign}/request-kyc
     */
    public function requestKyc(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'admin_message' => ['nullable', 'string', 'max:500'],
        ]);

        $owner = User::findOrFail($campaign->user_id);

        // Check KYC status
        $kyc = KycVerification::where('user_id', $owner->id)->latest()->first();

        if ($kyc && $kyc->status === 'approved') {
            return back()->with('error', 'This user already has approved KYC. You can approve the campaign directly.');
        }

        if ($kyc && $kyc->status === 'pending') {
            return back()->with('warning', 'KYC is already submitted and pending review. Please verify it first.');
        }

        // Notify the user via in-app + email
        $owner->notify(new KycRequestedNotification(
            campaign: $campaign,
            adminMessage: $validated['admin_message'] ?? ''
        ));

        return back()->with('success', 'KYC request sent to ' . $owner->name . ' via email and in-app notification.');
    }

    /**
     * Approve campaign after verifying KYC.
     * Route: POST /admin/campaigns/{campaign}/approve
     *
     * Add this to your existing approve method — checks KYC before approving.
     */
    public function approve(Campaign $campaign): RedirectResponse
    {
        $owner = User::findOrFail($campaign->user_id);

        $kyc = KycVerification::where('user_id', $owner->id)
            ->where('status', 'approved')
            ->first();

        if (! $kyc) {
            return back()->with('error', 'Cannot approve: User has not submitted KYC.');
        }

        $campaign->update(['status' => 'approved']);

        return back()->with('success', 'Campaign approved successfully.');
    }
}