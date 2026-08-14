<?php

namespace App\Services\Payment;

use App\Gateways\RazorpayGateway;
use App\Mail\DonationReceiptMail;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\ProductReservation;
use App\Services\CouponService;
use App\Services\ProductReservationService;
use App\Services\WalletService;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentVerificationService
{
    private const LOCK_TTL = 60;

    private const RATE_LIMIT_HITS = 30;

    private const RATE_LIMIT_WINDOW = 60;

    public function __construct(
        private RazorpayGateway $gateway,
        private CouponService $couponService,
        private ProductReservationService $productReservationService,
        private WalletService $walletService
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

            if ($donation->payment_status === 'completed') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment already completed.',
                    'redirect_url' => $this->campaignUrl($donation->campaign),
                ]);
            }

            DB::transaction(function () use ($donation, $request) {
                $lockedDonation = Donation::lockForUpdate()
                    ->findOrFail($donation->id);

                if ($lockedDonation->payment_status === 'completed') {
                    return;
                }

                $lockedDonation->payment_id = $request->razorpay_payment_id;
                $lockedDonation->signature = $request->razorpay_signature;
                $lockedDonation->payment_status = 'completed';
                $lockedDonation->paid_at = now();
                $lockedDonation->save();

                Campaign::lockForUpdate()
                    ->findOrFail($lockedDonation->campaign_id)
                    ->increment('platform_earnings', $lockedDonation->platform_fee);

                $this->decrementProductStock($lockedDonation);
                $this->consumeReservations($lockedDonation);
                $this->redeemCoupon($lockedDonation);

                $owner = $this->walletService->ownerForDonation($lockedDonation);
                if ($owner) {
                    $wallet = $this->walletService->getOrCreateWallet($owner);
                    $this->walletService->credit(
                        $wallet,
                        (float) $lockedDonation->net_amount,
                        WalletTransaction::SOURCE_DONATION,
                        $lockedDonation->id,
                        Donation::class,
                        'Donation #'.$lockedDonation->id
                    );
                }
            });

            $donation->refresh();
            $donation->load('campaign.category');

            Log::info('Payment completed', [
                'donation_id' => $donation->id,
                'campaign_id' => $donation->campaign_id,
                'donation_type' => $donation->donation_type,
                'total_amount' => $donation->total_amount,
                'platform_fee' => $donation->platform_fee,
                'net_amount' => $donation->net_amount,
                'payment_id' => $request->razorpay_payment_id,
                'user_id' => Auth::id(),
            ]);

            $this->sendReceiptEmail($donation);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful. Thank you for your donation!',
                'redirect_url' => $this->campaignUrl($donation->campaign),
                'paid_at' => $donation->paid_at?->toISOString(),
            ]);

        } catch (SignatureVerificationError $e) {
            Log::warning('Razorpay signature verification failed', [
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
            Log::error('Donation not found during verification', [
                'donation_id' => $request->donation_id,
                'order_id' => $request->razorpay_order_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Donation record not found. Please contact support.',
            ], 404);

        } catch (\Throwable $e) {
            Log::error('Payment verification exception', [
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

    private function decrementProductStock(Donation $donation): void
    {
        if ($donation->donation_type !== 'product') {
            return;
        }

        $items = DonationItem::where('donation_id', $donation->id)->get();

        foreach ($items as $item) {
            CampaignProduct::where('id', $item->product_id)
                ->where('remaining_quantity', '>=', $item->quantity)
                ->decrement('remaining_quantity', $item->quantity);
        }

        Log::info('Product stock decremented after payment', [
            'donation_id' => $donation->id,
            'items' => $items->count(),
        ]);
    }

    private function consumeReservations(Donation $donation): void
    {
        if ($donation->donation_type !== 'product') {
            return;
        }

        $this->productReservationService->consume($donation);

        Log::info('Product reservations consumed after payment', [
            'donation_id' => $donation->id,
        ]);
    }

    private function redeemCoupon(Donation $donation): void
    {
        if (! $donation->coupon_id) {
            return;
        }

        $coupon = Coupon::lockForUpdate()->find($donation->coupon_id);

        if (! $coupon) {
            return;
        }

        if (CouponRedemption::where('donation_id', $donation->id)->exists()) {
            return;
        }

        [$valid] = $coupon->isValidFor(
            $donation->user,
            $donation->campaign,
            (float) $donation->original_amount
        );

        if (! $valid) {
            return;
        }

        $coupon->increment('used_count');

        if ($coupon->user_id) {
            $coupon->redeemed_at = now();
            $coupon->save();
        }

        CouponRedemption::create([
            'coupon_id' => $coupon->id,
            'user_id' => $donation->user_id,
            'donation_id' => $donation->id,
            'discount_amount' => $donation->discount_amount,
            'created_at' => now(),
        ]);

        Log::info('Coupon redeemed', [
            'coupon_id' => $coupon->id,
            'donation_id' => $donation->id,
            'discount' => $donation->discount_amount,
        ]);
    }

    private function sendReceiptEmail(Donation $donation): void
    {
        if (empty($donation->donor_email)) {
            return;
        }

        try {
            Mail::to($donation->donor_email)
                ->send(new DonationReceiptMail($donation));

            Log::info('Donation receipt email sent', [
                'donation_id' => $donation->id,
                'email' => $donation->donor_email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Donation receipt email failed', [
                'donation_id' => $donation->id,
                'email' => $donation->donor_email,
                'error' => $e->getMessage(),
            ]);
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
