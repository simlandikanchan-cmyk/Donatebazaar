<?php

namespace Tests\Unit\Settlement;

use App\Events\RiskEvaluationCompleted;
use App\Events\SettlementAutoApproved;
use App\Events\SettlementManualReviewRequired;
use App\Events\SettlementRejected;
use App\Events\SettlementRequested;
use App\Exceptions\InsufficientWalletBalanceException;
use App\Gateways\RazorpayGateway;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\RiskConfig;
use App\Models\RiskRule;
use App\Models\RiskScore;
use App\Models\SettlementItem;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\Risk\Rules\KycVerifiedRule;
use App\Services\Risk\RiskEngine;
use App\Services\Risk\RiskRuleRegistry;
use App\Services\Risk\ScoreCalculator;
use App\Services\Risk\VerdictResolver;
use App\Services\SettlementService;
use App\Services\Settlement\SettlementStateMachine;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SettlementServiceTest extends TestCase
{
    use RefreshDatabase;

    private function seedConfig(int $version = 1, int $approval = 30, int $manualReview = 60): RiskConfig
    {
        return RiskConfig::create([
            'risk_version' => $version,
            'approval_threshold' => $approval,
            'manual_review_threshold' => $manualReview,
        ]);
    }

    private function registry(): RiskRuleRegistry
    {
        $registry = new RiskRuleRegistry(app());
        $registry->register('KYC_VERIFIED', KycVerifiedRule::class);

        return $registry;
    }

    private function riskEngine(): RiskEngine
    {
        return new RiskEngine($this->registry(), new ScoreCalculator(), new VerdictResolver());
    }

    private function service(): SettlementService
    {
        $gateway = $this->createMock(RazorpayGateway::class);

        return new SettlementService(
            new WalletService(),
            new SettlementStateMachine(),
            $this->riskEngine(),
            $gateway
        );
    }

    private function createDonation(User $user, ?string $status = 'pending', float $netAmount = 100.00): Donation
    {
        $campaign = Campaign::factory()->create(['user_id' => $user->id]);

        return Donation::create([
            'user_id' => $user->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => $netAmount,
            'platform_fee' => round($netAmount * 0.05, 2),
            'net_amount' => $netAmount,
            'payment_status' => 'completed',
            'is_refunded' => false,
            'settlement_status' => $status,
            'paid_at' => now()->subDays(10),
        ]);
    }

    private function orgWithOwner(): Organization
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        return $org;
    }

    #[Test]
    public function successful_request_creates_settlement_and_transitions_through_risk_evaluation(): void
    {
        $this->seedConfig(approval: 100, manualReview: 100);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 50,
            'priority' => 1,
            'enabled' => true,
        ]);

        $org = $this->orgWithOwner();
        $owner = $org->owner;
        $donation = $this->createDonation($owner);

        $wallet = app(WalletService::class)->getOrCreateWallet($owner);
        app(WalletService::class)->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        Event::fake([
            SettlementRequested::class,
            RiskEvaluationCompleted::class,
            SettlementAutoApproved::class,
            SettlementManualReviewRequired::class,
            SettlementRejected::class,
        ]);

        $settlement = $this->service()->requestSettlement($org, [$donation->id]);

        $this->assertSame('auto_approved', $settlement->status);
        $this->assertSame(50, $settlement->risk_score);
        $this->assertSame(1, $settlement->risk_version);
        $this->assertDatabaseHas('campaign_settlements', [
            'id' => $settlement->id,
            'organization_id' => $org->id,
            'net_amount' => 100.00,
        ]);
        $this->assertDatabaseHas('settlement_items', [
            'campaign_settlement_id' => $settlement->id,
            'donation_id' => $donation->id,
            'amount' => 100.00,
        ]);

        Event::assertDispatched(SettlementRequested::class);
        Event::assertDispatched(RiskEvaluationCompleted::class);
        Event::assertDispatched(SettlementAutoApproved::class);
    }

    #[Test]
    public function insufficient_balance_rolls_back_and_throws(): void
    {
        $org = $this->orgWithOwner();
        $owner = $org->owner;
        $donation = $this->createDonation($owner, 'pending', 500.00);

        $this->expectException(InsufficientWalletBalanceException::class);

        $this->service()->requestSettlement($org, [$donation->id]);

        $this->assertDatabaseMissing('campaign_settlements', [
            'organization_id' => $org->id,
        ]);
    }

    #[Test]
    public function duplicate_settlement_is_rejected_when_donations_already_locked(): void
    {
        $org = $this->orgWithOwner();
        $owner = $org->owner;
        $donation = $this->createDonation($owner);

        $existing = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'pending_approval',
        ]);
        SettlementItem::create([
            'campaign_settlement_id' => $existing->id,
            'donation_id' => $donation->id,
            'amount' => 100.00,
        ]);

        $this->expectException(\InvalidArgumentException::class);

        $this->service()->requestSettlement($org, [$donation->id]);
    }

    #[Test]
    public function manual_review_verdict_transitions_to_manual_review_and_dispatches_event(): void
    {
        $this->seedConfig(approval: 10, manualReview: 20);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 15,
            'priority' => 1,
            'enabled' => true,
        ]);

        $org = $this->orgWithOwner();
        $owner = $org->owner;
        $donation = $this->createDonation($owner, 'pending');

        $wallet = app(WalletService::class)->getOrCreateWallet($owner);
        app(WalletService::class)->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        Event::fake([
            SettlementRequested::class,
            RiskEvaluationCompleted::class,
            SettlementAutoApproved::class,
            SettlementManualReviewRequired::class,
            SettlementRejected::class,
        ]);

        $settlement = $this->service()->requestSettlement($org, [$donation->id]);

        $this->assertSame('manual_review', $settlement->status);
        $this->assertSame(15, $settlement->risk_score);
        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $settlement->risk_verdict);

        Event::assertDispatched(SettlementRequested::class);
        Event::assertDispatched(RiskEvaluationCompleted::class);
        Event::assertDispatched(SettlementManualReviewRequired::class);
        Event::assertNotDispatched(SettlementAutoApproved::class);
    }

    #[Test]
    public function state_machine_integration_creates_audit_logs(): void
    {
        $this->seedConfig(approval: 100, manualReview: 100);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 50,
            'priority' => 1,
            'enabled' => true,
        ]);

        $org = $this->orgWithOwner();
        $owner = $org->owner;
        $donation = $this->createDonation($owner, 'pending');

        $wallet = app(WalletService::class)->getOrCreateWallet($owner);
        app(WalletService::class)->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        $settlement = $this->service()->requestSettlement($org, [$donation->id]);

        $this->assertSame('auto_approved', $settlement->status);

        $logs = \App\Models\SettlementStateLog::where('settlement_id', $settlement->id)
            ->orderBy('created_at')
            ->get();

        $this->assertCount(2, $logs);
        $this->assertSame('risk_evaluation', $logs[0]->to_state);
        $this->assertSame('auto_approved', $logs[1]->to_state);
    }

    #[Test]
    public function wallet_locks_funds_during_request(): void
    {
        $this->seedConfig(approval: 100, manualReview: 100);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 50,
            'priority' => 1,
            'enabled' => true,
        ]);

        $org = $this->orgWithOwner();
        $owner = $org->owner;
        $wallet = app(WalletService::class)->getOrCreateWallet($owner);
        app(WalletService::class)->credit($wallet, 500.00, 'adjustment', 1, User::class);

        $donation = $this->createDonation($owner, 'pending', 100.00);

        $this->service()->requestSettlement($org, [$donation->id]);

        $wallet->refresh();
        $this->assertSame(400.00, (float) $wallet->balance);
        $this->assertSame(100.00, (float) $wallet->pending_settlement_balance);
    }

    #[Test]
    public function approve_debits_pending_balance_and_transitions_to_approved(): void
    {
        $org = $this->orgWithOwner();
        $owner = $org->owner;
        $wallet = app(WalletService::class)->getOrCreateWallet($owner);
        app(WalletService::class)->credit($wallet, 500.00, 'adjustment', 1, User::class);

        $donation = $this->createDonation($owner, 'pending', 100.00);
        $settlement = $this->service()->requestSettlement($org, [$donation->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $admin = User::factory()->create();
        $this->service()->approveSettlement($settlement->fresh(), $admin);

        $this->assertSame('approved', $settlement->fresh()->status);
        $wallet->refresh();
        $this->assertSame(0.00, (float) $wallet->pending_settlement_balance);
    }

    #[Test]
    public function reject_returns_funds_and_transitions_to_rejected(): void
    {
        $org = $this->orgWithOwner();
        $owner = $org->owner;
        $wallet = app(WalletService::class)->getOrCreateWallet($owner);
        app(WalletService::class)->credit($wallet, 500.00, 'adjustment', 1, User::class);

        $donation = $this->createDonation($owner, 'pending', 100.00);
        $settlement = $this->service()->requestSettlement($org, [$donation->id]);

        $admin = User::factory()->create();
        $this->service()->rejectSettlement($settlement->fresh(), $admin, 'Bad details');

        $this->assertSame('rejected', $settlement->fresh()->status);
        $wallet->refresh();
        $this->assertSame(500.00, (float) $wallet->balance);
        $this->assertSame(0.00, (float) $wallet->pending_settlement_balance);
    }

    #[Test]
    public function transaction_rollback_on_wallet_failure_prevents_settlement_creation(): void
    {
        $org = $this->orgWithOwner();
        $owner = $org->owner;

        $donation = Donation::create([
            'user_id' => $owner->id,
            'campaign_id' => Campaign::factory()->create(['user_id' => $owner->id])->id,
            'donation_type' => 'money',
            'total_amount' => 100.00,
            'platform_fee' => 5.00,
            'net_amount' => 100.00,
            'payment_status' => 'completed',
            'is_refunded' => false,
            'settlement_status' => 'pending',
            'paid_at' => now()->subDays(10),
        ]);

        $this->expectException(InsufficientWalletBalanceException::class);

        $this->service()->requestSettlement($org, [$donation->id]);

        $this->assertDatabaseMissing('campaign_settlements', [
            'organization_id' => $org->id,
        ]);
        $this->assertDatabaseMissing('settlement_items', [
            'donation_id' => $donation->id,
        ]);
    }
}
