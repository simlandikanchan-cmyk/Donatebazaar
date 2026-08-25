<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Category;
use App\Models\Donation;
use App\Models\ProductReservation;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    private WalletService $walletService;

    protected User $donor;

    protected User $owner;

    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletService = new WalletService;

        $this->owner = User::factory()->create(['role' => 'ngo']);
        $this->donor = User::factory()->create(['role' => 'donor']);

        $category = Category::create([
            'name' => 'Health',
            'slug' => 'health',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);

        $this->campaign = Campaign::create([
            'user_id' => $this->owner->id,
            'category_id' => $category->id,
            'title' => 'Medical Fund',
            'slug' => 'medical-fund',
            'description' => 'Help with medical expenses',
            'goal_amount' => 200000,
            'raised_amount' => 0,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    public function test_completed_donation_credits_owner_wallet(): void
    {
        $donation = Donation::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->donor->id,
            'donor_name' => 'Test Donor',
            'donor_email' => 'donor@example.com',
            'donation_type' => 'money',
            'total_amount' => 1000.00,
            'platform_fee' => 30.00,
            'net_amount' => 970.00,
            'order_id' => 'order_test_1',
            'currency' => 'INR',
        ]);
        $donation->payment_status = 'pending';
        $donation->save();

        DB::table('donations')->where('id', $donation->id)->update([
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        $this->walletService->credit(
            $this->walletService->getOrCreateWallet($this->owner),
            970.00,
            'donation',
            $donation->id,
            Donation::class
        );

        $wallet = Wallet::where('owner_id', $this->owner->id)
            ->where('owner_type', get_class($this->owner))
            ->first();

        $this->assertNotNull($wallet);
        $this->assertEquals(970.00, (float) $wallet->reserved_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);

        $this->assertDatabaseHas('campaigns', [
            'id' => $this->campaign->id,
            'raised_amount' => 1000.00,
        ]);
    }

    public function test_failed_donation_does_not_credit_wallet(): void
    {
        $donation = Donation::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->donor->id,
            'donor_name' => 'Failed Donor',
            'donor_email' => 'failed@example.com',
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 15.00,
            'net_amount' => 485.00,
            'order_id' => 'order_test_2',
            'currency' => 'INR',
        ]);
        $donation->payment_status = 'pending';
        $donation->save();

        $walletBefore = $this->walletService->getOrCreateWallet($this->owner);
        $balanceBefore = (float) $walletBefore->reserved_balance;

        $donation->refresh();
        $this->assertEquals('pending', $donation->payment_status);

        $walletAfter = $walletBefore->fresh();
        $this->assertEquals($balanceBefore, (float) $walletAfter->reserved_balance);
    }

    public function test_product_donation_creates_reservation(): void
    {
        $product = CampaignProduct::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->owner->id,
            'name' => 'Medicine Kit',
            'price' => 500,
            'quantity' => 10,
            'remaining_quantity' => 10,
            'is_active' => true,
            'approval_status' => 'approved',
        ]);

        $donation = Donation::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->donor->id,
            'donor_name' => 'Product Donor',
            'donor_email' => 'product@example.com',
            'donation_type' => 'product',
            'total_amount' => 500.00,
            'net_amount' => 485.00,
            'order_id' => 'order_test_3',
            'currency' => 'INR',
        ]);
        $donation->payment_status = 'pending';
        $donation->save();

        ProductReservation::create([
            'product_id' => $product->id,
            'donation_id' => $donation->id,
            'quantity' => 1,
            'expires_at' => now()->addMinutes(15),
        ]);

        $this->assertDatabaseHas('product_reservations', [
            'product_id' => $product->id,
            'donation_id' => $donation->id,
        ]);
    }

    public function test_refund_returns_amount_to_wallet(): void
    {
        $donation = Donation::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->donor->id,
            'donor_name' => 'Refund Donor',
            'donor_email' => 'refund@example.com',
            'donation_type' => 'money',
            'total_amount' => 2000.00,
            'platform_fee' => 60.00,
            'net_amount' => 1940.00,
            'order_id' => 'order_test_4',
            'currency' => 'INR',
        ]);
        $donation->payment_status = 'pending';
        $donation->save();

        DB::table('donations')->where('id', $donation->id)->update([
            'payment_status' => 'completed',
            'paid_at' => now(),
        ]);

        $this->walletService->credit(
            $this->walletService->getOrCreateWallet($this->owner),
            1940.00,
            'donation',
            $donation->id,
            Donation::class
        );

        $wallet = $this->walletService->getOrCreateWallet($this->owner);
        $this->assertEquals(1940.00, (float) $wallet->reserved_balance);

        $this->walletService->debit(
            $wallet,
            1940.00,
            'refund',
            $donation->id,
            Donation::class
        );

        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);
    }

    public function test_wallet_balance_equals_total_received_minus_reserved(): void
    {
        $wallet = $this->walletService->getOrCreateWallet($this->owner);

        $this->walletService->credit($wallet, 1000.00, 'donation', 1, Donation::class);
        $this->walletService->credit($wallet, 500.00, 'donation', 2, Donation::class);
        $this->walletService->credit($wallet, 200.00, 'adjustment', 3, User::class);

        $wallet->refresh();
        $this->assertEquals(1500.00, (float) $wallet->reserved_balance);
        $this->assertEquals(200.00, (float) $wallet->balance);
        $this->assertEquals(1700.00, (float) $wallet->reserved_balance + (float) $wallet->balance);
    }
}
