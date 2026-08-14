<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Repositories\DonationRepository;
use App\Repositories\SettlementRepository;
use App\Repositories\WalletRepository;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function __construct(
        private WalletService $walletService,
        private SettlementService $settlementService,
        private WalletRepository $walletRepo,
        private DonationRepository $donationRepo,
        private SettlementRepository $settlementRepo,
    ) {}

    public function index(Request $request): View
    {
        $user = auth()->user();
        $wallet = $this->walletService->getOrCreateWallet($user);

        $transactions = $wallet->transactions()
            ->latest()
            ->paginate(20);

        $org = $this->orgFor($user);
        $orgId = $org?->id;

        $lockedIds = $orgId
            ? $this->walletRepo->getDonationIdsFromSettlements($orgId)->all()
            : [];

        $eligible = Donation::whereHas('campaign', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('payment_status', 'completed')
            ->where('is_refunded', false)
            ->whereNotNull('paid_at')
            ->where('paid_at', '<=', now()->subDays(WalletService::DEFAULT_HOLD_DAYS))
            ->where('settlement_status', 'pending')
            ->whereNotIn('id', $lockedIds)
            ->with('campaign:id,title')
            ->get();

        $pendingSettlements = $orgId
            ? CampaignSettlement::where('organization_id', $orgId)
                ->whereIn('status', ['pending_approval', 'manual_review', 'auto_approved', 'approved', 'failed'])
                ->with('settlementItems', 'payoutAttempt')
                ->latest()
                ->get()
            : collect();

        $payoutAccounts = $org ? $org->payoutAccounts()->latest()->get() : collect();

        return view('wallet.dashboard', compact(
            'wallet', 'transactions', 'eligible', 'pendingSettlements', 'payoutAccounts', 'org'
        ));
    }

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
            $settlement = $this->settlementService->requestSettlement($org, $donationIds);
        } catch (InsufficientWalletBalanceException | \InvalidArgumentException $e) {
            return redirect()
                ->route('dashboard.wallet')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('dashboard.wallet')
            ->with('success', 'Payout request submitted. It is now pending admin approval.');
    }

    protected function orgFor($user): ?Organization
    {
        return Organization::where('user_id', $user->id)->first();
    }

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
            ->with('success', 'Payout account saved.');
    }
}
