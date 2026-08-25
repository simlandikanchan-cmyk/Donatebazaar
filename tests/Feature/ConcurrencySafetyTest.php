<?php

namespace Tests\Feature;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConcurrencySafetyTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private Wallet $wallet;

    private WalletService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->create(['role' => 'donor']);
        $this->service = app(WalletService::class);
        $this->wallet = $this->service->getOrCreateWallet($this->owner);
    }

    /**
     * Verify that lockForUpdate + idempotency guard prevents
     * double-processing when the same reference is used twice.
     */
    #[Test]
    public function idempotent_credit_returns_same_transaction(): void
    {
        $tx1 = $this->service->credit($this->wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 'unique-ref-1', User::class);
        $tx2 = $this->service->credit($this->wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 'unique-ref-1', User::class);

        $this->assertEquals($tx1->id, $tx2->id, 'Same idempotency key should return same transaction');

        $this->wallet->refresh();
        $this->assertEquals(500.00, (float) $this->wallet->balance);

        $count = WalletTransaction::where('reference_id', 'unique-ref-1')
            ->where('source', WalletTransaction::SOURCE_ADJUSTMENT)
            ->count();
        $this->assertEquals(1, $count, 'Only one transaction should exist for same reference');
    }

    /**
     * Verify that debit operations check balance before proceeding.
     */
    #[Test]
    public function debit_fails_when_insufficient_balance(): void
    {
        $this->service->credit($this->wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);
        $this->wallet->refresh();
        $this->assertEquals(500.00, (float) $this->wallet->balance);

        // First debit of 300 should succeed
        $this->service->debit($this->wallet, 300.00, WalletTransaction::SOURCE_ADJUSTMENT, 2, User::class);
        $this->wallet->refresh();
        $this->assertEquals(200.00, (float) $this->wallet->balance);

        // Second debit of 300 should fail — insufficient balance
        $this->expectException(InsufficientWalletBalanceException::class);
        $this->service->debit($this->wallet, 300.00, WalletTransaction::SOURCE_ADJUSTMENT, 3, User::class);
    }

    /**
     * Verify that the same debit reference is idempotent.
     */
    #[Test]
    public function idempotent_debit_returns_same_transaction(): void
    {
        $this->service->credit($this->wallet, 1000.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);
        $this->wallet->refresh();

        $tx1 = $this->service->debit($this->wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 'debit-ref', User::class);
        $tx2 = $this->service->debit($this->wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 'debit-ref', User::class);

        $this->assertEquals($tx1->id, $tx2->id);
        $this->wallet->refresh();
        $this->assertEquals(500.00, (float) $this->wallet->balance);

        $count = WalletTransaction::where('reference_id', 'debit-ref')->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Verify that concurrent settlement requests for the same donations
     * are idempotent — the second request is rejected because donations
     * are already locked in the first settlement.
     */
    #[Test]
    public function concurrent_settlement_requests_are_idempotent(): void
    {
        $org = Organization::factory()->create(['user_id' => $this->owner->id]);

        $campaign = Campaign::create([
            'user_id' => $this->owner->id,
            'title' => 'Test Campaign',
            'slug' => 'test-concurrent-settlement',
            'description' => 'Test',
            'goal_amount' => 10000,
        ]);

        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => $this->owner->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 475.00,
        ]);
        $donation->payment_status = 'completed';
        $donation->is_refunded = false;
        $donation->paid_at = now()->subDays(10);
        $donation->save();

        // Credit wallet so settlement has funds to debit
        $this->service->credit($this->wallet, 10000.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        $service = app(SettlementService::class);

        $settlement1 = $service->requestSettlement($org, [$donation->id]);

        // Second request should fail — donation already locked in settlement
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('already locked in a pending or approved settlement');
        $service->requestSettlement($org, [$donation->id]);

        // Verify only one settlement was created
        $this->assertDatabaseCount('campaign_settlements', 1, 'Only one settlement should be created');
    }
}
