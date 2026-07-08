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
        // end_date is a date (no time). Compare against the start of today so a
        // campaign stays active through its entire end_date calendar day and is
        // only expired once that day is fully over. (Using Carbon::now() would
        // mark it expired at 00:00 of the end date — a full day early.)
        $count = Campaign::whereIn('campaign_state', ['active', 'paused'])
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<', Carbon::today())
            ->update([
                'campaign_state' => 'expired',
            ]);

        $this->info("Expired {$count} campaign(s).");

        return Command::SUCCESS;
    }
}