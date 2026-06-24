<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Notifications\KycRequestedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    // -------------------------------------------------------------------------
    // INDEX
    // -------------------------------------------------------------------------
    public function index()
    {
        $campaigns = Campaign::with([
                'user:id,name',
                'category:id,name'
            ])
            ->latest()
            ->paginate(15);

        return view('admin.campaigns.index', [
            'campaigns'    => $campaigns,
            'cntActive'    => Campaign::active()->count(),
            'cntPending'   => Campaign::pending()->count(),
            'cntPaused'    => Campaign::paused()->count(),
            'cntRejected'  => Campaign::rejected()->count(),
            'cntExpired'   => Campaign::expired()->count(),
            'cntCompleted' => Campaign::completed()->count(),
        ]);
    }

    // -------------------------------------------------------------------------
    // SHOW
    // -------------------------------------------------------------------------
    public function show(Campaign $campaign)
    {
        $campaign->load([
            'user.kycVerification',
            'category',
            'events',
            'logs'
        ]);

        return view('admin.campaign.show', compact('campaign'));
    }

    // -------------------------------------------------------------------------
    // EDIT
    // -------------------------------------------------------------------------
public function edit(Campaign $campaign)
{
    $campaign->load([
        'user',
        'category',
        'events',
        'logs'
    ]);

    $categories = \App\Models\Category::orderBy('name')
        ->get();

    return view('admin.campaign.edit', compact(
        'campaign',
        'categories'
    ));
}

    // -------------------------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------------------------
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255'],
            'short_description'=> ['nullable', 'string', 'max:500'],
            'description'      => ['required', 'string'],
            'goal_amount'      => ['required', 'numeric', 'min:1'],
            'status'           => ['nullable', 'string'],
            'end_date'         => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($campaign, $data) {

            $campaign->update($data);

            $this->log(
                $campaign,
                'updated',
                'Campaign updated by admin.'
            );
        });

        return redirect()
            ->route('admin.campaign.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    // -------------------------------------------------------------------------
    // APPROVE
    // -------------------------------------------------------------------------
    public function approve(Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isPending()) {
            return back()->with('error', 'Only pending campaigns can be approved.');
        }

        if ($campaign->isExpired()) {
            return back()->with('error', 'Cannot approve expired campaign.');
        }

        $kyc = optional($campaign->user)->kycVerification;

        if (! $kyc || $kyc->status !== 'approved') {

            $reason = ! $kyc
                ? 'User has not submitted KYC.'
                : match ($kyc->status) {
                    'pending'  => 'KYC under review.',
                    'rejected' => 'KYC rejected.',
                    default    => 'KYC not verified.',
                };

            return back()->with('error', "Cannot approve: {$reason}");
        }

        DB::transaction(function () use ($campaign) {

            $campaign->approve();

            $this->log(
                $campaign,
                'approved',
                'Campaign approved.'
            );
        });

        return back()->with('success', 'Campaign approved and live.');
    }

    // -------------------------------------------------------------------------
    // REJECT
    // -------------------------------------------------------------------------
    public function reject(Request $request, Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isPending()) {
            return back()->with('error', 'Only pending campaigns can be rejected.');
        }

        $data = $request->validate([
            'reason' => ['required', 'min:10', 'max:500'],
        ]);

        DB::transaction(function () use ($campaign, $data) {

            $campaign->reject($data['reason']);

            $this->log(
                $campaign,
                'rejected',
                'Rejected: ' . $data['reason']
            );
        });

        return back()->with('success', 'Campaign rejected.');
    }

    // -------------------------------------------------------------------------
    // PAUSE
    // -------------------------------------------------------------------------
    public function pause(Request $request, Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isActive()) {
            return back()->with('error', 'Only active campaigns can be paused.');
        }

        if ($campaign->isExpired()) {
            return back()->with('error', 'Cannot pause expired campaign.');
        }

        if ($campaign->isPaused()) {
            return back()->with('error', 'Already paused.');
        }

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($campaign, $data) {

            $campaign->pause(
                $data['reason'] ?? 'Paused by admin'
            );

            $this->log(
                $campaign,
                'paused',
                'Campaign paused.'
            );
        });

        return back()->with('success', 'Campaign paused.');
    }

    // -------------------------------------------------------------------------
    // RESUME
    // -------------------------------------------------------------------------
    public function resume(Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isPaused()) {
            return back()->with('error', 'Campaign is not paused.');
        }

        if ($campaign->isExpired()) {
            return back()->with('error', 'Cannot resume expired campaign.');
        }

        if (! $campaign->ownerKycApproved()) {
            return back()->with('error', 'KYC not approved.');
        }

        try {

            DB::transaction(function () use ($campaign) {

                $campaign->resume();

                $this->log(
                    $campaign,
                    'resumed',
                    'Campaign resumed.'
                );
            });

        } catch (\RuntimeException $e) {

            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Campaign resumed.');
    }

    // -------------------------------------------------------------------------
    // COMPLETE
    // -------------------------------------------------------------------------
    public function complete(Campaign $campaign): RedirectResponse
    {
        if (! $campaign->isActive()) {
            return back()->with('error', 'Only active campaigns can be completed.');
        }

        if ($campaign->isExpired()) {
            return back()->with('error', 'Expired campaigns cannot be completed.');
        }

        if ($campaign->isCompleted()) {
            return back()->with('error', 'Already completed.');
        }

        DB::transaction(function () use ($campaign) {

            $campaign->complete();

            $this->log(
                $campaign,
                'completed',
                'Campaign completed.'
            );
        });

        return back()->with('success', 'Campaign marked completed.');
    }

    // -------------------------------------------------------------------------
    // REQUEST KYC
    // -------------------------------------------------------------------------
    public function requestKyc(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'admin_message' => ['nullable', 'string', 'max:500'],
        ]);

        $kyc = optional($campaign->user)->kycVerification;

        if ($kyc && $kyc->status === 'approved') {
            return back()->with('error', 'KYC already approved.');
        }

        if ($kyc && $kyc->status === 'pending') {
            return back()->with('warning', 'KYC already pending.');
        }

        $campaign->user->notify(
            new KycRequestedNotification(
                campaign: $campaign,
                adminMessage: $data['admin_message'] ?? ''
            )
        );

        $this->log(
            $campaign,
            'kyc_requested',
            'KYC requested.'
        );

        return back()->with('success', 'KYC request sent.');
    }

    // -------------------------------------------------------------------------
    // LOGGER
    // -------------------------------------------------------------------------
    private function log(
        Campaign $campaign,
        string $action,
        string $message
    ): void {

        try {

            $campaign->logs()->create([
                'action'  => $action,
                'message' => $message,
                'user_id' => auth()->id(),
            ]);

        } catch (\Throwable $e) {

            \Log::warning(
                'Campaign log failed: ' . $e->getMessage()
            );
        }
    }
}