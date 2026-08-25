<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class DonationReceiptService
{
    private function getReceiptUrlTtlHours(): int
    {
        return (int) config('services.donation.receipt_url_ttl_hours', 24);
    }

    private const PAYMENT_METHOD_LABELS = [
        'upi' => 'UPI',
        'card' => 'Card',
        'netbanking' => 'Net Banking',
        'wallet' => 'Wallet',
        'emi' => 'EMI',
        'paylater' => 'Pay Later',
        'banktransfer' => 'Bank Transfer',
    ];

    /**
     * Builds the single, authoritative set of receipt values shared by the
     * receipt email and the downloadable PDF. All financial values come
     * directly from the stored donation record — nothing is recalculated.
     */
    public function data(Donation $donation, bool $withUrls = true): array
    {
        $donation->loadMissing('campaign.category');

        $campaign = $donation->campaign;

        return [
            'donation' => $donation,
            'campaign' => $campaign,
            'donorName' => $donation->donor_name ?? 'Donor',
            'amount' => $donation->total_amount,
            'originalAmount' => $donation->original_amount ?? $donation->total_amount,
            'discountAmount' => $donation->discount_amount ?? 0,
            'couponCode' => $donation->coupon_code,
            'platformFee' => $donation->platform_fee,
            'netAmount' => $donation->net_amount,
            'receiptNo' => $donation->receipt_number,
            'paidAt' => $donation->paid_at,
            'paymentMethod' => $this->paymentMethodLabel($donation),
            'paymentReference' => $donation->payment_id,
            'campaignUrl' => $withUrls ? $this->campaignUrl($campaign) : null,
            'receiptDownloadUrl' => $withUrls ? $this->receiptDownloadUrl($donation) : null,
        ];
    }

    public function receiptDownloadUrl(Donation $donation): string
    {
        return URL::temporarySignedRoute(
            'donations.receipt.download',
            now()->addHours($this->getReceiptUrlTtlHours()),
            ['donation' => $donation->id]
        );
    }

    public function receiptFileName(Donation $donation): string
    {
        $receiptNo = preg_replace('/[^A-Za-z0-9\-_]/', '', (string) $donation->receipt_number);

        return 'DONATEBAZAAR-Receipt-'.($receiptNo !== '' ? $receiptNo : $donation->id).'.pdf';
    }

    public function isReceiptAvailable(Donation $donation): bool
    {
        return $donation->payment_status === PaymentStatus::Completed->value
            && ! $donation->is_refunded
            && $donation->deleted_at === null;
    }

    public function isAuthorized(Donation $donation, ?User $user = null): bool
    {
        if (! $user) {
            $user = Auth::user();
        }

        if (! $user) {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        return $donation->user_id === $user->id;
    }

    private function paymentMethodLabel(Donation $donation): string
    {
        $gateway = ucfirst((string) ($donation->payment_gateway ?? 'Razorpay'));

        $method = $donation->payment_method;

        if (empty($method)) {
            return $gateway;
        }

        $label = self::PAYMENT_METHOD_LABELS[strtolower($method)] ?? ucfirst($method);

        return $label.' · '.$gateway;
    }

    private function campaignUrl(?Campaign $campaign): ?string
    {
        if (! $campaign) {
            return null;
        }

        if ($campaign->category) {
            return route('campaign.public', [
                'category' => $campaign->category->slug,
                'slug' => $campaign->slug,
            ]);
        }

        return null;
    }
}
