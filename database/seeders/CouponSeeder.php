<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CouponSeeder extends Seeder
{
    /**
     * Seed sample coupons for manual testing.
     *
     *  - WELCOME500 : user-specific, fixed ₹500 off, min donation ₹1000, single use
     *  - SAVE10      : public promo, 10% off, capped at ₹200, unlimited uses
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@donatebazaar.com'],
            [
                'name'     => 'Demo Coupon User',
                'password' => Hash::make('password'),
                'phone'    => '9999999999',
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'WELCOME500'],
            [
                'user_id'       => $user->id,
                'campaign_id'   => null,
                'discount_type' => 'fixed',
                'discount_value' => 500,
                'min_amount'    => 1000,
                'max_discount'  => null,
                'usage_limit'   => 1,
                'used_count'    => 0,
                'expires_at'    => null,
                'is_active'     => true,
                'redeemed_at'   => null,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'SAVE10'],
            [
                'user_id'       => null,
                'campaign_id'   => null,
                'discount_type' => 'percent',
                'discount_value' => 10,
                'min_amount'    => null,
                'max_discount'  => 200,
                'usage_limit'   => null,
                'used_count'    => 0,
                'expires_at'    => null,
                'is_active'     => true,
                'redeemed_at'   => null,
            ]
        );
    }
}
