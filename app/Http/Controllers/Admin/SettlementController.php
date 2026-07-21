<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessSettlementPayout;
use App\Models\CampaignSettlement;
use App\Models\Refund;
use App\Services\SettlementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettlementController extends Controller
{
    /**
     * List all settlements, filterable by status, pending_approval first.
     */
    public function index(Request $request): View
    {
        $query = CampaignSettlement::with(['organization', 'settlementItems'])
            ->when($request->input('status'), function ($q) use ($request) {
                $q->where('status', $request->input('status'));
            });

        $order = (string) $request->input('status');
        if ($order === '') {
            $query->orderByRaw("FIELD(status, 'pending_approval') DESC")
                ->orderByDesc('created_at');
        } else {
            $query->latest();
        }

        $settlements = $query->paginate(25);

        return view('admin.settlements.index', compact('settlements'));
    }

    /**
     * Settlement detail: org, items, payout account, scrutiny flags.
     */
    public function show(CampaignSettlement $settlement): View
    {
        $settlement->load(['organization.payoutAccounts', 'settlementItems.donation.campaign']);

        $org = $settlement->organization;
        $payoutAccounts = $org?->payoutAccounts()->latest()->get() ?? collect();
        $payout = $payoutAccounts->firstWhere('is_verified', true);

        // "Needs extra scrutiny" heuristics.
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

    /**
     * Admin approves a pending_approval settlement.
     * Debits pending_settlement_balance, marks approved, queues payout.
     */
    public function approve(Request $request, CampaignSettlement $settlement): RedirectResponse
    {
        try {
            app(SettlementService::class)->approveSettlement($settlement, auth()->user());
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->route('admin.settlements.show', $settlement)
                ->with('error', $e->getMessage());
        }

        ProcessSettlementPayout::dispatch($settlement);

        return redirect()
            ->route('admin.settlements.show', $settlement)
            ->with('success', 'Settlement approved. Payout processing started.');
    }

    /**
     * Admin rejects a pending_approval settlement (requires a reason).
     */
    public function reject(Request $request, CampaignSettlement $settlement): RedirectResponse
    {
        $data = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            app(SettlementService::class)->rejectSettlement($settlement, auth()->user(), $data['reason']);
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->route('admin.settlements.show', $settlement)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.settlements.show', $settlement)
            ->with('success', 'Settlement rejected. Funds returned to balance.');
    }
}
