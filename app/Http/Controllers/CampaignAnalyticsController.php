<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignAnalyticsController extends Controller
{
    public function index(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        $completedDonations = $campaign->donations()
            ->where('payment_status', 'completed');

        $completedDonationsAll = (clone $completedDonations)->get();

        $totalRaised = (clone $completedDonations)->sum('total_amount');
        $donationCount = (clone $completedDonations)->count();
        $avgDonation = $donationCount > 0 ? $totalRaised / $donationCount : 0;
        $maxDonation = (clone $completedDonations)->max('total_amount') ?? 0;
        $minDonation = (clone $completedDonations)->min('total_amount') ?? 0;
        $platformFees = (clone $completedDonations)->sum('platform_fee');
        $uniqueDonors = (clone $completedDonations)
            ->whereNotNull('donor_email')
            ->distinct('donor_email')
            ->count('donor_email');

        $productDonations = (clone $completedDonations)
            ->where('donation_type', 'product')
            ->count();
        $productAmount = (clone $completedDonations)
            ->where('donation_type', 'product')
            ->sum('total_amount');

        $moneyDonations = (clone $completedDonations)
            ->where('donation_type', 'money')
            ->count();
        $moneyAmount = (clone $completedDonations)
            ->where('donation_type', 'money')
            ->sum('total_amount');

        $trendData = (clone $completedDonations)
            ->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('paid_at', '>=', now()->subDays(60))
            ->groupBy(DB::raw('DATE(paid_at)'))
            ->orderBy('date')
            ->get();

        $weeklyTrend = $trendData->groupBy(function ($item) {
            return Carbon::parse($item->date)->startOfWeek()->format('Y-m-d');
        })->map(function ($group) {
            return [
                'total' => $group->sum('total'),
                'count' => $group->sum('count'),
            ];
        });

        $donationTypeBreakdown = collect([
            ['type' => 'Money', 'count' => $moneyDonations, 'amount' => $moneyAmount],
            ['type' => 'Product', 'count' => $productDonations, 'amount' => $productAmount],
        ]);

        $dailyData = $trendData->pluck('total', 'date');

        $weeklyLabels = $weeklyTrend->keys()->map(function ($date) {
            $start = Carbon::parse($date);
            $end = $start->copy()->addDays(6);

            return $start->format('d M').' - '.$end->format('d M');
        });
        $weeklyTotals = $weeklyTrend->pluck('total');

        $topDonors = (clone $completedDonations)
            ->where('is_anonymous', false)
            ->whereNotNull('donor_name')
            ->select('donor_name', 'donor_email', DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as donations'))
            ->groupBy('donor_name', 'donor_email')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $recentDonations = (clone $completedDonations)
            ->where(function ($q) {
                $q->whereNull('is_anonymous')->orWhere('is_anonymous', false);
            })
            ->select('donor_name', 'total_amount', 'donation_type', 'paid_at', 'payment_gateway')
            ->latest('paid_at')
            ->take(15)
            ->get();

        $donationsByDayOfWeek = (clone $completedDonations)
            ->select(
                DB::raw('DAYOFWEEK(paid_at) as day_num'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DAYOFWEEK(paid_at)'))
            ->orderBy('day_num')
            ->get()
            ->keyBy('day_num');

        $dayNames = ['', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $dayOfWeekLabels = array_slice($dayNames, 1);
        $dayOfWeekTotals = collect($dayOfWeekLabels)->map(function ($name, $idx) use ($donationsByDayOfWeek) {
            return $donationsByDayOfWeek->get($idx + 1)?->total ?? 0;
        });
        $dayOfWeekCounts = collect($dayOfWeekLabels)->map(function ($name, $idx) use ($donationsByDayOfWeek) {
            return $donationsByDayOfWeek->get($idx + 1)?->count ?? 0;
        });

        $totalDonors = (clone $completedDonations)
            ->whereNotNull('donor_email')
            ->distinct('donor_email')
            ->count('donor_email');

        return view('campaigns.analytics', compact(
            'campaign',
            'totalRaised',
            'donationCount',
            'avgDonation',
            'maxDonation',
            'minDonation',
            'platformFees',
            'uniqueDonors',
            'totalDonors',
            'dailyData',
            'weeklyLabels',
            'weeklyTotals',
            'donationTypeBreakdown',
            'topDonors',
            'recentDonations',
            'dayOfWeekLabels',
            'dayOfWeekTotals',
            'dayOfWeekCounts',
        ));
    }
}
