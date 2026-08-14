<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * List all wallets with balances.
     */
    public function index(Request $request): View
    {
        $filter = $request->input('filter', 'all');

        $wallets = Wallet::with('owner')
            ->when($filter === 'users', function ($q) {
                $q->where('owner_type', App\Models\User::class);
            })
            ->when($filter === 'organizations', function ($q) {
                $q->where('owner_type', App\Models\Organization::class);
            })
            ->when($request->input('q'), function ($q) use ($request) {
                $search = str_replace(['%', '_'], ['\%', '\_'], $request->input('q'));
                $q->whereHasMorph('owner', ['App\Models\User', 'App\Models\Organization'], function ($o) use ($search) {
                    $o->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(25)
            ->appends($request->except('page'));

        $stats = [
            'total' => Wallet::count(),
            'total_balance' => Wallet::sum('balance'),
            'users' => Wallet::where('owner_type', App\Models\User::class)->count(),
            'organizations' => Wallet::where('owner_type', App\Models\Organization::class)->count(),
        ];

        return view('admin.wallets.index', compact('wallets', 'stats', 'filter'));
    }

    /**
     * Full ledger for one wallet + manual adjustment form.
     */
    public function show(Wallet $wallet): View
    {
        $transactions = $wallet->transactions()->latest()->paginate(30);

        $payoutAccounts = $wallet->owner_type === 'App\Models\Organization'
            ? $wallet->owner->payoutAccounts()->latest()->get()
            : collect();

        $txStats = [
            'total' => $wallet->transactions()->count(),
            'credits' => $wallet->transactions()->where('type', 'credit')->count(),
            'debits' => $wallet->transactions()->where('type', 'debit')->count(),
            'total_credited' => $wallet->transactions()->where('type', 'credit')->sum('amount'),
            'total_debited' => $wallet->transactions()->where('type', 'debit')->sum('amount'),
        ];

        return view('admin.wallets.show', compact('wallet', 'transactions', 'payoutAccounts', 'txStats'));
    }

    /**
     * Manual admin adjustment (credit/debit) with a required notes reason.
     */
    public function adjust(Request $request, Wallet $wallet): RedirectResponse
    {
        $data = $request->validate([
            'direction' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'required|string|max:1000',
        ]);

        $amount = (float) $data['amount'];

        // reference_id is an unsignedBigInteger column (with a unique index on
        // wallet_id/reference_type/reference_id/source/type), so each adjustment
        // gets a unique integer id — microsecond timestamp fits in BIGINT and
        // cannot collide with system references (donation/refund/settlement ids).
        $referenceId = (int) now()->format('Uu');

        try {
            if ($data['direction'] === 'credit') {
                app(WalletService::class)->credit(
                    $wallet, $amount, WalletTransaction::SOURCE_ADJUSTMENT,
                    $referenceId, Wallet::class, $data['notes']
                );
            } else {
                app(WalletService::class)->debit(
                    $wallet, $amount, WalletTransaction::SOURCE_ADJUSTMENT,
                    $referenceId, Wallet::class, $data['notes']
                );
            }
        } catch (InsufficientWalletBalanceException $e) {
            return redirect()
                ->route('admin.wallets.show', $wallet)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.wallets.show', $wallet)
            ->with('success', 'Wallet adjusted ('.$data['direction'].').');
    }
}
