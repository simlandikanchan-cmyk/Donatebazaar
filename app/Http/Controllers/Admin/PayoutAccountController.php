<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PayoutAccountController extends Controller
{
    /**
     * Mark a payout account as verified.
     */
    public function verify(Request $request, PayoutAccount $payoutAccount): RedirectResponse
    {
        $payoutAccount->update([
            'is_verified' => true,
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Payout account marked as verified.');
    }

    /**
     * Reverse verification: mark a payout account as unverified.
     */
    public function unverify(Request $request, PayoutAccount $payoutAccount): RedirectResponse
    {
        $payoutAccount->update([
            'is_verified' => false,
            'verified_by' => null,
            'verified_at' => null,
        ]);

        return redirect()->back()->with('success', 'Payout account unverified.');
    }
}
