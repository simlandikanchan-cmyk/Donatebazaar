<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\RecurringDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecurringDonationController extends Controller
{
    // ── Store a new recurring donation subscription ──
    public function store(Request $request, Campaign $campaign)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Please log in to set up recurring donations.');
        }

        $request->validate([
            'amount'    => 'required|numeric|min:10',
            'frequency' => 'required|in:daily,weekly,monthly,quarterly',
        ]);

        $existing = RecurringDonation::where('user_id', Auth::id())
            ->where('campaign_id', $campaign->id)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return back()->with('error',
                'You already have an active ' . $existing->frequency .
                ' donation of ₹' . number_format($existing->amount) .
                ' for this campaign. Please cancel it first.'
            );
        }

        // Shared helper now used here too — keeps store() and resume() in sync
        $nextBilling = RecurringDonation::calculateNextBilling($request->frequency);

        $recurring = RecurringDonation::create([
            'user_id'           => Auth::id(),
            'campaign_id'       => $campaign->id,
            'amount'            => $request->amount,
            'frequency'         => $request->frequency,
            'status'            => 'active',
            'next_billing_date' => $nextBilling,
            'billing_count'     => 0,
        ]);

        return redirect()->route('donate.redirect', [
            'campaign'     => $campaign->id,
            'amount'       => $request->amount,
            'recurring_id' => $recurring->id,
            'frequency'    => $request->frequency,
        ])->with('success',
            'Recurring ' . $request->frequency . ' donation of ₹' .
            number_format($request->amount) . ' set up successfully!'
        );
    }

    // ── List user's recurring donations ──
    public function index()
    {
        $recurring = RecurringDonation::where('user_id', Auth::id())
            ->with('campaign')
            ->latest()
            ->paginate(10);

        // Needed by the sidebar (mirrors what the KYC dashboard passes in)
        // for the "Campaigns" section counts (All / Active / Pending / etc.)
        $campaigns = Campaign::where('user_id', Auth::id())->get();

        // Badge shown next to "Recurring Donations" in the sidebar —
        // counts only active plans so it reads like "things currently running".
        $recurringCount = RecurringDonation::where('user_id', Auth::id())
            ->where('status', 'active')
            ->count();

        return view('recurring.index', compact('recurring', 'campaigns', 'recurringCount'));
    }

    // ── Cancel ──
    public function cancel(RecurringDonation $recurringDonation)
    {
        if ($recurringDonation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($recurringDonation->status === 'cancelled') {
            return back()->with('error', 'This donation is already cancelled.');
        }

        $recurringDonation->cancel();

        return back()->with('success', 'Recurring donation cancelled successfully.');
    }

    // ── Pause ──
    public function pause(RecurringDonation $recurringDonation)
    {
        if ($recurringDonation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($recurringDonation->status !== 'active') {
            return back()->with('error', 'Only active donations can be paused.');
        }

        $recurringDonation->pause();

        return back()->with('success', 'Recurring donation paused.');
    }

    // ── Resume ──
    public function resume(RecurringDonation $recurringDonation)
    {
        if ($recurringDonation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($recurringDonation->status !== 'paused') {
            return back()->with('error', 'Only paused donations can be resumed.');
        }

        $recurringDonation->resume();

        return back()->with('success', 'Recurring donation resumed.');
    }
}