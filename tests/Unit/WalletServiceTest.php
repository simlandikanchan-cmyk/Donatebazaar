<?php

namespace Tests\Unit;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\Refund;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    private WalletService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new WalletService;
    }

    #[Test]
    public function credit_donation_goes_to_reserved_balance(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);

        $tx = $this->service->credit($wallet, 100.00, WalletTransaction::SOURCE_DONATION, 1, Donation::class);

        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->balance);
        $this->assertEquals(100.00, (float) $wallet->reserved_balance);
        $this->assertEquals('credit', $tx->type);
        $this->assertEquals(100.00, (float) $tx->balance_after);
    }

    #[Test]
    public function credit_non_donation_goes_to_balance(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);

        $this->service->credit($wallet, 50.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        $wallet->refresh();
        $this->assertEquals(50.00, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);
    }

    #[Test]
    public function credit_is_idempotent_on_same_reference(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);

        $this->service->credit($wallet, 100.00, WalletTransaction::SOURCE_DONATION, 42, Donation::class);
        $this->service->credit($wallet, 100.00, WalletTransaction::SOURCE_DONATION, 42, Donation::class);

        $wallet->refresh();
        $this->assertEquals(100.00, (float) $wallet->reserved_balance);
        $this->assertEquals(1, WalletTransaction::where('reference_id', 42)->count());
    }

    #[Test]
    public function debit_throws_when_insufficient(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $this->service->credit($wallet, 30.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        $this->expectException(InsufficientWalletBalanceException::class);
        $this->service->debit($wallet, 999.00, WalletTransaction::SOURCE_ADJUSTMENT, 2, User::class);
    }

    #[Test]
    public function debit_pulls_from_reserved_first_for_refund(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);

        $this->service->credit($wallet, 200.00, WalletTransaction::SOURCE_DONATION, 10, Donation::class);
        $this->service->credit($wallet, 50.00, WalletTransaction::SOURCE_ADJUSTMENT, 11, User::class);

        $wallet->refresh();
        $this->assertEquals(200.00, (float) $wallet->reserved_balance);
        $this->assertEquals(50.00, (float) $wallet->balance);

        $campaign = Campaign::create([
            'title' => 'Wallet Test Campaign',
            'slug' => 'wallet-test-'.uniqid(),
            'user_id' => $owner->id,
            'description' => 'Wallet test campaign.',
            'goal_amount' => 10000.00,
        ]);

        $refundDonation = Donation::create([
            'user_id' => $owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 200.00,
            'platform_fee' => 10.00,
            'net_amount' => 200.00,
        ]);
        DB::table('donations')->where('id', $refundDonation->id)->update([
            'payment_status' => 'completed',
            'is_refunded' => false,
            'paid_at' => now()->subDays(2),
        ]);

        $refund = Refund::create([
            'donation_id' => $refundDonation->id,
            'amount' => 200.00,
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        $this->service->debit($wallet, 200.00, WalletTransaction::SOURCE_REFUND, $refund->id, Refund::class);

        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);
        $this->assertEquals(50.00, (float) $wallet->balance);
    }

    #[Test]
    public function release_matured_reserves_moves_to_balance(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);

        $campaign = Campaign::create([
            'title' => 'Wallet Test Campaign',
            'slug' => 'wallet-test-'.uniqid(),
            'user_id' => $owner->id,
            'description' => 'Wallet test campaign.',
            'goal_amount' => 10000.00,
        ]);

        $donation = Donation::create([
            'user_id' => $owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 120.00,
            'platform_fee' => 6.00,
            'net_amount' => 120.00,
        ]);
        $donation->payment_status = 'completed';
        $donation->is_refunded = false;
        $donation->paid_at = now()->subDays(10);
        $donation->save();

        $this->service->credit($wallet, 120.00, WalletTransaction::SOURCE_DONATION, $donation->id, Donation::class);

        $released = $this->service->releaseMaturedReserves();

        $this->assertGreaterThanOrEqual(1, $released);
        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);
        $this->assertEquals(120.00, (float) $wallet->balance);
    }

    #[Test]
    public function concurrent_debit_race_does_not_corrupt_balance(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $this->service->credit($wallet, 100.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);
        $wallet->refresh();

        $exceptionThrown = false;
        try {
            DB::transaction(function () use ($wallet) {
                DB::transaction(function () use ($wallet) {
                    $this->service->debit($wallet, 100.00, WalletTransaction::SOURCE_ADJUSTMENT, 2, User::class);
                });
            });
        } catch (InsufficientWalletBalanceException $e) {
            $exceptionThrown = true;
        }

        try {
            $this->service->debit($wallet, 100.00, WalletTransaction::SOURCE_ADJUSTMENT, 3, User::class);
        } catch (InsufficientWalletBalanceException $e) {
            $exceptionThrown = true;
        }

        $wallet->refresh();
        $this->assertGreaterThanOrEqual(0.00, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->balance);
    }

    #[Test]
    public function release_does_not_move_unmatured_reserves(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $campaign = $this->makeCampaign($owner);

        $donation = $this->makeEligibleDonation($owner, $campaign, 120.00, 2);
        $this->service->credit($wallet, 120.00, WalletTransaction::SOURCE_DONATION, $donation->id, Donation::class);

        $released = $this->service->releaseMaturedReserves();

        $wallet->refresh();
        $this->assertEquals(0, $released);
        $this->assertEquals(120.00, (float) $wallet->reserved_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);
        $this->assertNull($donation->refresh()->released_at);
    }

    #[Test]
    public function release_skips_refunded_donations(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $campaign = $this->makeCampaign($owner);

        $donation = $this->makeEligibleDonation($owner, $campaign, 120.00, 10, refunded: true);
        $this->service->credit($wallet, 120.00, WalletTransaction::SOURCE_DONATION, $donation->id, Donation::class);

        $released = $this->service->releaseMaturedReserves();

        $wallet->refresh();
        $this->assertEquals(0, $released);
        $this->assertEquals(120.00, (float) $wallet->reserved_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);
    }

    #[Test]
    public function release_is_idempotent(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $campaign = $this->makeCampaign($owner);

        $donation = $this->makeEligibleDonation($owner, $campaign, 120.00, 10);
        $this->service->credit($wallet, 120.00, WalletTransaction::SOURCE_DONATION, $donation->id, Donation::class);

        $first = $this->service->releaseMaturedReserves();
        $second = $this->service->releaseMaturedReserves();

        $wallet->refresh();
        $this->assertGreaterThanOrEqual(1, $first);
        $this->assertEquals(0, $second);
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);
        $this->assertEquals(120.00, (float) $wallet->balance);

        $this->assertEquals(
            1,
            WalletTransaction::where('wallet_id', $wallet->id)
                ->where('source', WalletTransaction::SOURCE_ADJUSTMENT)
                ->where('reference_type', Donation::class)
                ->where('reference_id', $donation->id)
                ->where('type', 'credit')
                ->count()
        );
    }

    #[Test]
    public function debit_is_idempotent_on_same_reference(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $this->service->credit($wallet, 100.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        $this->service->debit($wallet, 40.00, WalletTransaction::SOURCE_ADJUSTMENT, 7, User::class);
        $this->service->debit($wallet, 40.00, WalletTransaction::SOURCE_ADJUSTMENT, 7, User::class);

        $wallet->refresh();
        $this->assertEquals(60.00, (float) $wallet->balance);
        $this->assertEquals(
            1,
            WalletTransaction::where('wallet_id', $wallet->id)
                ->where('reference_id', 7)
                ->where('type', 'debit')
                ->count()
        );
    }

    #[Test]
    public function credit_rejects_non_positive_amount(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->credit($wallet, 0.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);
    }

    #[Test]
    public function debit_rejects_non_positive_amount(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $this->service->credit($wallet, 100.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->debit($wallet, -5.00, WalletTransaction::SOURCE_ADJUSTMENT, 2, User::class);
    }

    #[Test]
    public function release_reserves_for_donations_moves_matured_funds(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $campaign = $this->makeCampaign($owner);

        $donation = $this->makeEligibleDonation($owner, $campaign, 250.00, 10);
        $this->service->credit($wallet, 250.00, WalletTransaction::SOURCE_DONATION, $donation->id, Donation::class);

        $wallet->refresh();
        $this->assertEquals(250.00, (float) $wallet->reserved_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);

        $released = $this->service->releaseReservesForDonations($wallet, [$donation]);

        $this->assertEquals(1, $released);
        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);
        $this->assertEquals(250.00, (float) $wallet->balance);
    }

    #[Test]
    public function release_reserves_for_donations_skips_unmatured(): void
    {
        $owner = User::factory()->create();
        $wallet = $this->service->getOrCreateWallet($owner);
        $campaign = $this->makeCampaign($owner);

        $donation = $this->makeEligibleDonation($owner, $campaign, 250.00, 2);
        $this->service->credit($wallet, 250.00, WalletTransaction::SOURCE_DONATION, $donation->id, Donation::class);

        $released = $this->service->releaseReservesForDonations($wallet, [$donation]);

        $this->assertEquals(0, $released);
        $wallet->refresh();
        $this->assertEquals(250.00, (float) $wallet->reserved_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);
    }

    private function makeCampaign(User $owner): Campaign
    {
        return Campaign::create([
            'title' => 'Wallet Test Campaign',
            'slug' => 'wallet-test-'.uniqid(),
            'user_id' => $owner->id,
            'description' => 'Wallet test campaign.',
            'goal_amount' => 10000.00,
        ]);
    }

    private function makeEligibleDonation(
        User $owner,
        Campaign $campaign,
        float $amount = 300.00,
        int $paidDaysAgo = 0,
        bool $refunded = false
    ): Donation {
        $donation = Donation::create([
            'user_id' => $owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => $amount,
            'platform_fee' => round($amount * 0.05, 2),
            'net_amount' => $amount,
        ]);

        DB::table('donations')->where('id', $donation->id)->update([
            'payment_status' => $refunded ? 'refunded' : 'completed',
            'is_refunded' => $refunded,
            'paid_at' => now()->subDays($paidDaysAgo),
        ]);

        return $donation->refresh();
    }
}
