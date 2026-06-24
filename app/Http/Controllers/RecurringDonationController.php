<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\RecurringDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'frequency' => 'required|in:daily,weekly,monthly,quarterly', // ← updated
        ]);

        // Check if user already has an active recurring donation for this campaign
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

        // Calculate next billing date based on frequency
        $nextBilling = match($request->frequency) {       // ← updated
            'daily'     => Carbon::now()->addDay(),
            'weekly'    => Carbon::now()->addWeek(),
            'monthly'   => Carbon::now()->addMonth(),
            'quarterly' => Carbon::now()->addMonths(3),
        };

        // Create the recurring donation record
        $recurring = RecurringDonation::create([
            'user_id'           => Auth::id(),
            'campaign_id'       => $campaign->id,
            'amount'            => $request->amount,
            'frequency'         => $request->frequency,
            'status'            => 'active',
            'next_billing_date' => $nextBilling,
            'billing_count'     => 0,
        ]);

        // Process the FIRST donation immediately via Razorpay
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
            ->get();

        return view('recurring.index', compact('recurring'));
    }

    // ── Cancel ──
    public function cancel(RecurringDonation $recurringDonation)
    {
        if ($recurringDonation->user_id !== Auth::id()) {
            abort(403);
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

        $recurringDonation->pause();

        return back()->with('success', 'Recurring donation paused.');
    }

    // ── Resume ──
    public function resume(RecurringDonation $recurringDonation)
    {
        if ($recurringDonation->user_id !== Auth::id()) {
            abort(403);
        }

        $recurringDonation->resume();

        return back()->with('success', 'Recurring donation resumed.');
    }
}