<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class GiftCardController extends Controller
{
    // ── Public purchase page ──────────────────────────────────────────────────

    public function index()
    {
        return view('gift-cards.index');
    }

    // ── Create Razorpay order ─────────────────────────────────────────────────

    public function createOrder(Request $request)
    {
        $request->validate([
            'amount'          => 'required|integer|min:100|max:500000',
            'theme'           => 'required|in:purple,teal,coral,blue',
            'sender_name'     => 'required|string|max:100',
            'sender_email'    => 'required|email',
            'recipient_name'  => 'required|string|max:100',
            'recipient_email' => 'required|email',
            'message'         => 'nullable|string|max:500',
            'send_at'         => 'required|date|after_or_equal:today',
        ]);

        $key    = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        $api    = new Api($key, $secret);

        $order = $api->order->create([
            'receipt'  => 'gc_' . time() . '_' . rand(100, 999),
            'amount'   => (int) $request->amount * 100,
            'currency' => 'INR',
            'notes'    => ['type' => 'gift_card'],
        ]);

        // Store pending gift card
        $giftCard = GiftCard::create([
            'code'            => GiftCard::generateCode(),
            'amount'          => $request->amount,
            'theme'           => $request->theme,
            'sender_name'     => $request->sender_name,
            'sender_email'    => $request->sender_email,
            'recipient_name'  => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'message'         => $request->message,
            'send_at'         => $request->send_at,
            'order_id'        => $order['id'],
            'status'          => 'pending',
            'payment_status'  => 'pending',
        ]);

        return response()->json([
            'order_id'       => $order['id'],
            'gift_card_id'   => $giftCard->id,
            'razorpay_key'   => $key,
            'amount'         => (int) $request->amount * 100,
        ]);
    }

    // ── Verify payment + send email ───────────────────────────────────────────

    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'gift_card_id'        => 'required|integer|exists:gift_cards,id',
        ]);

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
        }

        $giftCard = GiftCard::findOrFail($request->gift_card_id);

        // Prevent duplicate processing (and duplicate emails) if verify is called more than once
        if ($giftCard->payment_status === 'completed') {
            return response()->json([
                'success' => true,
                'code'    => $giftCard->code,
                'message' => 'Already processed.',
            ]);
        }

        $giftCard->update([
            'payment_id'     => $request->razorpay_payment_id,
            'payment_status' => 'completed',
            'status'         => 'sent',
        ]);

        // Send email to recipient
        \Mail::send('emails.gift-card', ['giftCard' => $giftCard], function ($m) use ($giftCard) {
            $m->to($giftCard->recipient_email, $giftCard->recipient_name)
              ->subject("You've received a DonateBazaar Gift Card from {$giftCard->sender_name}!");
        });

        return response()->json([
            'success' => true,
            'code'    => $giftCard->code,
            'message' => 'Gift card sent successfully!',
        ]);
    }

    // ── Redeem page ───────────────────────────────────────────────────────────

    public function redeemPage()
    {
        $campaigns = Campaign::where('campaign_state', 'active')
            ->latest()
            ->take(12)
            ->get(['id', 'title', 'slug', 'cover_image', 'goal_amount', 'raised_amount']);

        return view('gift-cards.redeem', compact('campaigns'));
    }

    // ── Validate code (AJAX) ──────────────────────────────────────────────────

    public function validateCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $giftCard = GiftCard::where('code', strtoupper($request->code))
            ->where('payment_status', 'completed')
            ->first();

        if (!$giftCard) {
            return response()->json(['valid' => false, 'message' => 'Invalid gift card code.']);
        }

        if ($giftCard->isRedeemed()) {
            return response()->json(['valid' => false, 'message' => 'This gift card has already been redeemed.']);
        }

        if ($giftCard->isExpired()) {
            return response()->json(['valid' => false, 'message' => 'This gift card has expired.']);
        }

        return response()->json([
            'valid'           => true,
            'amount'          => $giftCard->amount,
            'code'            => $giftCard->code,
            'recipient_email' => $giftCard->recipient_email, // NEW — for frontend hint/autofill
        ]);
    }

    // ── Process redemption ────────────────────────────────────────────────────

    public function redeem(Request $request)
    {
        $request->validate([
            'code'        => 'required|string',
            'campaign_id' => 'required|integer|exists:campaigns,id',
            'donor_name'  => 'required|string|max:100',
            'donor_email' => 'required|email',
        ]);

        return \DB::transaction(function () use ($request) {

            $giftCard = GiftCard::where('code', strtoupper($request->code))
                ->where('payment_status', 'completed')
                ->where('status', '!=', 'redeemed')
                ->lockForUpdate()
                ->firstOrFail();

            if ($giftCard->isExpired()) {
                return back()->with('error', 'This gift card has expired.');
            }

            // 🔒 NEW: Only the intended recipient can redeem this gift card
            if (strtolower(trim($giftCard->recipient_email)) !== strtolower(trim($request->donor_email))) {
                return back()
                    ->withInput()
                    ->with('error', 'This gift card was sent to a different email address. Please use the email it was sent to.');
            }

            // Mark redeemed
            $giftCard->update([
                'status'               => 'redeemed',
                'redeemed_by'          => Auth::id(),
                'redeemed_on_campaign' => $request->campaign_id,
                'redeemed_at'          => now(),
            ]);

            // Create donation record
            Donation::create([
                'campaign_id'     => $request->campaign_id,
                'user_id'         => Auth::id(),
                'donor_name'      => $request->donor_name,
                'donor_email'     => $request->donor_email,
                'donation_type'   => 'money',
                'total_amount'    => $giftCard->amount,
                'payment_gateway' => 'gift_card',
                'payment_status'  => 'completed',
                'payment_id'      => $giftCard->code,
                'order_id'        => $giftCard->order_id,
                'currency'        => 'INR',
                'receipt_number'  => strtoupper(\Illuminate\Support\Str::random(12)),
                'paid_at'         => now(),
            ]);

            // Update campaign raised amount
            \App\Models\Campaign::find($request->campaign_id)
                ->increment('raised_amount', $giftCard->amount);

            return redirect()->route('gift-cards.redeem.success', ['code' => $giftCard->code]);
        });
    }

    // ── Success page ──────────────────────────────────────────────────────────

    public function redeemSuccess(string $code)
    {
        $giftCard = GiftCard::where('code', $code)->firstOrFail();
        return view('gift-cards.success', compact('giftCard'));
    }
}