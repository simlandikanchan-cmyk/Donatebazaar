<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
        $originalStatus = $payoutAccount->is_verified;

        $payoutAccount->update([
            'is_verified' => true,
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'payout_account_verified',
            'loggable_type' => PayoutAccount::class,
            'loggable_id' => $payoutAccount->id,
            'meta' => [
                'payout_account_id' => $payoutAccount->id,
                'organization_id' => $payoutAccount->organization_id,
                'previous_status' => $originalStatus,
                'new_status' => true,
            ],
        ]);

        return redirect()->back()->with('success', 'Payout account marked as verified.');
    }

    /**
     * Reverse verification: mark a payout account as unverified.
     */
    public function unverify(Request $request, PayoutAccount $payoutAccount): RedirectResponse
    {
        $originalStatus = $payoutAccount->is_verified;

        $payoutAccount->update([
            'is_verified' => false,
            'verified_by' => null,
            'verified_at' => null,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'payout_account_unverified',
            'loggable_type' => PayoutAccount::class,
            'loggable_id' => $payoutAccount->id,
            'meta' => [
                'payout_account_id' => $payoutAccount->id,
                'organization_id' => $payoutAccount->organization_id,
                'previous_status' => $originalStatus,
                'new_status' => false,
            ],
        ]);

        return redirect()->back()->with('success', 'Payout account unverified.');
    }
}
