<?php

namespace App\Http\Controllers;

use App\Mail\DonationReceiptMail;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Donation;
use App\Models\DonationItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    private const MIN_AMOUNT        = 1;
    private const MAX_AMOUNT        = 500000;
    private const LOCK_TTL          = 60;
    private const RATE_LIMIT_HITS   = 10;
    private const RATE_LIMIT_WINDOW = 60;

    /*
    |--------------------------------------------------------------------------
    | Platform Fee — Ketto style
    | 5% is deducted from every donation.
    | campaign receives net_amount; platform keeps platform_fee.
    |--------------------------------------------------------------------------
    */

    private const PLATFORM_FEE_PERCENT = 5.0;

    private function calculateFees(float $amount): array
    {
        $platformFee = round($amount * self::PLATFORM_FEE_PERCENT / 100, 2);
        $netAmount   = round($amount - $platformFee, 2);

        return [
            'platform_fee' => $platformFee,
            'net_amount'   => $netAmount,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Razorpay Instance
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | Helper — always redirect to the campaign's own public page
    |--------------------------------------------------------------------------
    */

    private function backToCampaign(Campaign $campaign, string $error): RedirectResponse
    {
        if (! $campaign->relationLoaded('category')) {
            $campaign->load('category');
        }

        return redirect()
            ->route('campaign.public', [
                'category' => $campaign->category->slug,
                'slug'     => $campaign->slug,
            ])
            ->with('error', $error);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper — redirect to campaign page with success message
    |--------------------------------------------------------------------------
    */

    private function successToCampaign(Campaign $campaign, string $message): RedirectResponse
    {
        if (! $campaign->relationLoaded('category')) {
            $campaign->load('category');
        }

        return redirect()
            ->route('campaign.public', [
                'category' => $campaign->category->slug,
                'slug'     => $campaign->slug,
            ])
            ->with('success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper — resolve real campaign state including real-time expiry
    |--------------------------------------------------------------------------
    */

    private function resolveState(Campaign $campaign): string
    {
        $state = $campaign->campaign_state;

        if (
            $state === 'active' &&
            $campaign->end_date &&
            Carbon::parse($campaign->end_date)->isPast()
        ) {
            return 'expired';
        }

        return $state;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper — clear donation session keys
    |--------------------------------------------------------------------------
    */

    private function clearDonationSession(): void
    {
        session()->forget([
            'donation_amount',
            'donation_campaign',
            'donation_session_at',
            'donation_cart',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper — send receipt email safely (never crash on mail failure)
    |--------------------------------------------------------------------------
    */

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
                'email'       => $donation->donor_email,
            ]);
        } catch (\Throwable $e) {
            // Never let email failure break the payment flow
            Log::error('Donation receipt email failed', [
                'donation_id' => $donation->id,
                'email'       => $donation->donor_email,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helper — build campaign public URL
    |--------------------------------------------------------------------------
    */

    private function campaignUrl(Campaign $campaign): string
    {
        if (! $campaign->relationLoaded('category')) {
            $campaign->load('category');
        }

        return route('campaign.public', [
            'category' => $campaign->category->slug,
            'slug'     => $campaign->slug,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 1 → Redirect To Payment
    |--------------------------------------------------------------------------
    */

    public function redirectToPayment(
        Request $request,
        Campaign $campaign
    ): RedirectResponse {

        /*
        |----------------------------------------------------------------------
        | Rate Limit
        |----------------------------------------------------------------------
        */

        $rateLimitKey = 'donate_redirect_' . (Auth::id() ?? $request->ip());

        if (RateLimiter::tooManyAttempts($rateLimitKey, self::RATE_LIMIT_HITS)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return $this->backToCampaign(
                $campaign,
                "Too many attempts. Please wait {$seconds} seconds."
            );
        }

        RateLimiter::hit($rateLimitKey, self::RATE_LIMIT_WINDOW);

        /*
        |----------------------------------------------------------------------
        | Validate Amount
        |----------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [
            'amount' => [
                'required',
                'numeric',
                'min:' . self::MIN_AMOUNT,
                'max:' . self::MAX_AMOUNT,
            ],
        ], [
            'amount.required' => 'Please enter a donation amount.',
            'amount.numeric'  => 'Amount must be a valid number.',
            'amount.min'      => 'Minimum donation is ₹' . self::MIN_AMOUNT . '.',
            'amount.max'      => 'Maximum donation is ₹' . number_format(self::MAX_AMOUNT) . '.',
        ]);

        if ($validator->fails()) {
            return $this->backToCampaign(
                $campaign,
                $validator->errors()->first('amount')
            );
        }

        $amount = (float) $request->amount;

        /*
        |----------------------------------------------------------------------
        | Campaign State Check
        |----------------------------------------------------------------------
        */

        $state = $this->resolveState($campaign);

        if ($state !== 'active') {
            $messages = [
                'expired'   => 'This campaign has ended and is no longer accepting donations.',
                'paused'    => 'This campaign is currently paused.',
                'rejected'  => 'This campaign is not available for donations.',
                'completed' => 'This campaign has already reached its goal.',
                'inactive'  => 'This campaign is not currently active.',
                'pending'   => 'This campaign is pending approval.',
            ];

            return $this->backToCampaign(
                $campaign,
                $messages[$state] ?? 'This campaign is not accepting donations right now.'
            );
        }

        /*
        |----------------------------------------------------------------------
        | Store Donation Session
        | — also saves cart data when the user donates products
        |----------------------------------------------------------------------
        */

        session([
            'donation_amount'     => $amount,
            'donation_campaign'   => (string) $campaign->id,
            'donation_session_at' => now()->timestamp,
            'donation_cart'       => [
                'ids'  => $request->input('product_ids', ''),
                'qtys' => $request->input('product_qtys', ''),
                'type' => $request->input('donation_type', 'money'),
            ],
        ]);

        Log::info('Donation session created', [
            'campaign_id'   => $campaign->id,
            'user_id'       => Auth::id(),
            'amount'        => $amount,
            'donation_type' => $request->input('donation_type', 'money'),
            'product_ids'   => $request->input('product_ids', ''),
        ]);

        return redirect()->route('payment.page', $campaign->id);
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2 → Payment Page
    |--------------------------------------------------------------------------
    */

    public function paymentPage(
        Campaign $campaign
    ): View|RedirectResponse {

        $amount     = session('donation_amount');
        $campaignId = session('donation_campaign');
        $sessionAt  = session('donation_session_at');

        Log::debug('Payment page session check', [
            'amount'           => $amount,
            'session_campaign' => $campaignId,
            'route_campaign'   => $campaign->id,
            'user_id'          => Auth::id(),
        ]);

        /*
        |----------------------------------------------------------------------
        | Session Validation
        |----------------------------------------------------------------------
        */

        if (
            empty($amount) ||
            empty($campaignId) ||
            (string) $campaignId !== (string) $campaign->id
        ) {
            $this->clearDonationSession();
            return $this->backToCampaign($campaign, 'Invalid donation session. Please try again.');
        }

        /*
        |----------------------------------------------------------------------
        | Session Expiry (15 minutes)
        |----------------------------------------------------------------------
        */

        if (
            empty($sessionAt) ||
            (now()->timestamp - (int) $sessionAt) > 900
        ) {
            $this->clearDonationSession();
            return $this->backToCampaign($campaign, 'Your session expired. Please try again.');
        }

        /*
        |----------------------------------------------------------------------
        | Campaign State Check
        |----------------------------------------------------------------------
        */

        $state = $this->resolveState($campaign);

        if ($state !== 'active') {
            $this->clearDonationSession();
            return $this->backToCampaign($campaign, 'This campaign is no longer active.');
        }

        /*
        |----------------------------------------------------------------------
        | Amount Sanity Check
        |----------------------------------------------------------------------
        */

        $amount = (float) $amount;

        if ($amount < self::MIN_AMOUNT || $amount > self::MAX_AMOUNT) {
            $this->clearDonationSession();
            return $this->backToCampaign($campaign, 'Invalid donation amount.');
        }

        /*
        |----------------------------------------------------------------------
        | Calculate Platform Fee
        |----------------------------------------------------------------------
        */

        $fees = $this->calculateFees($amount);

        /*
        |----------------------------------------------------------------------
        | Create Razorpay Order
        | NOTE: Razorpay charges the donor the full amount.
        | We internally split it into platform_fee + net_amount.
        |----------------------------------------------------------------------
        */

        try {

            $api = $this->getRazorpayApi();

            $order = $api->order->create([
                'receipt'  => 'rcpt_' . time() . '_' . Auth::id(),
                'amount'   => (int) round($amount * 100), // full amount in paise
                'currency' => 'INR',
                'notes'    => [
                    'campaign_id'   => $campaign->id,
                    'campaign_name' => $campaign->title,
                    'user_id'       => Auth::id(),
                    'platform_fee'  => $fees['platform_fee'],
                    'net_amount'    => $fees['net_amount'],
                ],
            ]);

        } catch (\Throwable $e) {

            Log::error('Razorpay order creation failed', [
                'campaign_id' => $campaign->id,
                'amount'      => $amount,
                'user_id'     => Auth::id(),
                'error'       => $e->getMessage(),
            ]);

            $this->clearDonationSession();
            return $this->backToCampaign($campaign, 'Unable to initialize payment. Please try again.');
        }

        /*
        |----------------------------------------------------------------------
        | Detect donation type from session cart
        |----------------------------------------------------------------------
        */

        $cart         = session('donation_cart', []);
        $isProduct    = ($cart['type'] ?? '') === 'products' && !empty($cart['ids']);
        $donationType = $isProduct ? 'product' : 'money';

        /*
        |----------------------------------------------------------------------
        | Create Pending Donation (with fee breakdown)
        |----------------------------------------------------------------------
        */

        $donation = Donation::create([
            'campaign_id'     => $campaign->id,
            'user_id'         => Auth::id(),
            'donor_name'      => Auth::user()?->name ?? 'Guest Donor',
            'donor_email'     => Auth::user()?->email,
            'donation_type'   => $donationType,
            'total_amount'    => $amount,
            'platform_fee'    => $fees['platform_fee'],
            'net_amount'      => $fees['net_amount'],
            'order_id'        => $order['id'],
            'payment_gateway' => 'razorpay',
            'payment_status'  => 'pending',
            'currency'        => 'INR',
            'receipt_number'  => strtoupper(Str::random(12)),
        ]);

        /*
        |----------------------------------------------------------------------
        | Save donation_items rows for product donations
        | — also decrements remaining_quantity on each campaign_product
        |----------------------------------------------------------------------
        */

        if ($isProduct) {
            $ids  = array_values(array_filter(explode(',', $cart['ids'])));
            $qtys = array_values(array_filter(explode(',', $cart['qtys'])));

            foreach ($ids as $i => $productId) {
                $productId = (int) trim($productId);
                $qty       = (int) trim($qtys[$i] ?? 1);

                $product = CampaignProduct::find($productId);

                if (! $product) {
                    Log::warning('Product not found during donation_items creation', [
                        'product_id'  => $productId,
                        'donation_id' => $donation->id,
                    ]);
                    continue;
                }

                DonationItem::create([
                    'donation_id' => $donation->id,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'price'       => $product->price,
                ]);

                // Decrement remaining stock safely (never go below 0)
                CampaignProduct::where('id', $productId)
                    ->where('remaining_quantity', '>=', $qty)
                    ->decrement('remaining_quantity', $qty);
            }

            Log::info('Product donation items saved', [
                'donation_id' => $donation->id,
                'product_ids' => $cart['ids'],
                'qtys'        => $cart['qtys'],
            ]);
        }

        /*
        |----------------------------------------------------------------------
        | Clear Session
        |----------------------------------------------------------------------
        */

        $this->clearDonationSession();

        Log::info('Payment order created', [
            'donation_id'   => $donation->id,
            'order_id'      => $order['id'],
            'campaign_id'   => $campaign->id,
            'donation_type' => $donationType,
            'total_amount'  => $amount,
            'platform_fee'  => $fees['platform_fee'],
            'net_amount'    => $fees['net_amount'],
        ]);

        $campaign->load('category');

        /*
        |----------------------------------------------------------------------
        | Completed-payment guard
        |
        | If someone navigates directly to /payment/{id} after already paying
        | (e.g. back button, refresh, shared URL) we redirect them away
        | BEFORE the view renders, so the Razorpay modal never re-opens.
        |
        | This MUST live here in the controller, not in a Blade @php block.
        | Calling redirect()->send() inside Blade causes a 500 because
        | headers are already being written when the template is rendering.
        |----------------------------------------------------------------------
        */

        if ($donation->payment_status === 'completed') {
            return $this->successToCampaign(
                $campaign,
                'Your donation has already been completed. Thank you!'
            );
        }

        return view('payment.index', [
            'campaign'     => $campaign,
            'donation'     => $donation,
            'amount'       => $amount,
            'platform_fee' => $fees['platform_fee'],
            'net_amount'   => $fees['net_amount'],
            'order_id'     => $order['id'],
            'donation_id'  => $donation->id,
            'razorpay_key' => config('services.razorpay.key'),
            'guest_phone'  => null,
            'donor_name'   => Auth::user()?->name  ?? 'Guest Donor',
            'donor_email'  => Auth::user()?->email ?? '',
            'donor_phone'  => Auth::user()?->phone ?? '',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3 → Verify Payment
    |--------------------------------------------------------------------------
    */

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'donation_id'         => 'required|integer|exists:donations,id',
        ]);

        /*
        |----------------------------------------------------------------------
        | Rate Limit
        |----------------------------------------------------------------------
        */

        $rateLimitKey = 'payment_verify_' . (Auth::id() ?? $request->ip());

        if (RateLimiter::tooManyAttempts($rateLimitKey, self::RATE_LIMIT_HITS)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many attempts. Please wait.',
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, self::RATE_LIMIT_WINDOW);

        /*
        |----------------------------------------------------------------------
        | Distributed Lock — prevent duplicate processing
        |----------------------------------------------------------------------
        */

        $lockKey = 'payment_lock_' . $request->razorpay_payment_id;
        $lock    = Cache::lock($lockKey, self::LOCK_TTL);

        if (! $lock->get()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment already processing.',
            ], 429);
        }

        try {

            /*
            |------------------------------------------------------------------
            | Verify Razorpay Signature
            |------------------------------------------------------------------
            */

            $api = $this->getRazorpayApi();

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            /*
            |------------------------------------------------------------------
            | Find Donation
            |------------------------------------------------------------------
            */

            $donation = Donation::where('id', $request->donation_id)
                ->where('order_id', $request->razorpay_order_id)
                ->firstOrFail();

            /*
            |------------------------------------------------------------------
            | Early Idempotency Check
            |------------------------------------------------------------------
            */

            if ($donation->payment_status === 'completed') {
                return response()->json([
                    'success'      => true,
                    'message'      => 'Payment already completed.',
                    'redirect_url' => $this->campaignUrl($donation->campaign),
                ]);
            }

            /*
            |------------------------------------------------------------------
            | DB Transaction with Row-Level Locks
            | NOTE: We do NOT manually increment raised_amount here.
            |       The DB trigger trg_raised_amount_on_donations handles it
            |       automatically when payment_status changes to 'completed'.
            |       Double-incrementing would corrupt the raised_amount.
            |------------------------------------------------------------------
            */

            DB::transaction(function () use ($donation, $request) {

                $lockedDonation = Donation::lockForUpdate()
                    ->findOrFail($donation->id);

                // Double-check inside transaction
                if ($lockedDonation->payment_status === 'completed') {
                    return;
                }

                $lockedDonation->update([
                    'payment_id'     => $request->razorpay_payment_id,
                    'signature'      => $request->razorpay_signature,
                    'payment_status' => 'completed',
                    'paid_at'        => now(),
                ]);

                // raised_amount is updated automatically by DB trigger.
                Campaign::lockForUpdate()
                    ->findOrFail($lockedDonation->campaign_id)
                    ->increment('platform_earnings', $lockedDonation->platform_fee);
            });

            // Reload fresh donation with campaign for email
            $donation->refresh();
            $donation->load('campaign.category');

            Log::info('Payment completed', [
                'donation_id'   => $donation->id,
                'campaign_id'   => $donation->campaign_id,
                'donation_type' => $donation->donation_type,
                'total_amount'  => $donation->total_amount,
                'platform_fee'  => $donation->platform_fee,
                'net_amount'    => $donation->net_amount,
                'payment_id'    => $request->razorpay_payment_id,
                'user_id'       => Auth::id(),
            ]);

            // Send receipt email — outside transaction, no DB lock held
            $this->sendReceiptEmail($donation);

            return response()->json([
                'success'      => true,
                'message'      => 'Payment successful. Thank you for your donation!',
                'redirect_url' => $this->campaignUrl($donation->campaign),
                'paid_at'      => $donation->paid_at?->toISOString(),
            ]);

        } catch (SignatureVerificationError $e) {

            Log::warning('Razorpay signature verification failed', [
                'order_id'   => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'user_id'    => Auth::id(),
                'ip'         => $request->ip(),
            ]);

            Donation::where('order_id', $request->razorpay_order_id)
                ->where('payment_status', 'pending')
                ->update(['payment_status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Any deducted amount will be refunded in 5–7 days.',
            ], 400);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Log::error('Donation not found during verification', [
                'donation_id' => $request->donation_id,
                'order_id'    => $request->razorpay_order_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Donation record not found. Please contact support.',
            ], 404);

        } catch (\Throwable $e) {

            Log::error('Payment verification exception', [
                'message'     => $e->getMessage(),
                'order_id'    => $request->razorpay_order_id ?? null,
                'payment_id'  => $request->razorpay_payment_id ?? null,
                'donation_id' => $request->donation_id ?? null,
                'user_id'     => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed. Please contact support.',
            ], 500);

        } finally {
            $lock->release();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Razorpay Webhook
    |--------------------------------------------------------------------------
    */

    public function webhook(Request $request): JsonResponse
    {
        $secret    = config('services.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');
        $payload   = $request->getContent();

        /*
        |----------------------------------------------------------------------
        | Webhook Secret Guard
        |
        | Separating the two checks intentionally:
        |
        | !$secret  → our misconfiguration — 500 so it shows up in alerts.
        |             Returning 200 here would silently swallow all webhooks
        |             and we would never know payments were being missed.
        |
        | !$signature → Razorpay sent a request with no signature header —
        |               reject with 400, log it, but don't 500 the server.
        |----------------------------------------------------------------------
        */

        if (! $secret) {
            Log::critical('RAZORPAY_WEBHOOK_SECRET is not set — all webhooks are being dropped');
            return response()->json(['status' => 'misconfigured'], 500);
        }

        if (! $signature) {
            Log::warning('Webhook received without X-Razorpay-Signature header', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['status' => 'invalid'], 400);
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        if (! hash_equals($expected, $signature)) {
            Log::warning('Webhook signature mismatch', ['ip' => $request->ip()]);
            return response()->json(['status' => 'invalid'], 400);
        }

        $data  = json_decode($payload, true);
        $event = $data['event'] ?? null;

        Log::info('Webhook received', ['event' => $event]);

        match ($event) {
            'payment.captured' => $this->handlePaymentCaptured($data),
            'payment.failed'   => $this->handlePaymentFailed($data),
            default            => null,
        };

        return response()->json(['status' => 'ok'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | Webhook Handlers
    |--------------------------------------------------------------------------
    */

    private function handlePaymentCaptured(array $payload): void
    {
        $paymentId = $payload['payload']['payment']['entity']['id']       ?? null;
        $orderId   = $payload['payload']['payment']['entity']['order_id'] ?? null;

        if (! $paymentId || ! $orderId) return;

        $lock = Cache::lock('webhook_lock_' . $paymentId, self::LOCK_TTL);

        if (! $lock->get()) return;

        /*
        |----------------------------------------------------------------------
        | $donationToMail is declared outside the transaction so we can
        | send the receipt email AFTER the transaction commits.
        |
        | Sending email inside the transaction is dangerous in production:
        | the DB row-lock is held for the entire duration of the mail call.
        | If the mail server is slow (common) the lock times out, the
        | transaction rolls back, and the payment_status is never updated —
        | even though Razorpay already captured the money.
        |----------------------------------------------------------------------
        */

        $donationToMail = null;

        try {
            DB::transaction(function () use ($paymentId, $orderId, &$donationToMail) {

                $donation = Donation::lockForUpdate()
                    ->where('order_id', $orderId)
                    ->first();

                if (! $donation || $donation->payment_status === 'completed') return;

                $donation->update([
                    'payment_id'     => $paymentId,
                    'payment_status' => 'completed',
                    'paid_at'        => now(),
                ]);

                // raised_amount handled by DB trigger.
                Campaign::lockForUpdate()
                    ->findOrFail($donation->campaign_id)
                    ->increment('platform_earnings', $donation->platform_fee);

                Log::info('Webhook: payment captured', [
                    'donation_id'   => $donation->id,
                    'donation_type' => $donation->donation_type,
                    'payment_id'    => $paymentId,
                    'platform_fee'  => $donation->platform_fee,
                    'net_amount'    => $donation->net_amount,
                ]);

                // Store for emailing after commit — NOT inside transaction
                $donationToMail = $donation->fresh();
            });

            // Transaction committed — now safe to send email.
            // DB lock is released; slow mail server cannot affect DB state.
            if ($donationToMail) {
                $this->sendReceiptEmail($donationToMail);
            }

        } finally {
            $lock->release();
        }
    }

    private function handlePaymentFailed(array $payload): void
    {
        $orderId = $payload['payload']['payment']['entity']['order_id'] ?? null;

        if (! $orderId) return;

        Donation::where('order_id', $orderId)
            ->where('payment_status', 'pending')
            ->update(['payment_status' => 'failed']);

        Log::info('Webhook: payment failed', ['order_id' => $orderId]);
    }
}

