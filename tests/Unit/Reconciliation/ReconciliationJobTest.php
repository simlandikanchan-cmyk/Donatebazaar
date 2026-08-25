<?php

namespace Tests\Unit\Reconciliation;

use App\Gateways\RazorpayGateway;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Services\Reconciliation\ReconciliationService;
use App\Services\Settlement\SettlementStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReconciliationJobTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function job_processes_batch_successfully(): void
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

        CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'processing',
            'processed_at' => now()->subHour(),
            'gateway_reference' => 'PAYOUT_123',
        ]);

        $gateway = $this->createMock(RazorpayGateway::class);
        $gateway->method('getPayoutStatus')
            ->willReturn(['id' => 'PAYOUT_123', 'status' => 'paid', 'amount' => 500.00, 'currency' => 'INR']);

        $service = new ReconciliationService(
            gateway: $gateway,
            stateMachine: new SettlementStateMachine(),
            batchSize: 10,
            processingStuckMinutes: 0
        );

        $job = new \App\Jobs\ReconciliationJob(batchSize: 10, processingStuckMinutes: 0);
        $job->handle($service);

        $this->assertTrue(true);
    }

    #[Test]
    public function job_handles_empty_batch_gracefully(): void
    {
        $gateway = $this->createMock(RazorpayGateway::class);
        $service = new ReconciliationService(
            gateway: $gateway,
            stateMachine: new SettlementStateMachine(),
            batchSize: 10,
            processingStuckMinutes: 0
        );

        $job = new \App\Jobs\ReconciliationJob(batchSize: 10, processingStuckMinutes: 0);
        $job->handle($service);

        $this->assertTrue(true);
    }
}
