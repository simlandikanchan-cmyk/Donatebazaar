<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                $q->where('owner_type', User::class);
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
            'users' => Wallet::where('owner_type', User::class)->count(),
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
            'amount' => 'required|numeric|min:0.01|max:100000',
            'notes' => 'required|string|max:1000',
        ]);

        $amount = (float) $data['amount'];

        $referenceId = (int) now()->format('Uu');

        $originalBalance = $wallet->balance;

        try {
            if ($data['direction'] === 'credit') {
                app(WalletService::class)->credit(
                    $wallet, $amount, WalletTransaction::SOURCE_ADJUSTMENT,
                    $referenceId, Wallet::class, $data['notes'],
                    User::class, auth()->id()
                );
            } else {
                app(WalletService::class)->debit(
                    $wallet, $amount, WalletTransaction::SOURCE_ADJUSTMENT,
                    $referenceId, Wallet::class, $data['notes'],
                    User::class, auth()->id()
                );
            }
        } catch (InsufficientWalletBalanceException $e) {
            return redirect()
                ->route('admin.wallets.show', $wallet)
                ->with('error', $e->getMessage());
        }

        $wallet->refresh();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'wallet_adjusted',
            'loggable_type' => Wallet::class,
            'loggable_id' => $wallet->id,
            'meta' => [
                'wallet_id' => $wallet->id,
                'owner_type' => $wallet->owner_type,
                'owner_id' => $wallet->owner_id,
                'direction' => $data['direction'],
                'amount' => $amount,
                'previous_balance' => $originalBalance,
                'new_balance' => $wallet->balance,
                'notes' => $data['notes'],
            ],
        ]);

        return redirect()
            ->route('admin.wallets.show', $wallet)
            ->with('success', 'Wallet adjusted ('.$data['direction'].').');
    }

    public function destroy(Request $request, Wallet $wallet): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $originalBalance = $wallet->balance;
        $transactionCount = $wallet->transactions()->count();

        if ($transactionCount > 0) {
            return redirect()
                ->route('admin.wallets.show', $wallet)
                ->with('error', 'Wallets with financial transaction history cannot be deleted. Use archival or contact support.');
        }

        DB::transaction(function () use ($wallet, $request) {
            $wallet->delete();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'wallet_archived',
                'loggable_type' => Wallet::class,
                'loggable_id' => $wallet->id,
                'meta' => [
                    'wallet_id' => $wallet->id,
                    'owner_type' => $wallet->owner_type,
                    'owner_id' => $wallet->owner_id,
                    'previous_balance' => $originalBalance,
                    'reason' => $request->input('reason'),
                ],
            ]);
        });

        return redirect()
            ->route('admin.wallets.index')
            ->with('success', 'Wallet archived successfully.');
    }
}
