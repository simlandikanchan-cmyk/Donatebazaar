<?php

namespace App\Console\Commands;

use App\Mail\KycReminderMail;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendKycReminders extends Command
{
    protected $signature = 'campaigns:send-kyc-reminders';

    protected $description = 'Send KYC upload reminders to campaign owners who have not submitted KYC';

    public function handle(): int
    {
        $now = Carbon::now();

        $campaigns = Campaign::with('user')
            ->where('campaign_state', Campaign::STATE_PENDING)
            ->where(function ($q) use ($now) {
                $q->whereNull('kyc_reminded_at')
                    ->where('created_at', '<', $now->copy()->subHours(24));
            })
            ->orWhere(function ($q) use ($now) {
                $q->whereNotNull('kyc_reminded_at')
                    ->where('kyc_reminded_at', '<', $now->copy()->subDays(3));
            })
            ->whereDoesntHave('user.kycVerification', fn ($q) => $q->where('status', 'approved'))
            ->get();

        $sent = 0;

        foreach ($campaigns as $campaign) {
            Mail::to($campaign->user)->queue(new KycReminderMail($campaign));

            $campaign->timestamps = false;
            $campaign->update(['kyc_reminded_at' => $now]);

            $sent++;
        }

        $this->info("Sent {$sent} KYC reminder(s).");

        return Command::SUCCESS;
    }
}
