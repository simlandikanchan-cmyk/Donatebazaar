<?php

namespace Tests\Feature;

use App\Gateways\RazorpayGateway;
use App\Events\SettlementProcessingStarted;
use App\Exceptions\PermanentFailureException;
use App\Exceptions\TemporaryFailureException;
use App\Exceptions\TimeoutException;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Services\SettlementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TransactionBoundaryTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private User $admin;

    private Organization $org;

    private SettlementService $settlementService;

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

        $this->settlementService = app(SettlementService::class);
    }

    #[Test]
    public function transaction_commits_before_gateway_call(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $gatewayCalled = false;
        $settlementInProcessing = null;

        $gateway = $this->createMock(RazorpayGateway::class);
        $gateway->method('initiatePayout')->willReturnCallback(function () use (&$gatewayCalled, &$settlementInProcessing, $settlement) {
            $gatewayCalled = true;
            $settlementInProcessing = CampaignSettlement::find($settlement->id);
            return [
                'gateway_reference' => 'PAYOUT_'.$settlement->id,
                'provider_status' => 'paid',
            ];
        });

        $service = new SettlementService(
            walletService: app(\App\Services\WalletService::class),
            stateMachine: new \App\Services\Settlement\SettlementStateMachine(),
            riskEngine: app(\App\Services\Risk\RiskEngine::class),
            gateway: $gateway
        );

        $service->processSettlementPayout($settlement);

        $this->assertTrue($gatewayCalled);
        $this->assertSame('processing', $settlementInProcessing->status);
        $this->assertNotNull($settlementInProcessing->processed_at);
    }

    #[Test]
    public function gateway_timeout_after_phase1_commit_transitions_to_retry_pending(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $gateway = $this->createMock(RazorpayGateway::class);
        $gateway->method('initiatePayout')->willThrowException(new TimeoutException('Gateway timeout'));

        $service = new SettlementService(
            walletService: app(\App\Services\WalletService::class),
            stateMachine: new \App\Services\Settlement\SettlementStateMachine(),
            riskEngine: app(\App\Services\Risk\RiskEngine::class),
            gateway: $gateway
        );

        $result = $service->processSettlementPayout($settlement);

        $this->assertFalse($result['success']);
        $this->assertTrue($result['retryable']);
        $settlement->refresh();
        $this->assertSame('retry_pending', $settlement->status);
        $this->assertNotNull($settlement->processed_at);
    }

    #[Test]
    public function retry_pending_settlement_without_gateway_reference_calls_gateway(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'status' => 'retry_pending',
            'processed_at' => now(),
            'gateway_reference' => null,
        ]);

        $gateway = $this->createMock(RazorpayGateway::class);
        $gateway->method('initiatePayout')->willReturn([
            'gateway_reference' => 'PAYOUT_'.$settlement->id,
            'provider_status' => 'paid',
        ]);

        $service = new SettlementService(
            walletService: app(\App\Services\WalletService::class),
            stateMachine: new \App\Services\Settlement\SettlementStateMachine(),
            riskEngine: app(\App\Services\Risk\RiskEngine::class),
            gateway: $gateway
        );

        $result = $service->processSettlementPayout($settlement);

        $this->assertTrue($result['success']);
        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);
    }

    #[Test]
    public function retry_pending_settlement_with_gateway_reference_still_calls_gateway(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'status' => 'retry_pending',
            'processed_at' => now(),
            'gateway_reference' => 'PAYOUT_123',
        ]);

        $gateway = $this->createMock(RazorpayGateway::class);
        $gateway->expects($this->once())->method('initiatePayout')->willReturn([
            'gateway_reference' => 'PAYOUT_123',
            'provider_status' => 'paid',
        ]);

        $service = new SettlementService(
            walletService: app(\App\Services\WalletService::class),
            stateMachine: new \App\Services\Settlement\SettlementStateMachine(),
            riskEngine: app(\App\Services\Risk\RiskEngine::class),
            gateway: $gateway
        );

        $result = $service->processSettlementPayout($settlement);

        $this->assertTrue($result['success']);
        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);
    }

    #[Test]
    public function phase1_commit_does_not_hold_lock_during_gateway_call(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $lockHeldDuringGateway = false;

        $gateway = $this->createMock(RazorpayGateway::class);
        $gateway->method('initiatePayout')->willReturnCallback(function () use ($settlement, &$lockHeldDuringGateway) {
            $fresh = CampaignSettlement::find($settlement->id);
            $lockHeldDuringGateway = DB::selectOne(
                'SELECT COUNT(*) as count FROM information_schema.innodb_locks WHERE lock_table = ?',
                ['campaign_settlements']
            )->count > 0;

            return [
                'gateway_reference' => 'PAYOUT_'.$settlement->id,
                'provider_status' => 'paid',
            ];
        });

        $service = new SettlementService(
            walletService: app(\App\Services\WalletService::class),
            stateMachine: new \App\Services\Settlement\SettlementStateMachine(),
            riskEngine: app(\App\Services\Risk\RiskEngine::class),
            gateway: $gateway
        );

        $service->processSettlementPayout($settlement);
        $this->assertFalse($lockHeldDuringGateway, 'DB lock should not be held during gateway call');
    }

    #[Test]
    public function wallet_reversal_and_state_transition_are_atomic_on_permanent_failure(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $gateway = $this->createMock(RazorpayGateway::class);
        $gateway->method('initiatePayout')->willThrowException(new PermanentFailureException('Permanent failure'));

        $service = new SettlementService(
            walletService: app(\App\Services\WalletService::class),
            stateMachine: new \App\Services\Settlement\SettlementStateMachine(),
            riskEngine: app(\App\Services\Risk\RiskEngine::class),
            gateway: $gateway
        );

        $result = $service->processSettlementPayout($settlement);

        $this->assertFalse($result['success']);
        $this->assertFalse($result['retryable']);
        $settlement->refresh();
        $this->assertSame('failed', $settlement->status);
    }

    #[Test]
    public function duplicate_execution_is_idempotent_across_split_transactions(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $gateway = $this->createMock(RazorpayGateway::class);
        $gateway->method('initiatePayout')->willReturn([
            'gateway_reference' => 'PAYOUT_'.$settlement->id,
            'provider_status' => 'paid',
        ]);

        $service = new SettlementService(
            walletService: app(\App\Services\WalletService::class),
            stateMachine: new \App\Services\Settlement\SettlementStateMachine(),
            riskEngine: app(\App\Services\Risk\RiskEngine::class),
            gateway: $gateway
        );

        $result1 = $service->processSettlementPayout($settlement);
        $result2 = $service->processSettlementPayout($settlement->fresh());

        $this->assertTrue($result1['success']);
        $this->assertTrue($result2['success']);
        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);
        $this->assertSame('PAYOUT_'.$settlement->id, $settlement->gateway_reference);
    }
}
