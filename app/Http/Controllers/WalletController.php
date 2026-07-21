<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\Wallet;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Org/user wallet dashboard: balances + ledger + request-payout form.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $wallet = app(WalletService::class)->getOrCreateWallet($user);

        $transactions = $wallet->transactions()
            ->latest()
            ->paginate(20);

        // Eligible donations: completed, not refunded, hold window matured,
        // not already settled, and not locked in a pending/approved settlement.
        $lockedIds = DB::table('settlement_items')
            ->join('campaign_settlements', 'campaign_settlements.id', '=', 'settlement_items.campaign_settlement_id')
            ->whereIn('campaign_settlements.status', ['pending_approval', 'approved'])
            ->pluck('settlement_items.donation_id')
            ->all();

        $eligible = Donation::where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->where('is_refunded', false)
            ->whereNotNull('paid_at')
            ->where('paid_at', '<=', now()->subDays(WalletService::DEFAULT_HOLD_DAYS))
            ->where('settlement_status', 'pending')
            ->whereNotIn('id', $lockedIds)
            ->with('campaign:id,title')
            ->get();

        $pendingSettlements = CampaignSettlement::where('organization_id', $this->orgIdFor($user))
            ->whereIn('status', ['pending_approval', 'approved'])
            ->with('settlementItems')
            ->latest()
            ->get();

        $org = $this->orgFor($user);
        $payoutAccounts = $org ? $org->payoutAccounts()->latest()->get() : collect();

        return view('wallet.dashboard', compact(
            'wallet', 'transactions', 'eligible', 'pendingSettlements', 'payoutAccounts', 'org'
        ));
    }

    /**
     * Submit a payout/settlement request (locks funds, pending admin approval).
     */
    public function requestPayout(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $org = $this->ensureOrgFor($user);

        $donationIds = (array) $request->input('donation_ids', []);

        if (empty($donationIds)) {
            return redirect()
                ->route('dashboard.wallet')
                ->with('error', 'Select at least one eligible donation.');
        }

        if ($org->payoutAccounts()->count() === 0) {
            return redirect()
                ->route('dashboard.wallet')
                ->with('error', 'Add a payout account (bank or UPI) before requesting a payout.');
        }

        try {
            $settlement = app(SettlementService::class)->requestSettlement($org, $donationIds);
        } catch (InsufficientWalletBalanceException $e) {
            return redirect()
                ->route('dashboard.wallet')
                ->with('error', $e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->route('dashboard.wallet')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('dashboard.wallet')
            ->with('success', 'Payout request submitted. It is now pending admin approval.');
    }

    /**
     * Resolve the org record for the authenticated user (if any).
     */
    protected function orgFor($user): ?Organization
    {
        return Organization::where('user_id', $user->id)->first();
    }

    /**
     * Resolve the user's org, auto-creating a personal "individual"
     * organization for standalone fundraisers who have none. Settlements are
     * org-scoped, so every payout request needs an owning organization.
     */
    protected function ensureOrgFor($user): Organization
    {
        $org = $this->orgFor($user);

        if ($org) {
            return $org;
        }

        return Organization::create([
            'user_id' => $user->id,
            'name' => $user->name ?? ('User #'.$user->id),
            'type' => 'individual',
        ]);
    }

    /**
     * Save a payout account (bank/UPI) for the user's organization.
     */
    public function savePayoutAccount(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $org = $this->ensureOrgFor($user);

        $data = $request->validate([
            'account_holder_name' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'upi_id' => 'nullable|string|max:255',
        ]);

        if (! $data['bank_name'] && ! $data['upi_id']) {
            return redirect()
                ->route('dashboard.wallet')
                ->with('error', 'Please provide either bank details or a UPI ID.');
        }

        $org->payoutAccounts()->create($data);

        return redirect()
            ->route('dashboard.wallet')
            ->with('success', 'Payout account saved successfully.');
    }

    protected function orgIdFor($user): ?int
    {
        return $this->orgFor($user)?->id;
    }
}
