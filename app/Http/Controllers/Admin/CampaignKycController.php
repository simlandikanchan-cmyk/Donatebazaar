<?php

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
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
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

        return back()->with('success', 'KYC request sent to '.$owner->name.' via email and in-app notification.');
    }
}
