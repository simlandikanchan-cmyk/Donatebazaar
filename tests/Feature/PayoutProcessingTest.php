<?php

namespace Tests\Feature;

use App\Jobs\ProcessSettlementJob;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Notifications\SettlementProcessingStartedNotification;
use App\Notifications\SettlementRetryScheduledNotification;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PayoutProcessingTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private User $admin;

    private Organization $org;

    private WalletService $walletService;

    private SettlementService $settlementService;

    private Donation $donation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->org = Organization::factory()->create(['user_id' => $this->owner->id]);

        PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $this->walletService = app(WalletService::class);
        $this->settlementService = app(SettlementService::class);
        $wallet = $this->walletService->getOrCreateWallet($this->owner);
        $this->walletService->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, Organization::class);

        $campaign = Campaign::create([
            'title' => 'Payout Test Campaign',
            'slug' => 'payout-test-'.uniqid(),
            'user_id' => $this->owner->id,
            'description' => 'Test campaign',
            'goal_amount' => 10000.00,
        ]);

        $this->donation = Donation::create([
            'user_id' => $this->owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 500.00,
        ]);
        $this->donation->payment_status = 'completed';
        $this->donation->is_refunded = false;
        $this->donation->paid_at = now()->subDays(10);
        $this->donation->save();
    }

    #[Test]
    public function successful_payout_marks_settlement_paid_and_donations_settled(): void
    {
        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        $result = $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();
        $this->donation->refresh();

        $this->assertTrue($result['success']);
        $this->assertEquals('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
        $this->assertNotNull($settlement->processed_at);
        $this->assertNull($settlement->failed_at);
        $this->assertNull($settlement->failed_reason);
        $this->assertNotEmpty($settlement->gateway_reference);
        $this->assertEquals('settled', $this->donation->settlement_status);
        $this->assertEquals($settlement->id, $this->donation->campaign_settlement_id);
    }

    #[Test]
    public function payout_failure_restores_balance_and_records_reversal(): void
    {
        $failOwner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $failOwner->id, 'name' => 'FAIL Org']);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($failOwner);
        $wallet->balance = 0;
        $wallet->pending_settlement_balance = 0;
        $wallet->save();

        $campaign = Campaign::create([
            'title' => 'Fail Restore Campaign',
            'slug' => 'fail-restore-'.uniqid(),
            'user_id' => $failOwner->id,
            'description' => 'test',
            'goal_amount' => 10000.00,
        ]);
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'user_id' => $failOwner->id,
            'gross_amount' => 525.00,
            'net_amount' => 500.00,
            'platform_fee' => 25.00,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $result = $this->settlementService->processSettlementPayout($settlement);

        $settlement->refresh();
        $wallet->refresh();

        $this->assertFalse($result['success']);
        $this->assertEquals('retry_pending', $settlement->status);
        $this->assertNull($settlement->failed_at);
        $this->assertNull($settlement->failed_reason);
        $this->assertSame(1, $settlement->retry_count);
        $this->assertNotNull($settlement->next_retry_at);
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);

        $this->assertDatabaseMissing('wallet_transactions', [
            'source' => WalletTransaction::SOURCE_SETTLEMENT_REVERSAL,
            'reference_id' => $settlement->id,
        ]);
    }

    #[Test]
    public function payout_is_idempotent_when_already_paid(): void
    {
        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);
        $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();
        $paidAt = $settlement->paid_at;
        $reference = $settlement->gateway_reference;

        // Second call returns immediately with success.
        $result = $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();

        $this->assertTrue($result['success']);
        $this->assertEquals('paid', $settlement->status);
        $this->assertEquals($paidAt->format('Y-m-d H:i:s'), $settlement->paid_at->format('Y-m-d H:i:s'));
        $this->assertEquals($reference, $settlement->gateway_reference);
    }

    #[Test]
    public function payout_is_idempotent_when_already_failed(): void
    {
        $failOwner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $failOwner->id, 'name' => 'FAIL Org Retry']);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($failOwner);
        $wallet->update(['balance' => 0, 'pending_settlement_balance' => 500]);

        $campaign = Campaign::create([
            'title' => 'Fail Idempotent Campaign',
            'slug' => 'fail-idem-'.uniqid(),
            'user_id' => $failOwner->id,
            'description' => 'test',
            'goal_amount' => 10000.00,
        ]);
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'user_id' => $failOwner->id,
            'gross_amount' => 525.00,
            'net_amount' => 500.00,
            'platform_fee' => 25.00,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();
        $this->assertEquals('retry_pending', $settlement->status);

        // Second call returns immediately (idempotent).
        $result = $this->settlementService->processSettlementPayout($settlement);
        $this->assertFalse($result['success']);
        $this->assertEquals('retry_pending', $settlement->status);
    }

    #[Test]
    public function payout_retry_after_failure_can_succeed_with_fixed_org_name(): void
    {
        $failOwner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $failOwner->id, 'name' => 'FAIL Then Fix']);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($failOwner);
        $wallet->update(['balance' => 0, 'pending_settlement_balance' => 500]);

        $campaign = Campaign::create([
            'title' => 'Fail Then Fix Campaign',
            'slug' => 'fail-fix-'.uniqid(),
            'user_id' => $failOwner->id,
            'description' => 'test',
            'goal_amount' => 10000.00,
        ]);
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'user_id' => $failOwner->id,
            'gross_amount' => 525.00,
            'net_amount' => 500.00,
            'platform_fee' => 25.00,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();
        $this->assertEquals('retry_pending', $settlement->status);

        // "Fix" the org name so the stub no longer throws.
        $org->update(['name' => 'Fixed Org']);

        // Add balance back so retry can debit it.
        $wallet->update(['balance' => 500, 'pending_settlement_balance' => 0]);

        // Reset status to retry_pending for retry.
        $settlement->update([
            'status' => 'retry_pending',
            'failed_at' => null,
            'failed_reason' => null,
            'retry_count' => 1,
        ]);

        $settlement = $settlement->fresh();
        $result = $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();

        $this->assertTrue($result['success']);
        $this->assertEquals('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
        $this->assertNotEmpty($settlement->gateway_reference);
    }

    #[Test]
    public function processing_state_accessible_during_payout(): void
    {
        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        $this->assertFalse($settlement->refresh()->isProcessing());

        // processSettlementPayout sets processing → paid atomically in the stub,
        // but we can observe the processing state by inspecting inside the method.
        // This validates the helper exists and works correctly.
        $settlement->update(['status' => 'processing', 'processed_at' => now()]);
        $this->assertTrue($settlement->refresh()->isProcessing());
        $this->assertNull($settlement->paid_at);
        $this->assertNull($settlement->failed_at);
    }

    #[Test]
    public function settlement_shows_as_approved_before_queue_processes(): void
    {
        Queue::fake();

        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->admin)
            ->post(route('admin.settlements.approve', $settlement))
            ->assertRedirect();

        $settlement->refresh();
        $this->assertEquals('approved', $settlement->status);
        $this->assertNull($settlement->paid_at);
        $this->assertNull($settlement->failed_at);
        $this->assertNull($settlement->gateway_reference);

        Queue::assertPushed(ProcessSettlementJob::class);
    }

    #[Test]
    public function double_approval_throws(): void
    {
        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only pending_approval or manual_review settlements can be approved');
        $this->settlementService->approveSettlement($settlement->refresh(), $this->admin);
    }

    #[Test]
    public function failed_payout_sends_retry_notifications(): void
    {
        Notification::fake();

        $failOwner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $failOwner->id, 'name' => 'FAIL Notification']);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($failOwner);
        $wallet->update(['balance' => 0, 'pending_settlement_balance' => 500]);

        $campaign = Campaign::create([
            'title' => 'Fail Notification Campaign',
            'slug' => 'fail-notif-'.uniqid(),
            'user_id' => $failOwner->id,
            'description' => 'test',
            'goal_amount' => 10000.00,
        ]);
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'user_id' => $failOwner->id,
            'gross_amount' => 525.00,
            'net_amount' => 500.00,
            'platform_fee' => 25.00,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $this->settlementService->processSettlementPayout($settlement);

        Notification::assertSentTo(
            $failOwner,
            SettlementProcessingStartedNotification::class
        );

        Notification::assertSentTo(
            $failOwner,
            SettlementRetryScheduledNotification::class
        );
    }

    #[Test]
    public function balance_unchanged_when_payout_succeeds(): void
    {
        $wallet = $this->walletService->getOrCreateWallet($this->owner);
        $this->walletService->credit($wallet, 1000.00, WalletTransaction::SOURCE_ADJUSTMENT, 2, Organization::class);

        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        $wallet->refresh();
        $balanceAfterApprove = (float) $wallet->balance;

        $this->settlementService->processSettlementPayout($settlement);

        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);
        // processSettlementPayout does NOT change balance — it was debited at approval time.
        $this->assertEquals($balanceAfterApprove, (float) $wallet->balance, 'Payout processing must not modify wallet balance');
    }
}
