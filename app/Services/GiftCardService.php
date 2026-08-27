<?php

namespace App\Services;

use App\Gateways\RazorpayGateway;
use App\Models\GiftCard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GiftCardService
{
    public function __construct(
        private readonly RazorpayGateway $gateway
    ) {}

    public function createGiftCardOrder(array $data): array
    {
        $order = $this->gateway->createOrderWithNotes(
            (float) $data['amount'],
            ['type' => 'gift_card'],
            'gc_'.time().'_'.rand(100, 999)
        );

        $giftCard = GiftCard::create([
            'code' => GiftCard::generateCode(),
            'amount' => $data['amount'],
            'theme' => $data['theme'],
            'sender_name' => $data['sender_name'],
            'sender_email' => $data['sender_email'],
            'recipient_name' => $data['recipient_name'],
            'recipient_email' => $data['recipient_email'],
            'message' => $data['message'] ?? null,
            'send_at' => $data['send_at'],
            'order_id' => $order['id'],
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        return [
            'order_id' => $order['id'],
            'gift_card_id' => $giftCard->id,
            'amount' => (int) $data['amount'] * 100,
        ];
    }

    public function verifyGiftCardPayment(string $orderId, string $paymentId, string $signature, int $giftCardId): GiftCard
    {
        $this->gateway->verifyPaymentSignature([
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]);

        $giftCard = GiftCard::findOrFail($giftCardId);

        if ($giftCard->payment_status === 'completed') {
            return $giftCard;
        }

        $giftCard->update([
            'payment_id' => $paymentId,
            'payment_status' => 'completed',
            'status' => 'sent',
            'payment_verified_at' => now(),
        ]);

        try {
            Mail::send('emails.gift-card', ['giftCard' => $giftCard], function ($m) use ($giftCard) {
                $m->to($giftCard->recipient_email, $giftCard->recipient_name)
                    ->subject("You've received a DonateBazaar Gift Card from {$giftCard->sender_name}!");
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send gift card email', [
                'gift_card_id' => $giftCard->id,
                'code' => $giftCard->code,
                'message' => $e->getMessage(),
            ]);
        }

        return $giftCard;
    }
}
