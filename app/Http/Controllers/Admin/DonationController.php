<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DonationRefundMail;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Refund;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\Error as RazorpayError;

class DonationController extends Controller
{
    /**
     * List donations — paginated, filterable by status / campaign, donor search.
     */
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

        $campaigns = Campaign::query()->orderBy('title')->pluck('title', 'id');

        $counts = [
            'total'      => Donation::count(),
            'completed'  => Donation::where('payment_status', 'completed')->count(),
            'refunded'   => Donation::where('is_refunded', true)->count(),
            'refundable' => Donation::where('payment_status', 'completed')
                                    ->where('is_refunded', false)->count(),
        ];

        return view('admin.donations.index', compact('donations', 'campaigns', 'counts'));
    }

    /**
     * Donation detail + refund history.
     */
    public function show(Donation $donation): View
    {
        $donation->load(['campaign', 'user', 'coupon', 'refunds']);

        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Admin-triggered full refund.
     *
     * Wrapped in a Cache::lock keyed per-donation so that a double-click,
     * duplicate tab submit, or client-side retry cannot fire two Razorpay
     * refund API calls for the same donation. The DB::transaction's
     * lockForUpdate() below only protects the database row — it does NOT
     * stop two concurrent requests from both reaching the gateway before
     * either has saved anything, so the lock has to wrap the guard check
     * and the gateway call as well, not just the DB write.
     */
    public function refund(Request $request, Donation $donation): RedirectResponse
    {
        $lock = Cache::lock('donation_refund_' . $donation->id, 30);

        if (! $lock->get()) {
            return redirect()
                ->route('admin.donations.show', $donation)
                ->with('error', 'A refund is already being processed for this donation.');
        }

        try {
            // Re-fetch fresh state now that we hold the lock — the $donation
            // passed in via route-model-binding may be stale if another
            // request (or the webhook) changed it moments ago.
            $donation->refresh();

            // (a) Guard: only completed, not-yet-refunded donations.
            if ($donation->payment_status !== 'completed' || $donation->is_refunded) {
                return redirect()
                    ->route('admin.donations.show', $donation)
                    ->with('error', 'Only completed, non-refunded donations can be refunded.');
            }

            $paymentId = $donation->payment_id;

            if (empty($paymentId) || ! preg_match('/^pay_[A-Za-z0-9]{14,}$/', $paymentId)) {
                return redirect()
                    ->route('admin.donations.show', $donation)
                    ->with('error', 'This donation has no valid Razorpay payment id and cannot be refunded.');
            }

            $api = $this->getRazorpayApi();

            try {
                // (b) Full refund (total_amount → paise). Payment must be fetched so id is set.
                $razorpayRefund = $api->payment
                    ->fetch($paymentId)
                    ->refund(['amount' => (int) round($donation->total_amount * 100)]);
            } catch (RazorpayError $e) {
                // (d) API failure: do NOT modify donation fields; log a failed Refund row.
                Log::error('Admin refund failed at gateway', [
                    'donation_id' => $donation->id,
                    'payment_id'  => $donation->payment_id,
                    'message'     => $e->getMessage(),
                ]);

                Refund::create([
                    'donation_id'         => $donation->id,
                    'donation_payment_id' => null,
                    'gateway_refund_id'   => null,
                    'amount'              => $donation->total_amount,
                    'reason'              => 'Admin refund failed at gateway: ' . $e->getMessage(),
                    'status'              => 'failed',
                    'processed_at'        => null,
                ]);

                return redirect()
                    ->route('admin.donations.show', $donation)
                    ->with('error', 'Refund failed at the payment gateway: ' . $e->getMessage());
            }

            // (c) Success: persist inside a transaction with a row lock + re-checked guard.
            $refundRecord = null;

            DB::transaction(function () use ($donation, $razorpayRefund, $request, &$refundRecord) {
                $locked = Donation::lockForUpdate()->where('id', $donation->id)->first();

                // Idempotency guard: a webhook may have already refunded this.
                if ($locked->payment_status !== 'completed' || $locked->is_refunded) {
                    return;
                }

                $locked->payment_status = 'refunded';
                $locked->is_refunded    = true;
                $locked->refunded_at    = now();
                $locked->save();

                $refundRecord = Refund::create([
                    'donation_id'         => $locked->id,
                    'donation_payment_id' => null,
                    'gateway_refund_id'   => $razorpayRefund->id,
                    'amount'              => $donation->total_amount,
                    'reason'              => $request->input('reason'),
                    'status'              => 'processed',
                    'processed_at'        => now(),
                ]);
            });

            if ($refundRecord && $donation->donor_email) {
                Mail::to($donation->donor_email)->send(new DonationRefundMail($donation, $refundRecord));
            }

            return redirect()
                ->route('admin.donations.show', $donation)
                ->with('success', 'Refund of ₹' . number_format($donation->total_amount, 2) . ' initiated successfully.');

        } finally {
            $lock->release();
        }
    }

    /**
     * Build a configured Razorpay API client (mirrors PaymentController).
     */
    private function getRazorpayApi(): Api
    {
        $key    = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if (empty($key) || empty($secret)) {
            Log::critical('Razorpay credentials missing.');
            throw new \RuntimeException('Payment gateway configuration missing.');
        }

        return new Api($key, $secret);
    }
}