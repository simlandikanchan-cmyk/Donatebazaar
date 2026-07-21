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
        $wallets = Wallet::with('owner')
            ->when($request->input('q'), function ($q) use ($request) {
                $search = str_replace(['%', '_'], ['\%', '\_'], $request->input('q'));
                $q->whereHasMorph('owner', ['App\Models\User', 'App\Models\Organization'], function ($o) use ($search) {
                    $o->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(25);

        return view('admin.wallets.index', compact('wallets'));
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

        return view('admin.wallets.show', compact('wallet', 'transactions', 'payoutAccounts'));
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

        try {
            if ($data['direction'] === 'credit') {
                app(WalletService::class)->credit(
                    $wallet, $amount, WalletTransaction::SOURCE_ADJUSTMENT,
                    'adjust_'.now()->timestamp.'_'.$wallet->id, Wallet::class, $data['notes']
                );
            } else {
                app(WalletService::class)->debit(
                    $wallet, $amount, WalletTransaction::SOURCE_ADJUSTMENT,
                    'adjust_'.now()->timestamp.'_'.$wallet->id, Wallet::class, $data['notes']
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
