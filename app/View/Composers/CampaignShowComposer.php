<?php

namespace App\View\Composers;

use Illuminate\View\View;

class CampaignShowComposer
{
    public function compose(View $view): void
    {
        $campaign = $view->getData()['campaign'];
        $kyc = auth()->user()->kycVerification ?? null;

        $state = $campaign->campaign_state;

        if ($state === 'active') {
            $chipClass = 'chip-active';
            $chipLabel = 'Active';
        } elseif ($state === 'paused') {
            $chipClass = 'chip-paused';
            $chipLabel = 'Paused';
        } elseif ($state === 'rejected') {
            $chipClass = 'chip-rejected';
            $chipLabel = 'Rejected';
        } elseif ($state === 'expired') {
            $chipClass = 'chip-expired';
            $chipLabel = 'Expired';
        } elseif ($state === 'inactive') {
            $chipClass = 'chip-inactive';
            $chipLabel = 'Under Review';
        } elseif ($state === 'pending') {
            $chipClass = 'chip-pending';
            $chipLabel = 'Pending';
        } else {
            $chipClass = 'chip-pending';
            $chipLabel = ucfirst($state ?? 'Draft');
        }

        $raised = $campaign->raised_amount ?? 0;
        $goal = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
        $rawPercent = round(($raised / $goal) * 100);
        $percentage = min(100, $rawPercent);
        $isOverfunded = $raised > $campaign->goal_amount;
        $remaining = max(0, $campaign->goal_amount - $raised);
        $surplus = $isOverfunded ? ($raised - $campaign->goal_amount) : 0;

        $donorsList = $campaign->donations ?? collect();
        $donorCount = $donorsList->count();
        $avgDonation = $donorCount > 0 ? $donorsList->avg('total_amount') : 0;
        $lastDonation = $donorsList->first();
        $recentDonors = $donorsList->take(3);

        $daysLeft = isset($campaign->end_date) && $campaign->end_date
                    ? (int) ceil(now()->diffInDays($campaign->end_date, false))
                    : null;
        $isEnded = $daysLeft !== null && $daysLeft < 0;

        $kycAadhaarUrl = $kyc?->aadhaar_url ? asset('storage/'.$kyc->aadhaar_url) : null;
        $kycPanUrl = $kyc?->pan_url ? asset('storage/'.$kyc->pan_url) : null;
        $kycSelfieUrl = $kyc?->selfie_url ? asset('storage/'.$kyc->selfie_url) : null;

        $isImg = fn ($url) => $url && preg_match('/\.(jpe?g|png|webp|gif)$/i', $url);
        $isPdf = fn ($url) => $url && str_ends_with(strtolower($url), '.pdf');

        $bankName = $kyc?->kyc_bank_name ?? null;
        $bankAcc = $kyc?->kyc_account_number ?? null;
        $bankIfsc = $kyc?->kyc_ifsc ?? null;
        $bankHolder = $kyc?->kyc_account_name ?? null;

        $updates = $campaign->updates ?? collect();

        $publicUrl = null;
        try {
            if (
                in_array($campaign->campaign_state, ['active', 'completed', 'expired']) &&
                $campaign->category &&
                $campaign->slug
            ) {
                $publicUrl = route('campaign.public', ['category' => $campaign->category->slug, 'slug' => $campaign->slug]);
            }
        } catch (\Throwable $e) {
        }

        $view->with(compact(
            'kyc', 'chipClass', 'chipLabel', 'raised', 'goal', 'rawPercent',
            'percentage', 'isOverfunded', 'remaining', 'surplus', 'donorsList',
            'donorCount', 'avgDonation', 'lastDonation', 'recentDonors',
            'daysLeft', 'isEnded', 'kycAadhaarUrl', 'kycPanUrl', 'kycSelfieUrl',
            'isImg', 'isPdf', 'bankName', 'bankAcc', 'bankIfsc', 'bankHolder',
            'updates', 'publicUrl'
        ));
    }
}
