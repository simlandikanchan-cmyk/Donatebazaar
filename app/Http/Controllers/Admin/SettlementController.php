<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CampaignSettlement;
use App\Models\Refund;
use App\Jobs\ProcessSettlementJob;
use App\Repositories\SettlementRepository;
use App\Services\SettlementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettlementController extends Controller
{
    public function __construct(
        private SettlementService $settlementService,
        private SettlementRepository $settlementRepo,
    ) {}

    public function index(Request $request): View
    {
        $statuses = ['pending_approval', 'manual_review', 'approved', 'processing', 'paid', 'rejected', 'failed'];

        $filter = $request->input('status');
        if ($filter === 'pending_approval') {
            $filter = ['pending_approval', 'manual_review'];
        }

        $query = CampaignSettlement::with(['organization', 'settlementItems'])
            ->when($filter, function ($q) use ($filter) {
                $q->whereIn('status', (array) $filter);
            });

        $order = (string) $request->input('status');
        if ($order === '') {
            $query->orderByRaw("FIELD(status, 'manual_review', 'pending_approval') DESC")
                ->orderByDesc('created_at');
        } else {
            $query->latest();
        }

        $settlements = $query->paginate(25);

        $rawCounts = CampaignSettlement::selectRaw('status, COUNT(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status');

        $counts = ['total' => (int) $rawCounts->sum()];
        foreach ($statuses as $st) {
            $counts[$st] = (int) ($rawCounts[$st] ?? 0);
        }
        $counts['pending_approval'] = (int) ($rawCounts['pending_approval'] ?? 0)
            + (int) ($rawCounts['manual_review'] ?? 0);

        return view('admin.settlements.index', compact('settlements', 'counts', 'statuses'));
    }

    public function show(CampaignSettlement $settlement): View
    {
        $settlement->load(['organization.payoutAccounts', 'settlementItems.donation.campaign']);

        $org = $settlement->organization;
        $payoutAccounts = $org?->payoutAccounts()->latest()->get() ?? collect();
        $payout = $payoutAccounts->firstWhere('is_verified', true);

        $flags = [];
        if ((float) $settlement->net_amount >= 100000) {
            $flags[] = 'High value (≥ ₹100,000)';
        }
        if ($payoutAccounts->contains(fn ($a) => ! $a->is_verified)) {
            $flags[] = 'Unverified payout account on file';
        }
        if ($org && $org->verification_status !== 'verified') {
            $flags[] = 'Organization KYC not verified';
        }
        $recentRefunds = $org
            ? Refund::whereHas('donation', function ($q) use ($org) {
                $q->where('user_id', $org->user_id);
            })->where('created_at', '>=', now()->subDays(30))->count()
            : 0;
        if ($recentRefunds > 0) {
            $flags[] = "{$recentRefunds} refund(s) in last 30 days";
        }

        return view('admin.settlements.show', compact(
            'settlement', 'org', 'payout', 'payoutAccounts', 'flags'
        ));
    }

    public function approve(Request $request, CampaignSettlement $settlement): RedirectResponse
    {
        $originalStatus = $settlement->status;

        try {
            $this->settlementService->approveSettlement($settlement, auth()->user());
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->route('admin.settlements.show', $settlement)
                ->with('error', $e->getMessage());
        }

        ProcessSettlementJob::dispatch($settlement);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'settlement_approved',
            'loggable_type' => CampaignSettlement::class,
            'loggable_id' => $settlement->id,
            'meta' => [
                'settlement_id' => $settlement->id,
                'net_amount' => $settlement->net_amount,
                'organization_id' => $settlement->organization_id,
                'previous_status' => $originalStatus,
                'new_status' => 'approved',
            ],
        ]);

        return redirect()
            ->route('admin.settlements.show', $settlement)
            ->with('success', 'Settlement approved. Payout processing started.');
    }

    public function reject(Request $request, CampaignSettlement $settlement): RedirectResponse
    {
        $originalStatus = $settlement->status;

        $data = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $this->settlementService->rejectSettlement($settlement, auth()->user(), $data['reason']);
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->route('admin.settlements.show', $settlement)
                ->with('error', $e->getMessage());
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'settlement_rejected',
            'loggable_type' => CampaignSettlement::class,
            'loggable_id' => $settlement->id,
            'meta' => [
                'settlement_id' => $settlement->id,
                'net_amount' => $settlement->net_amount,
                'organization_id' => $settlement->organization_id,
                'previous_status' => $originalStatus,
                'new_status' => 'rejected',
                'reason' => $data['reason'],
            ],
        ]);

        return redirect()
            ->route('admin.settlements.show', $settlement)
            ->with('success', 'Settlement rejected. Funds returned to balance.');
    }

    public function destroy(CampaignSettlement $settlement): RedirectResponse
    {
        if (in_array($settlement->status, ['paid', 'processing'], true)) {
            return redirect()
                ->route('admin.settlements.index')
                ->with('error', 'Paid or processing settlements cannot be deleted.');
        }

        $settlement->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'settlement_archived',
            'loggable_type' => CampaignSettlement::class,
            'loggable_id' => $settlement->id,
            'meta' => [
                'settlement_id' => $settlement->id,
                'net_amount' => $settlement->net_amount,
                'organization_id' => $settlement->organization_id,
                'previous_status' => $settlement->status,
            ],
        ]);

        return redirect()
            ->route('admin.settlements.index')
            ->with('success', 'Settlement archived successfully.');
    }
}
