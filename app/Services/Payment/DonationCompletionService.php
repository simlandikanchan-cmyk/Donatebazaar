<?php

namespace App\Services\Payment;

use App\Mail\DonationReceiptMail;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\ProductReservation;
use App\Services\ProductReservationService;
use App\Services\WalletService;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DonationCompletionService
{
    public function __construct(
        private ProductReservationService $productReservationService,
        private WalletService $walletService
    ) {}

    public function complete(Donation $donation, ?string $paymentId = null, ?string $signature = null): array
    {
        $donationToMail = null;
        $ownerForNotif = null;

        DB::transaction(function () use ($donation, $paymentId, $signature, &$donationToMail, &$ownerForNotif) {
            $lockedDonation = Donation::lockForUpdate()->findOrFail($donation->id);

            if ($lockedDonation->payment_status === 'completed') {
                return;
            }

            if ($paymentId) {
                $lockedDonation->payment_id = $paymentId;
            }

            if ($signature) {
                $lockedDonation->signature = $signature;
            }

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

            Log::channel('donations')->info('Donation completion side effects applied', [
                'donation_id' => $lockedDonation->id,
                'payment_id' => $lockedDonation->payment_id,
                'platform_fee' => $lockedDonation->platform_fee,
                'net_amount' => $lockedDonation->net_amount,
            ]);

            $donationToMail = $lockedDonation->fresh();
            $ownerForNotif = $lockedDonation->campaign->user;
        });

        if ($donationToMail) {
            $this->sendReceiptEmail($donationToMail);
        }

        return [
            'donation' => $donationToMail,
            'owner' => $ownerForNotif,
        ];
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

        Log::channel('donations')->info('Product stock decremented after payment', [
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

        Log::channel('donations')->info('Product reservations consumed after payment', [
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

        Log::channel('donations')->info('Coupon redeemed', [
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
                ->queue(new DonationReceiptMail($donation));

            Log::channel('donations')->info('Donation receipt email queued', [
                'donation_id' => $donation->id,
                'email' => $donation->donor_email,
            ]);
        } catch (\Throwable $e) {
            Log::channel('donations')->error('Donation receipt email queue failed', [
                'donation_id' => $donation->id,
                'email' => $donation->donor_email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
