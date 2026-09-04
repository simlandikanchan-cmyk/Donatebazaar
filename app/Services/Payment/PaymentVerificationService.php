<?php

namespace App\Services\Payment;

use App\Gateways\RazorpayGateway;
use App\Models\Campaign;
use App\Models\Donation;
use App\Services\Payment\DonationCompletionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentVerificationService
{
    private const LOCK_TTL = 60;

    private const RATE_LIMIT_HITS = 30;

    private const RATE_LIMIT_WINDOW = 60;

    public function __construct(
        private RazorpayGateway $gateway,
        private DonationCompletionService $completionService
    ) {}

    public function verifyPayment(Request $request): JsonResponse
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'donation_id' => 'required|integer|exists:donations,id',
        ]);

        $rateLimitKey = 'payment_verify_'.(Auth::id() ?? $request->ip());

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($rateLimitKey, self::RATE_LIMIT_HITS)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many attempts. Please wait.',
            ], 429);
        }

        \Illuminate\Support\Facades\RateLimiter::hit($rateLimitKey, self::RATE_LIMIT_WINDOW);

        $lockKey = 'payment_lock_'.$request->razorpay_payment_id;
        $lock = Cache::lock($lockKey, self::LOCK_TTL);

        if (! $lock->get()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment already processing.',
            ], 429);
        }

        try {
            $this->gateway->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            $donation = Donation::with('campaign.category')
                ->where('id', $request->donation_id)
                ->where('order_id', $request->razorpay_order_id)
                ->firstOrFail();

            if ($donation->user_id !== Auth::id()) {
                Log::channel('payments')->warning('Payment verification rejected — ownership mismatch', [
                    'donation_id' => $donation->id,
                    'donation_user_id' => $donation->user_id,
                    'authenticated_user_id' => Auth::id(),
                    'ip' => $request->ip(),
                ]);

                abort(403, 'You are not authorized to verify this donation.');
            }

            if ($donation->payment_status === 'completed') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment already completed.',
                    'redirect_url' => $this->campaignUrl($donation->campaign),
                ]);
            }

            $this->gateway->verifyPaymentDetails(
                $request->razorpay_payment_id,
                $request->razorpay_order_id,
                (float) $donation->total_amount,
                (string) $donation->currency
            );

            $donation->payment_id = $request->razorpay_payment_id;
            $donation->signature = $request->razorpay_signature;

            $result = $this->completionService->complete(
                $donation,
                $request->razorpay_payment_id,
                $request->razorpay_signature
            );

            $donation->refresh();
            $donation->load('campaign.category');

            Log::channel('payments')->info('Payment completed', [
                'donation_id' => $donation->id,
                'campaign_id' => $donation->campaign_id,
                'donation_type' => $donation->donation_type,
                'total_amount' => $donation->total_amount,
                'platform_fee' => $donation->platform_fee,
                'net_amount' => $donation->net_amount,
                'payment_id' => $request->razorpay_payment_id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful. Thank you for your donation!',
                'redirect_url' => $this->campaignUrl($donation->campaign),
                'paid_at' => $donation->paid_at?->toISOString(),
            ]);

        } catch (SignatureVerificationError $e) {
            Log::channel('payments')->warning('Razorpay signature verification failed', [
                'order_id' => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
            ]);

            DB::table('donations')
                ->where('order_id', $request->razorpay_order_id)
                ->where('payment_status', 'pending')
                ->update(['payment_status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Any deducted amount will be refunded in 5–7 days.',
            ], 400);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::channel('payments')->error('Donation not found during verification', [
                'donation_id' => $request->donation_id,
                'order_id' => $request->razorpay_order_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Donation record not found. Please contact support.',
            ], 404);

        } catch (\Throwable $e) {
            Log::channel('payments')->error('Payment verification exception', [
                'message' => $e->getMessage(),
                'order_id' => $request->razorpay_order_id ?? null,
                'payment_id' => $request->razorpay_payment_id ?? null,
                'donation_id' => $request->donation_id ?? null,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed. Please contact support.',
            ], 500);

        } finally {
            $lock->release();
        }
    }

    private function campaignUrl(Campaign $campaign): string
    {
        if (! $campaign->relationLoaded('category')) {
            $campaign->load('category');
        }

        return route('campaign.public', [
            'category' => $campaign->category->slug,
            'slug' => $campaign->slug,
        ]);
    }
}
