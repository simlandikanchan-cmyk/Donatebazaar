<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\RecurringDonation;
use Illuminate\Support\Facades\Auth;

class DonationHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $donations = Donation::where('user_id', $user->id)
            ->with(['campaign' => function ($q) {
                $q->select('id', 'title', 'slug', 'cover_image', 'goal_amount', 'raised_amount', 'category_id')
                    ->with('category:id,name,slug');
            }, 'refunds'])
            ->orderByRaw("FIELD(payment_status, 'completed', 'pending', 'failed', 'refunded')")
            ->latest('created_at')
            ->paginate(15);

        $campaigns = Campaign::where('user_id', $user->id)->get();
        $recurringCount = RecurringDonation::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        $totalDonated = Donation::where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->sum('total_amount');

        $completedCount = Donation::where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->count();

        $pendingCount = Donation::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->count();

        $refundedCount = Donation::where('user_id', $user->id)
            ->where('is_refunded', true)
            ->count();

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
