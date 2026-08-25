<?php

namespace Tests\Unit\Reconciliation;

use App\Gateways\RazorpayGateway;
use App\Events\SettlementCancelled;
use App\Events\SettlementFailed;
use App\Events\SettlementPaid;
use App\Exceptions\PermanentFailureException;
use App\Exceptions\TemporaryFailureException;
use App\Exceptions\TimeoutException;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Services\Reconciliation\ReconciliationResult;
use App\Services\Reconciliation\ReconciliationService;
use App\Services\Settlement\SettlementStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReconciliationServiceTest extends TestCase
{
    use RefreshDatabase;

    private RazorpayGateway $gateway;

    private SettlementStateMachine $stateMachine;

    private ReconciliationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = $this->createMock(RazorpayGateway::class);
        $this->stateMachine = new SettlementStateMachine();
        $this->service = new ReconciliationService(
            gateway: $this->gateway,
            stateMachine: $this->stateMachine,
            batchSize: 10,
            processingStuckMinutes: 0
        );
    }

    #[Test]
    public function successful_reconciliation_transitions_processing_to_paid(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_123',
        ]);

        $this->gateway->method('getPayoutStatus')
            ->willReturn(['id' => 'PAYOUT_123', 'status' => 'paid', 'amount' => 500.00, 'currency' => 'INR']);

        $result = $this->service->reconcileSettlement($settlement);

        $this->assertTrue($result->reconciled);
        $this->assertSame('paid', $result->localStatus);
        $this->assertSame('paid', $result->gatewayStatus);
        $this->assertSame('transitioned_to_paid', $result->actionTaken);

        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
    }

    #[Test]
    public function failed_reconciliation_transitions_processing_to_failed(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_456',
        ]);

        $this->gateway->method('getPayoutStatus')
            ->willReturn(['id' => 'PAYOUT_456', 'status' => 'failed', 'amount' => 0.00, 'currency' => 'INR']);

        $result = $this->service->reconcileSettlement($settlement);

        $this->assertTrue($result->reconciled);
        $this->assertSame('failed', $result->localStatus);
        $this->assertSame('failed', $result->gatewayStatus);
        $this->assertSame('transitioned_to_failed', $result->actionTaken);

        $settlement->refresh();
        $this->assertSame('failed', $settlement->status);
    }

    #[Test]
    public function cancelled_reconciliation_transitions_processing_to_cancelled(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_789',
        ]);

        $this->gateway->method('getPayoutStatus')
            ->willReturn(['id' => 'PAYOUT_789', 'status' => 'cancelled', 'amount' => 0.00, 'currency' => 'INR']);

        $result = $this->service->reconcileSettlement($settlement);

        $this->assertTrue($result->reconciled);
        $this->assertSame('cancelled', $result->localStatus);
        $this->assertSame('cancelled', $result->gatewayStatus);
        $this->assertSame('transitioned_to_cancelled', $result->actionTaken);

        $settlement->refresh();
        $this->assertSame('cancelled', $settlement->status);
    }

    #[Test]
    public function already_paid_settlement_is_skipped(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'status' => 'paid',
            'gateway_reference' => 'PAYOUT_123',
        ]);

        $result = $this->service->reconcileSettlement($settlement);

        $this->assertFalse($result->reconciled);
        $this->assertSame('paid', $result->localStatus);
        $this->assertSame('already_terminal', $result->actionTaken);
    }

    #[Test]
    public function missing_gateway_reference_is_skipped(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => null,
        ]);

        $result = $this->service->reconcileSettlement($settlement);

        $this->assertFalse($result->reconciled);
        $this->assertSame('processing', $result->localStatus);
        $this->assertSame('missing_gateway_reference', $result->actionTaken);
    }

    #[Test]
    public function gateway_timeout_returns_retryable_failure(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_123',
        ]);

        $this->gateway->method('getPayoutStatus')
            ->willThrowException(new TimeoutException('Gateway timeout'));

        $result = $this->service->reconcileSettlement($settlement);

        $this->assertFalse($result->reconciled);
        $this->assertSame('failed', $result->actionTaken);
        $this->assertTrue($result->metadata['retryable']);
    }

    #[Test]
    public function gateway_permanent_failure_returns_non_retryable_failure(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_123',
        ]);

        $this->gateway->method('getPayoutStatus')
            ->willThrowException(new PermanentFailureException('Permanent failure'));

        $result = $this->service->reconcileSettlement($settlement);

        $this->assertFalse($result->reconciled);
        $this->assertSame('failed', $result->actionTaken);
        $this->assertFalse($result->metadata['retryable']);
    }

    #[Test]
    public function unknown_gateway_status_is_skipped(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_123',
        ]);

        $this->gateway->method('getPayoutStatus')
            ->willReturn(['id' => 'PAYOUT_123', 'status' => 'unknown', 'amount' => 0.00, 'currency' => 'INR']);

        $result = $this->service->reconcileSettlement($settlement);

        $this->assertFalse($result->reconciled);
        $this->assertSame('processing', $result->localStatus);
        $this->assertSame('unknown_gateway_status', $result->actionTaken);
    }

    #[Test]
    public function duplicate_reconciliation_is_idempotent(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_123',
        ]);

        $this->gateway->method('getPayoutStatus')
            ->willReturn(['id' => 'PAYOUT_123', 'status' => 'paid', 'amount' => 500.00, 'currency' => 'INR']);

        $result1 = $this->service->reconcileSettlement($settlement);
        $result2 = $this->service->reconcileSettlement($settlement);

        $this->assertTrue($result1->reconciled);
        $this->assertFalse($result2->reconciled);
        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);
    }

    #[Test]
    public function batch_reconciliation_processes_multiple_settlements(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement1 = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_1',
        ]);

        $settlement2 = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_2',
        ]);

        $this->gateway->method('getPayoutStatus')
            ->willReturnCallback(function ($ref) {
                if ($ref === 'PAYOUT_1') {
                    return ['id' => 'PAYOUT_1', 'status' => 'paid', 'amount' => 500.00, 'currency' => 'INR'];
                }

                return ['id' => 'PAYOUT_2', 'status' => 'failed', 'amount' => 0.00, 'currency' => 'INR'];
            });

        $results = $this->service->reconcile();

        $this->assertCount(2, $results);
        $this->assertTrue($results[0]->reconciled);
        $this->assertTrue($results[1]->reconciled);
    }
}
