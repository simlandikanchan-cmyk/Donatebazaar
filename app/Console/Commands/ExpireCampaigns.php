<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use Carbon\Carbon;

class ExpireCampaigns extends Command
{
    protected $signature = 'campaigns:expire';
    protected $description = 'Expire campaigns whose end date has passed';

    public function handle()
    {
        $count = Campaign::whereIn('campaign_state', ['active', 'paused'])
            ->whereNotNull('end_date')
            ->where('end_date', '<', Carbon::now())
            ->update([
                'campaign_state' => 'expired',
            ]);

        $this->info("Expired {$count} campaign(s).");

        // Also fix any active campaigns that slipped through (e.g. end_date = today midnight)
        $countToday = Campaign::where('campaign_state', 'active')
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', Carbon::today())
            ->update([
                'campaign_state' => 'expired',
            ]);

        if ($countToday > 0) {
            $this->info("Fixed {$countToday} additional campaign(s) missed by date boundary.");
        }

        return Command::SUCCESS;
    }
}