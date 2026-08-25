<?php

namespace App\Services\Payment;

use App\Exceptions\InsufficientStockException;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\ProductReservation;
use App\Services\CouponService;
use App\Services\ProductReservationService;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PaymentOrderService
{
    private const RATE_LIMIT_HITS = 30;

    private const RATE_LIMIT_WINDOW = 60;

    public function __construct(
        private CouponService $couponService,
        private ProductReservationService $productReservationService,
        private WalletService $walletService
    ) {}

    private function getMinAmount(): int
    {
        return (int) config('services.donation.min_amount', 1);
    }

    private function getMaxAmount(): int
    {
        return (int) config('services.donation.max_amount', 500000);
    }

    private function getPlatformFeePercent(): float
    {
        return (float) config('services.donation.platform_fee_percent', 5.0);
    }

    private function getCurrency(): string
    {
        return (string) config('services.donation.currency', 'INR');
    }

    public function initiateDonation(Request $request, Campaign $campaign): RedirectResponse
    {
        $rateLimitKey = 'donate_redirect_'.(Auth::id() ?? $request->ip());

        if (RateLimiter::tooManyAttempts($rateLimitKey, self::RATE_LIMIT_HITS)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', "Too many attempts. Please wait {$seconds} seconds.");
        }

        RateLimiter::hit($rateLimitKey, self::RATE_LIMIT_WINDOW);

        $minAmount = $this->getMinAmount();
        $maxAmount = $this->getMaxAmount();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'amount' => [
                'required',
                'numeric',
                'min:'.$minAmount,
                'max:'.$maxAmount,
            ],
        ], [
            'amount.required' => 'Please enter a donation amount.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Minimum donation is ₹'.$minAmount.'.',
            'amount.max' => 'Maximum donation is ₹'.number_format($maxAmount).'.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', $validator->errors()->first('amount'));
        }

        $amount = (float) $request->amount;
        $enteredAmount = $amount;
        $couponData = null;

        $code = trim((string) $request->input('coupon_code', ''));

        if ($code !== '') {
            $couponResult = $this->couponService->validate($code, Auth::user(), $campaign, $enteredAmount);

            if ($couponResult['valid']) {
                $amount = $couponResult['discounted_total'];
                $couponData = [
                    'id' => $couponResult['coupon']->id,
                    'code' => $couponResult['coupon']->code,
                    'discount' => $couponResult['discount_amount'],
                    'original' => $enteredAmount,
                ];
            }
        }

        if ($amount < $minAmount || $amount > $maxAmount) {
            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', 'Invalid donation amount.');
        }

        $state = $this->resolveState($campaign);

        if ($state !== 'active') {
            $messages = [
                'expired' => 'This campaign has ended and is no longer accepting donations.',
                'paused' => 'This campaign is currently paused.',
                'rejected' => 'This campaign is not available for donations.',
                'completed' => 'This campaign has already reached its goal.',
                'inactive' => 'This campaign is not currently active.',
                'pending' => 'This campaign is pending approval.',
            ];

            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', $messages[$state] ?? 'This campaign is not accepting donations right now.');
        }

        $reservationIds = [];
        $cartType = $request->input('donation_type', 'money');
        $productIdsRaw = $request->input('product_ids', '');
        $productQtysRaw = $request->input('product_qtys', '');

        if ($cartType === 'products' && $productIdsRaw !== '') {
            $pIds = array_values(array_filter(explode(',', $productIdsRaw)));
            $pQtys = array_values(array_filter(explode(',', $productQtysRaw)));

            $items = [];
            foreach ($pIds as $i => $pid) {
                $items[] = [
                    'product_id' => (int) trim($pid),
                    'quantity' => (int) trim($pQtys[$i] ?? 1),
                ];
            }

            try {
                $reservationIds = $this->productReservationService->reserve(
                    $items,
                    Session::getId()
                );
            } catch (InsufficientStockException $e) {
                Log::channel('payments')->info('Product reservation failed', [
                    'campaign_id' => $campaign->id,
                    'error' => $e->getMessage(),
                ]);

                return redirect()
                    ->route('campaign.public', [
                        'category' => $campaign->category->slug ?? '',
                        'slug' => $campaign->slug,
                    ])
                    ->with('error', $e->getMessage());
            }
        }

        Session::put([
            'donation_amount' => $amount,
            'donation_original_amount' => $enteredAmount,
            'donation_discount' => $couponData ? $couponData['discount'] : 0,
            'donation_coupon_code' => $couponData ? $couponData['code'] : null,
            'donation_coupon_id' => $couponData ? $couponData['id'] : null,
            'donation_campaign' => (string) $campaign->id,
            'donation_session_at' => now()->timestamp,
            'donation_reservation_ids' => $reservationIds,
            'donation_cart' => [
                'ids' => $request->input('product_ids', ''),
                'qtys' => $request->input('product_qtys', ''),
                'type' => $request->input('donation_type', 'money'),
            ],
        ]);

        Log::channel('payments')->info('Donation session created', [
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'amount' => $amount,
            'donation_type' => $request->input('donation_type', 'money'),
            'product_ids' => $request->input('product_ids', ''),
        ]);

        return redirect()->route('payment.page', $campaign->id);
    }

    public function showPaymentPage(Campaign $campaign): View|RedirectResponse
    {
        $amount = Session::get('donation_amount');
        $campaignId = Session::get('donation_campaign');
        $sessionAt = Session::get('donation_session_at');

        Log::channel('payments')->debug('Payment page session check', [
            'amount' => $amount,
            'session_campaign' => $campaignId,
            'route_campaign' => $campaign->id,
            'user_id' => Auth::id(),
        ]);

        if (empty($amount) || empty($campaignId) || (string) $campaignId !== (string) $campaign->id) {
            $this->clearDonationSession();

            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', 'Invalid donation session. Please try again.');
        }

        if (empty($sessionAt) || (now()->timestamp - (int) $sessionAt) > 900) {
            $this->clearDonationSession();

            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', 'Your session expired. Please try again.');
        }

        $state = $this->resolveState($campaign);

        if ($state !== 'active') {
            $this->clearDonationSession();

            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', 'This campaign is no longer active.');
        }

        $amount = (float) $amount;
        $minAmount = $this->getMinAmount();
        $maxAmount = $this->getMaxAmount();

        if ($amount < $minAmount || $amount > $maxAmount) {
            $this->clearDonationSession();

            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', 'Invalid donation amount.');
        }

        $originalAmount = (float) Session::get('donation_original_amount', $amount);
        $discountAmount = (float) Session::get('donation_discount', 0);
        $couponCode = Session::get('donation_coupon_code');
        $couponId = Session::get('donation_coupon_id');

        $coupon = null;

        if ($couponCode) {
            $coupon = \App\Models\Coupon::find($couponId);

            if ($coupon && $coupon->isValidFor(Auth::user(), $campaign, $originalAmount)[0]) {
                $discountAmount = $coupon->computeDiscount($originalAmount);
                $amount = round($originalAmount - $discountAmount, 2);
            } else {
                $amount = $originalAmount;
                $discountAmount = 0;
                $couponCode = null;
                $couponId = null;
                $coupon = null;
            }
        }

        $fees = $this->calculateFees($amount);

        try {
            $order = app(\App\Gateways\RazorpayGateway::class)->createOrder(
                $campaign,
                Auth::user(),
                $amount,
                $fees
            );
        } catch (\Throwable $e) {
            Log::channel('payments')->error('Razorpay order creation failed', [
                'campaign_id' => $campaign->id,
                'amount' => $amount,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            $this->clearDonationSession();

            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug ?? '',
                    'slug' => $campaign->slug,
                ])
                ->with('error', 'Unable to initialize payment. Please try again.');
        }

        $cart = Session::get('donation_cart', []);
        $isProduct = ($cart['type'] ?? '') === 'products' && ! empty($cart['ids']);
        $donationType = $isProduct ? 'product' : 'money';

        $donation = new Donation();
        $donation->campaign_id = $campaign->id;
        $donation->user_id = Auth::id();
        $donation->donor_name = Auth::user()?->name ?? 'Guest Donor';
        $donation->donor_email = Auth::user()?->email;
        $donation->donation_type = $donationType;
        $donation->total_amount = $amount;
        $donation->original_amount = $originalAmount;
        $donation->discount_amount = $discountAmount;
        $donation->coupon_id = $couponId;
        $donation->coupon_code = $couponCode;
        $donation->platform_fee = $fees['platform_fee'];
        $donation->net_amount = $fees['net_amount'];
        $donation->order_id = $order['id'];
        $donation->payment_gateway = 'razorpay';
        $donation->currency = $this->getCurrency();
        $donation->payment_status = 'pending';

        $this->saveDonationWithUniqueReceipt($donation);

        if ($isProduct) {
            $ids = array_values(array_filter(explode(',', $cart['ids'])));
            $qtys = array_values(array_filter(explode(',', $cart['qtys'])));

            foreach ($ids as $i => $productId) {
                $productId = (int) trim($productId);
                $qty = (int) trim($qtys[$i] ?? 1);

                $product = \App\Models\CampaignProduct::find($productId);

                if (! $product) {
                    Log::channel('payments')->warning('Product not found during donation_items creation', [
                        'product_id' => $productId,
                        'donation_id' => $donation->id,
                    ]);

                    continue;
                }

                DonationItem::create([
                    'donation_id' => $donation->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => $product->price,
                ]);
            }

            Log::channel('payments')->info('Product donation items saved', [
                'donation_id' => $donation->id,
                'product_ids' => $cart['ids'],
                'qtys' => $cart['qtys'],
            ]);

            if ($ids = Session::get('donation_reservation_ids', [])) {
                ProductReservation::whereIn('id', $ids)
                    ->whereNull('donation_id')
                    ->update(['donation_id' => $donation->id]);
            }
        }

        $this->clearDonationSession();

        Log::channel('payments')->info('Payment order created', [
            'donation_id' => $donation->id,
            'order_id' => $order['id'],
            'campaign_id' => $campaign->id,
            'donation_type' => $donationType,
            'total_amount' => $amount,
            'platform_fee' => $fees['platform_fee'],
            'net_amount' => $fees['net_amount'],
        ]);

        $campaign->load('category');

        if ($donation->payment_status === 'completed') {
            return redirect()
                ->route('campaign.public', [
                    'category' => $campaign->category->slug,
                    'slug' => $campaign->slug,
                ])
                ->with('success', 'Your donation has already been completed. Thank you!');
        }

        return view('payment.index', [
            'campaign' => $campaign,
            'donation' => $donation,
            'amount' => $amount,
            'platform_fee' => $fees['platform_fee'],
            'net_amount' => $fees['net_amount'],
            'order_id' => $order['id'],
            'donation_id' => $donation->id,
            'razorpay_key' => config('services.razorpay.key'),
        ]);
    }

    private function calculateFees(float $amount): array
    {
        $platformFee = round($amount * $this->getPlatformFeePercent() / 100, 2);
        $netAmount = round($amount - $platformFee, 2);

        return [
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
        ];
    }

    private function generateUniqueReceiptNumber(): string
    {
        return strtoupper(\Illuminate\Support\Str::random(12));
    }

    private function saveDonationWithUniqueReceipt(Donation $donation): Donation
    {
        $maxAttempts = 3;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $donation->receipt_number = $this->generateUniqueReceiptNumber();

            try {
                $donation->save();

                return $donation->fresh();
            } catch (\Illuminate\Database\QueryException $e) {
                if ($attempt === $maxAttempts) {
                    throw $e;
                }
            }
        }

        throw $donation;
    }

    private function resolveState(Campaign $campaign): string
    {
        $state = $campaign->campaign_state;

        if (
            $state === 'active' &&
            $campaign->end_date &&
            \Carbon\Carbon::parse($campaign->end_date)->endOfDay()->isPast()
        ) {
            return 'expired';
        }

        return $state;
    }

    private function clearDonationSession(): void
    {
        Session::forget([
            'donation_amount',
            'donation_original_amount',
            'donation_discount',
            'donation_coupon_code',
            'donation_coupon_id',
            'donation_campaign',
            'donation_session_at',
            'donation_cart',
        ]);
    }
}
