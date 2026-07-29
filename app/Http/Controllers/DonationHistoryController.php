<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\RecurringDonation;
use App\Repositories\CampaignRepository;
use App\Repositories\DonationRepository;
use Illuminate\Support\Facades\Auth;

class DonationHistoryController extends Controller
{
    public function __construct(
        private DonationRepository $donationRepo,
        private CampaignRepository $campaignRepo,
    ) {}

    public function index()
    {
        $user = Auth::user();

        $donations = $this->donationRepo->getUserDonations($user->id);
        $campaigns = $this->campaignRepo->getUserCampaigns($user->id, 9999);
        $recurringCount = RecurringDonation::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        $stats = $this->donationRepo->getUserStats($user->id);
        $totalDonated = (float) $stats->total_donated;
        $completedCount = (int) $stats->completed_count;
        $pendingCount = (int) $stats->pending_count;
        $refundedCount = (int) $stats->refunded_count;

        return view('donations.history', compact(
            'donations', 'campaigns', 'recurringCount',
            'totalDonated', 'completedCount', 'pendingCount', 'refundedCount'
        ));
    }

    public function receipt(Donation $donation)
    {
        if ($donation->user_id !== Auth::id()) {
            abort(403);
        }

        $donation->load('campaign');

        return view('donations.receipt', [
            'donation' => $donation,
            'campaign' => $donation->campaign,
            'donorName' => $donation->donor_name ?? 'Donor',
            'amount' => $donation->total_amount,
            'platformFee' => $donation->platform_fee,
            'netAmount' => $donation->net_amount,
            'receiptNo' => $donation->receipt_number,
            'paidAt' => $donation->paid_at,
        ]);
    }
}
