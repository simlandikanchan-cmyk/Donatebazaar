<?php

namespace App\Services\Campaign;

use App\Mail\CampaignStatusMail;
use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CampaignWorkflowService
{
    public function approve(Campaign $campaign, User $admin): void
    {
        if (! $campaign->isPending()) {
            throw new \InvalidArgumentException('Only pending campaigns can be approved.');
        }

        if ($campaign->isExpired()) {
            throw new \InvalidArgumentException('Cannot approve expired campaign.');
        }

        if (! $campaign->ownerKycApproved()) {
            throw new \InvalidArgumentException('Cannot approve: User KYC not approved.');
        }

        DB::transaction(function () use ($campaign, $admin) {
            $campaign->approve();

            CampaignLog::create([
                'campaign_id' => $campaign->id,
                'action' => 'approved',
                'message' => 'Campaign approved.',
                'user_id' => $admin->id,
            ]);
        });

        Mail::to($campaign->user)->send(new CampaignStatusMail($campaign, 'approved'));
    }

    public function reject(Campaign $campaign, User $admin, string $reason): void
    {
        if (! $campaign->isPending()) {
            throw new \InvalidArgumentException('Only pending campaigns can be rejected.');
        }

        if (trim($reason) === '' || mb_strlen($reason) < 10) {
            throw new \InvalidArgumentException('A rejection reason of at least 10 characters is required.');
        }

        DB::transaction(function () use ($campaign, $admin, $reason) {
            $campaign->reject($reason);

            CampaignLog::create([
                'campaign_id' => $campaign->id,
                'action' => 'rejected',
                'message' => 'Rejected: '.$reason,
                'user_id' => $admin->id,
            ]);
        });

        Mail::to($campaign->user)->send(new CampaignStatusMail($campaign, 'rejected', $reason));
    }

    public function pause(Campaign $campaign, User $admin, ?string $reason = null): void
    {
        if (! $campaign->isActive()) {
            throw new \InvalidArgumentException('Only active campaigns can be paused.');
        }

        if ($campaign->isExpired()) {
            throw new \InvalidArgumentException('Cannot pause expired campaign.');
        }

        if ($campaign->isPaused()) {
            throw new \InvalidArgumentException('Already paused.');
        }

        DB::transaction(function () use ($campaign, $admin, $reason) {
            $campaign->pause($reason ?? 'Paused by admin');

            CampaignLog::create([
                'campaign_id' => $campaign->id,
                'action' => 'paused',
                'message' => 'Campaign paused.',
                'user_id' => $admin->id,
            ]);
        });
    }

    public function resume(Campaign $campaign, User $admin): void
    {
        if (! $campaign->isPaused()) {
            throw new \InvalidArgumentException('Campaign is not paused.');
        }

        if ($campaign->isExpired()) {
            throw new \InvalidArgumentException('Cannot resume expired campaign.');
        }

        if (! $campaign->ownerKycApproved()) {
            throw new \RuntimeException('KYC not approved.');
        }

        DB::transaction(function () use ($campaign, $admin) {
            $campaign->resume();

            CampaignLog::create([
                'campaign_id' => $campaign->id,
                'action' => 'resumed',
                'message' => 'Campaign resumed.',
                'user_id' => $admin->id,
            ]);
        });
    }

    public function complete(Campaign $campaign, User $admin): void
    {
        if (! $campaign->isActive()) {
            throw new \InvalidArgumentException('Only active campaigns can be completed.');
        }

        if ($campaign->isExpired()) {
            throw new \InvalidArgumentException('Expired campaigns cannot be completed.');
        }

        if ($campaign->isCompleted()) {
            throw new \InvalidArgumentException('Already completed.');
        }

        DB::transaction(function () use ($campaign, $admin) {
            $campaign->complete();

            CampaignLog::create([
                'campaign_id' => $campaign->id,
                'action' => 'completed',
                'message' => 'Campaign completed.',
                'user_id' => $admin->id,
            ]);
        });
    }

    public function bulkApprove(array $campaignIds, User $admin): BulkResult
    {
        $campaigns = Campaign::with('user.kycVerification')->whereIn('id', $campaignIds)->get();

        $done = 0;
        $skipped = 0;

        foreach ($campaigns as $campaign) {
            if (! $campaign->isPending() || $campaign->isExpired()) {
                $skipped++;

                continue;
            }

            if (! $campaign->ownerKycApproved()) {
                $skipped++;

                continue;
            }

            DB::transaction(function () use ($campaign, $admin) {
                $campaign->approve();

                CampaignLog::create([
                    'campaign_id' => $campaign->id,
                    'action' => 'approved',
                    'message' => 'Campaign approved (bulk).',
                    'user_id' => $admin->id,
                ]);
            });

            Mail::to($campaign->user)->send(new CampaignStatusMail($campaign, 'approved'));
            $done++;
        }

        return new BulkResult($done, $skipped);
    }

    public function bulkReject(array $campaignIds, User $admin, string $reason): BulkResult
    {
        $campaigns = Campaign::with('user')->whereIn('id', $campaignIds)->get();

        $done = 0;
        $skipped = 0;

        foreach ($campaigns as $campaign) {
            if (! $campaign->isPending()) {
                $skipped++;

                continue;
            }

            DB::transaction(function () use ($campaign, $admin, $reason) {
                $campaign->reject($reason);

                CampaignLog::create([
                    'campaign_id' => $campaign->id,
                    'action' => 'rejected',
                    'message' => 'Rejected (bulk): '.$reason,
                    'user_id' => $admin->id,
                ]);
            });

            Mail::to($campaign->user)->send(new CampaignStatusMail($campaign, 'rejected', $reason));
            $done++;
        }

        return new BulkResult($done, $skipped);
    }

    public function bulkPause(array $campaignIds, User $admin, ?string $reason = null): BulkResult
    {
        $campaigns = Campaign::with('user')->whereIn('id', $campaignIds)->get();

        $done = 0;
        $skipped = 0;

        foreach ($campaigns as $campaign) {
            if (! $campaign->isActive() || $campaign->isExpired() || $campaign->isPaused()) {
                $skipped++;

                continue;
            }

            DB::transaction(function () use ($campaign, $admin, $reason) {
                $campaign->pause($reason ?? 'Paused by admin (bulk)');

                CampaignLog::create([
                    'campaign_id' => $campaign->id,
                    'action' => 'paused',
                    'message' => 'Campaign paused (bulk).',
                    'user_id' => $admin->id,
                ]);
            });

            $done++;
        }

        return new BulkResult($done, $skipped);
    }
}
