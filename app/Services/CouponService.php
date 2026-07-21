<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\User;

class CouponService
{
    /**
     * Validate a coupon code for a given user / campaign / amount.
     *
     * The discount is ALWAYS recomputed here from the coupon record — never
     * trust a client-supplied discount value.
     *
     * @return array{
     *     valid: bool,
     *     discount_amount: float,
     *     discounted_total: float,
     *     message: string,
     *     coupon: Coupon|null
     * }
     */
    public function validate(string $code, ?User $user, ?Campaign $campaign, float $amount): array
    {
        $coupon = Coupon::where('code', trim($code))->first();

        if (! $coupon) {
            return $this->invalid($amount, 'Invalid coupon code.');
        }

        [$valid, $message] = $coupon->isValidFor($user, $campaign, $amount);

        if (! $valid) {
            return $this->invalid($amount, $message, $coupon);
        }

        $discount = $coupon->computeDiscount($amount);
        $discountedTotal = round($amount - $discount, 2);

        return [
            'valid' => true,
            'discount_amount' => $discount,
            'discounted_total' => $discountedTotal,
            'message' => $message,
            'coupon' => $coupon,
        ];
    }

    private function invalid(float $amount, string $message, ?Coupon $coupon = null): array
    {
        return [
            'valid' => false,
            'discount_amount' => 0.0,
            'discounted_total' => $amount,
            'message' => $message,
            'coupon' => $coupon,
        ];
    }
}
