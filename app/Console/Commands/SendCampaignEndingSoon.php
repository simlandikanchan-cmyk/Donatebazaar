<?php

namespace App\Console\Commands;

use App\Mail\CampaignEndingSoonMail;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCampaignEndingSoon extends Command
{
    protected $signature = 'campaigns:send-ending-soon';

    protected $description = 'Send ending soon reminders to campaign owners whose campaigns end within 7 days';

    public function handle(): int
    {
        $now = Carbon::now();
        $weekFromNow = $now->copy()->addDays(7);

        $campaigns = Campaign::with('user')
            ->where('campaign_state', Campaign::STATE_ACTIVE)
            ->whereNotNull('end_date')
            ->where('end_date', '>', $now)
            ->where('end_date', '<=', $weekFromNow)
            ->whereDoesntHave('user.kycVerification', fn ($q) => $q->where('status', 'approved'))
            ->get();

        $sent = 0;

        foreach ($campaigns as $campaign) {
            if (! $campaign->user || ! $campaign->user->email) {
                continue;
            }

            try {
                Mail::to($campaign->user)->queue(new CampaignEndingSoonMail($campaign));
                $sent++;
            } catch (\Throwable $e) {
                \Log::warning('Campaign ending soon email failed', [
                    'campaign_id' => $campaign->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Sent {$sent} campaign ending soon reminder(s).");

        return Command::SUCCESS;
    }
}
