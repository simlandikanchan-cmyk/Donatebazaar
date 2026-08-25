<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Repositories\DonationRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CampaignAnalyticsController extends Controller
{
    public function __construct(
        private DonationRepository $donationRepo,
    ) {}

    public function index(Campaign $campaign)
    {
        if ($campaign->user_id !== Auth::id()) {
            abort(403);
        }

        $stats = $this->donationRepo->getCampaignAnalytics($campaign->id);

        $trendData = $stats['trendData'];
        $weeklyTrend = $trendData->groupBy(function ($item) {
            return Carbon::parse($item->date)->startOfWeek()->format('Y-m-d');
        })->map(function ($group) {
            return [
                'total' => $group->sum('total'),
                'count' => $group->sum('count'),
            ];
        });

        $donationTypeBreakdown = collect([
            ['type' => 'Money', 'count' => $stats['moneyCount'], 'amount' => $stats['moneyAmount']],
            ['type' => 'Product', 'count' => $stats['productCount'], 'amount' => $stats['productAmount']],
        ]);

        $dailyData = $trendData->pluck('total', 'date');

        $weeklyLabels = $weeklyTrend->keys()->map(function ($date) {
            $start = Carbon::parse($date);
            $end = $start->copy()->addDays(6);

            return $start->format('d M').' - '.$end->format('d M');
        });
        $weeklyTotals = $weeklyTrend->pluck('total');

        $dayNames = ['', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $dayOfWeekLabels = array_slice($dayNames, 1);
        $dayOfWeekTotals = collect($dayOfWeekLabels)->map(function ($name, $idx) use ($stats) {
            return $stats['donationsByDayOfWeek']->get($idx + 1)?->total ?? 0;
        });
        $dayOfWeekCounts = collect($dayOfWeekLabels)->map(function ($name, $idx) use ($stats) {
            return $stats['donationsByDayOfWeek']->get($idx + 1)?->count ?? 0;
        });

        return view('campaigns.analytics', compact(
            'campaign',
            'dailyData', 'weeklyLabels', 'weeklyTotals',
            'donationTypeBreakdown', 'dayOfWeekLabels', 'dayOfWeekTotals', 'dayOfWeekCounts',
        ) + $stats);
    }
}
