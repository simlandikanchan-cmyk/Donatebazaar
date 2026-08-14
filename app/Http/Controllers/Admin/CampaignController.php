<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\Campaign\CampaignQueryService;
use App\Services\Campaign\CampaignWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CampaignController extends Controller
{
    public function __construct(
        private CampaignWorkflowService $workflowService,
        private CampaignQueryService $queryService
    ) {}

    public function index(Request $request): View
    {
        $data = $this->queryService->getAdminList($request);

        return view('admin.campaign.index', $data);
    }

    public function show(Campaign $campaign): View
    {
        $campaign = $this->queryService->getAdminDetail($campaign);

        return view('admin.campaign.show', compact('campaign'));
    }

    public function edit(Campaign $campaign): View
    {
        $data = $this->queryService->getEditData($campaign);

        return view('admin.campaign.edit', $data);
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'goal_amount' => ['required', 'numeric', 'min:1'],
            'status' => ['nullable', 'string'],
            'end_date' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($campaign, $data) {
            $campaign->update($data);

            $campaign->logs()->create([
                'action' => 'updated',
                'message' => 'Campaign updated by admin.',
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.campaign.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    public function approve(Campaign $campaign): RedirectResponse
    {
        try {
            $this->workflowService->approve($campaign, auth()->user());
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Campaign approved and live.');
    }

    public function reject(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'min:10', 'max:500'],
        ]);

        try {
            $this->workflowService->reject($campaign, auth()->user(), $data['reason']);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Campaign rejected.');
    }

    public function pause(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->workflowService->pause($campaign, auth()->user(), $data['reason'] ?? null);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Campaign paused.');
    }

    public function resume(Campaign $campaign): RedirectResponse
    {
        try {
            $this->workflowService->resume($campaign, auth()->user());
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Campaign resumed.');
    }

    public function complete(Campaign $campaign): RedirectResponse
    {
        try {
            $this->workflowService->complete($campaign, auth()->user());
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Campaign marked completed.');
    }

    public function bulkApprove(Request $request): Response
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:campaigns,id'],
        ]);

        $result = $this->workflowService->bulkApprove($data['ids'], auth()->user());

        $type = $result->getType();
        $msg = $result->getMessage('approved');

        if ($request->expectsJson()) {
            return response()->json(['message' => $msg, 'type' => $type]);
        }

        return back()->with($type, $msg);
    }

    public function bulkReject(Request $request): Response
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:campaigns,id'],
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $result = $this->workflowService->bulkReject($data['ids'], auth()->user(), $data['reason']);

        $type = $result->getType();
        $msg = $result->getMessage('rejected');

        if ($request->expectsJson()) {
            return response()->json(['message' => $msg, 'type' => $type]);
        }

        return back()->with($type, $msg);
    }

    public function bulkPause(Request $request): Response
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:campaigns,id'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $result = $this->workflowService->bulkPause($data['ids'], auth()->user(), $data['reason'] ?? null);

        $type = $result->getType();
        $msg = $result->getMessage('paused');

        if ($request->expectsJson()) {
            return response()->json(['message' => $msg, 'type' => $type]);
        }

        return back()->with($type, $msg);
    }

    public function quick(Campaign $campaign): View
    {
        $data = $this->queryService->getQuickViewData($campaign);

        return view('admin._campaign_quick', $data);
    }

    public function requestKyc(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'admin_message' => ['nullable', 'string', 'max:500'],
        ]);

        $hasApprovedKyc = \App\Models\KycVerification::where('user_id', $campaign->user_id)
            ->where('status', \App\Models\KycVerification::STATUS_APPROVED)
            ->exists();

        if ($hasApprovedKyc) {
            return back()->with('error', 'KYC already approved.');
        }

        $hasPendingKyc = \App\Models\KycVerification::where('user_id', $campaign->user_id)
            ->where('status', \App\Models\KycVerification::STATUS_PENDING)
            ->exists();

        if ($hasPendingKyc) {
            return back()->with('warning', 'KYC already pending.');
        }

        $campaign->user->notify(
            new \App\Notifications\KycRequestedNotification(
                campaign: $campaign,
                adminMessage: $data['admin_message'] ?? ''
            )
        );

        $campaign->logs()->create([
            'action' => 'kyc_requested',
            'message' => 'KYC requested.',
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'KYC request sent.');
    }
}
