<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Donation;
use App\Models\Refund;
use App\Services\Payment\RefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function __construct(
        private RefundService $refundService
    ) {}

    public function index(Request $request): View
    {
        $query = Donation::query()
            ->with(['campaign:id,title,slug', 'user:id,name,email'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('donor_name', 'like', "%{$q}%")
                    ->orWhere('donor_email', 'like', "%{$q}%")
                    ->orWhere('receipt_number', 'like', "%{$q}%")
                    ->orWhere('payment_id', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $donations = $query->paginate(20)->withQueryString();

        $campaigns = \App\Models\Campaign::query()->orderBy('title')->pluck('title', 'id');

        $counts = [
            'total' => Donation::count(),
            'completed' => Donation::where('payment_status', 'completed')->count(),
            'refunded' => Donation::where('is_refunded', true)->count(),
            'refundable' => Donation::where('payment_status', 'completed')
                ->where('is_refunded', false)->count(),
        ];

        return view('admin.donations.index', compact('donations', 'campaigns', 'counts'));
    }

    public function show(Donation $donation): View
    {
        $donation->load(['campaign', 'user', 'coupon', 'refunds', 'items.product']);

        return view('admin.donations.show', compact('donation'));
    }

    public function refund(Request $request, Donation $donation): RedirectResponse
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $donation->refresh();

        if ($donation->is_refunded) {
            return redirect()
                ->route('admin.donations.show', $donation)
                ->with('info', 'This donation has already been refunded.');
        }

        if ($donation->payment_status !== 'completed') {
            return redirect()
                ->route('admin.donations.show', $donation)
                ->with('error', 'Only completed donations can be refunded.');
        }

        try {
            $refundRecord = $this->refundService->processAdminRefund(
                $donation,
                auth()->user(),
                $request->input('reason', 'Admin refund')
            );
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('admin.donations.show', $donation)
                ->with('error', $e->getMessage());
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'donation_refunded',
            'loggable_type' => Donation::class,
            'loggable_id' => $donation->id,
            'meta' => [
                'donation_id' => $donation->id,
                'amount' => $donation->total_amount,
                'donor_email' => $donation->donor_email,
                'campaign_id' => $donation->campaign_id,
                'previous_status' => 'completed',
                'new_status' => 'refunded',
                'refund_id' => $refundRecord->id,
                'gateway_refund_id' => $refundRecord->gateway_refund_id,
                'reason' => $request->input('reason', 'Admin refund'),
            ],
        ]);

        return redirect()
            ->route('admin.donations.show', $donation)
            ->with('success', 'Refund of ₹'.number_format($donation->total_amount, 2).' initiated successfully.');
    }

    public function destroy(Donation $donation): RedirectResponse
    {
        $originalStatus = $donation->payment_status;

        if ($originalStatus === 'completed') {
            return redirect()
                ->route('admin.donations.index')
                ->with('error', 'Completed donations cannot be deleted. Use the refund action instead.');
        }

        if ($donation->is_refunded) {
            return redirect()
                ->route('admin.donations.index')
                ->with('error', 'Refunded donations cannot be deleted.');
        }

        if (in_array($originalStatus, ['pending', 'processing'], true)) {
            $donation->payment_status = 'cancelled';
            $donation->save();
        }

        $donation->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'donation_archived',
            'loggable_type' => Donation::class,
            'loggable_id' => $donation->id,
            'meta' => [
                'donation_id' => $donation->id,
                'amount' => $donation->total_amount,
                'donor_email' => $donation->donor_email,
                'campaign_id' => $donation->campaign_id,
                'previous_status' => $originalStatus,
                'new_status' => $donation->payment_status,
            ],
        ]);

        return redirect()
            ->route('admin.donations.index')
            ->with('success', 'Donation archived successfully.');
    }
}
