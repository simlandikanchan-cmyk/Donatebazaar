<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\GiftCard;
use App\Services\GiftCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GiftCardController extends Controller
{
    public function __construct(private readonly GiftCardService $giftCardService) {}

    public function index()
    {
        return view('gift-cards.index');
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100|max:500000',
            'theme' => 'required|in:purple,teal,coral,blue',
            'sender_name' => 'required|string|max:100',
            'sender_email' => 'required|email',
            'recipient_name' => 'required|string|max:100',
            'recipient_email' => 'required|email',
            'message' => 'nullable|string|max:500',
            'send_at' => 'required|date|after_or_equal:today',
        ]);

        return response()->json($this->giftCardService->createGiftCardOrder($request->all()));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'gift_card_id' => 'required|integer|exists:gift_cards,id',
        ]);

        try {
            $giftCard = $this->giftCardService->verifyGiftCardPayment(
                $request->razorpay_order_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature,
                $request->gift_card_id
            );
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
        }

        return response()->json([
            'success' => true,
            'code' => $giftCard->code,
            'message' => 'Gift card sent successfully!',
        ]);
    }

    public function redeemPage()
    {
        $campaigns = Campaign::where('campaign_state', 'active')
            ->latest()
            ->take(12)
            ->get(['id', 'title', 'slug', 'cover_image', 'goal_amount', 'raised_amount']);

        return view('gift-cards.redeem', compact('campaigns'));
    }

    public function validateCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $giftCard = GiftCard::where('code', strtoupper($request->code))
            ->where('payment_status', 'completed')
            ->first();

        if (! $giftCard) {
            return response()->json(['valid' => false, 'message' => 'Invalid gift card code.']);
        }

        if ($giftCard->isRedeemed()) {
            return response()->json(['valid' => false, 'message' => 'This gift card has already been redeemed.']);
        }

        if ($giftCard->isExpired()) {
            return response()->json(['valid' => false, 'message' => 'This gift card has expired.']);
        }

        return response()->json([
            'valid' => true,
            'amount' => $giftCard->amount,
            'code' => $giftCard->code,
            'recipient_email_masked' => $this->maskEmail($giftCard->recipient_email),
        ]);
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        $maskedLocal = substr($local, 0, 1).str_repeat('*', max(strlen($local) - 1, 1));
        $domainParts = explode('.', $domain);
        $maskedDomain = substr($domainParts[0], 0, 1).str_repeat('*', max(strlen($domainParts[0]) - 1, 1));
        $tld = implode('.', array_slice($domainParts, 1));

        return "{$maskedLocal}@{$maskedDomain}.{$tld}";
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'campaign_id' => 'required|integer|exists:campaigns,id',
            'donor_name' => 'required|string|max:100',
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

            if (strtolower(trim($giftCard->recipient_email)) !== strtolower(trim($request->donor_email))) {
                return back()
                    ->withInput()
                    ->with('error', 'This gift card was sent to a different email address. Please use the email it was sent to.');
            }

            $giftCard->update([
                'status' => 'redeemed',
                'redeemed_by' => Auth::id(),
                'redeemed_on_campaign' => $request->campaign_id,
                'redeemed_at' => now(),
            ]);

            $donation = new Donation;
            $donation->campaign_id = $request->campaign_id;
            $donation->user_id = Auth::id();
            $donation->donor_name = $request->donor_name;
            $donation->donor_email = $request->donor_email;
            $donation->donation_type = 'money';
            $donation->total_amount = $giftCard->amount;
            $donation->payment_gateway = 'gift_card';
            $donation->payment_status = 'completed';
            $donation->payment_id = $giftCard->code;
            $donation->order_id = $giftCard->order_id;
            $donation->currency = 'INR';
            $donation->receipt_number = strtoupper(Str::random(12));
            $donation->paid_at = now();
            $donation->save();

            return redirect()->route('gift-cards.redeem.success', ['code' => $giftCard->code]);
        });
    }

    public function redeemSuccess(string $code)
    {
        $giftCard = GiftCard::where('code', $code)->firstOrFail();

        return view('gift-cards.success', compact('giftCard'));
    }
}
