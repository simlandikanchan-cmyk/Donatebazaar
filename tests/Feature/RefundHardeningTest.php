<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Refund;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Payment\RefundService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Razorpay\Api\Errors\Error as RazorpayError;
use Razorpay\Api\Payment as RazorpayPayment;
use Tests\TestCase;

class RefundHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Campaign $campaign;

    protected Donation $donation;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.razorpay.key', 'rzp_test_key');
        Config::set('services.razorpay.secret', 'rzp_test_secret');

        $this->admin = User::factory()->create(['role' => 'admin']);

        $this->campaign = Campaign::create([
            'title' => 'Refund Hardening Campaign',
            'slug' => 'refund-hardening-campaign',
            'user_id' => $this->admin->id,
            'description' => 'Campaign used by the refund hardening test.',
            'goal_amount' => 10000.00,
        ]);

        $paymentId = 'pay_'.str_repeat('A', 15);

        $this->donation = Donation::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->admin->id,
            'donor_name' => 'Jane Donor',
            'donor_email' => 'jane@example.com',
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 15.00,
            'net_amount' => 485.00,
            'currency' => 'INR',
            'order_id' => 'order_harden_1',
        ]);

        DB::table('donations')->where('id', $this->donation->id)->update([
            'payment_id' => $paymentId,
            'payment_status' => 'completed',
            'is_refunded' => false,
        ]);

        Wallet::create([
            'owner_type' => User::class,
            'owner_id' => $this->admin->id,
            'user_id' => $this->admin->id,
            'balance' => 1000.00,
            'reserved_balance' => 0.00,
            'currency' => 'INR',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function mockRazorpay(?string $refundId, bool $shouldThrow = false, int $times = 1): void
    {
        $paymentMock = Mockery::mock('overload:'.RazorpayPayment::class);
        $paymentMock->shouldReceive('fetch')->andReturnSelf();

        if ($shouldThrow) {
            $paymentMock->shouldReceive('refund')
                ->times($times)
                ->andThrow(new RazorpayError('gateway declined', 'BAD_REQUEST_ERROR', 400));
        } else {
            $entity = new \stdClass;
            $entity->id = $refundId;
            $paymentMock->shouldReceive('refund')->times($times)->andReturn($entity);
        }
    }

    #[Test]
    public function successful_refund_marks_refund_processed_and_reverses_wallet(): void
    {
        $this->mockRazorpay('rfnd_success_1');

        $service = app(RefundService::class);
        $refund = $service->processAdminRefund($this->donation, $this->admin, 'duplicate charge');

        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);
        $this->assertEquals('refunded', $this->donation->payment_status);

        $refund->refresh();
        $this->assertEquals(Refund::STATUS_PROCESSED, $refund->status);
        $this->assertNotNull($refund->processed_at);
        $this->assertNull($refund->notes);

        // Org wallet was debited by the donation net amount.
        $wallet = Wallet::where('owner_id', $this->admin->id)->first();
        $this->assertEquals('515.00', $wallet->balance);
    }

    #[Test]
    public function admin_refund_persists_a_stable_gateway_idempotency_key(): void
    {
        $this->mockRazorpay('rfnd_stable_1');

        $service = app(RefundService::class);
        $service->processAdminRefund($this->donation, $this->admin, 'duplicate charge');

        $this->donation->refresh();
        $this->assertNotNull($this->donation->refund_idempotency_key);
        $this->assertMatchesRegularExpression('/^ref_[A-Za-z0-9]{20,}$/', $this->donation->refund_idempotency_key);

        // A second refund attempt must reuse the SAME key (no duplicate gateway refund).
        $keyAfterFirst = $this->donation->refund_idempotency_key;

        // Reset refund flags to simulate a retry after a transient DB failure
        // (gateway already refunded, donation not yet marked refunded).
        DB::table('donations')->where('id', $this->donation->id)->update([
            'payment_status' => 'completed',
            'is_refunded' => false,
        ]);

        $this->mockRazorpay('rfnd_stable_1', false, 2);
        $service->processAdminRefund($this->donation, $this->admin, 'retry');

        $this->donation->refresh();
        $this->assertEquals($keyAfterFirst, $this->donation->refund_idempotency_key);

        // Exactly one refund row for this gateway refund id.
        $this->assertEquals(1, Refund::where('gateway_refund_id', 'rfnd_stable_1')->count());
    }

    #[Test]
    public function wallet_debit_failure_leaves_refund_in_reversal_pending_state(): void
    {
        $this->mockRazorpay('rfnd_pending_1');

        // Starve the owner wallet so the reversal debit must fail.
        Wallet::where('owner_id', $this->admin->id)->update(['balance' => 0.00]);

        $service = app(RefundService::class);
        $service->processAdminRefund($this->donation, $this->admin, 'duplicate charge');

        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);

        $refund = Refund::where('donation_id', $this->donation->id)->first();
        $this->assertNotNull($refund);
        // The refund must NOT be marked successfully processed while the
        // required wallet accounting failed — it enters the recovery state.
        $this->assertEquals(Refund::STATUS_REVERSAL_PENDING, $refund->status);
        $this->assertNull($refund->processed_at);
        $this->assertNotNull($refund->notes);
        $this->assertStringContainsString('Wallet reversal failed', $refund->notes);
    }

    #[Test]
    public function retry_reuses_existing_refund_and_does_not_call_gateway_again(): void
    {
        // Gateway is called exactly ONCE across both attempts.
        $this->mockRazorpay('rfnd_retry_1', false, 1);

        // First attempt: wallet is starved → reversal fails → reversal_pending.
        Wallet::where('owner_id', $this->admin->id)->update(['balance' => 0.00]);

        $service = app(RefundService::class);
        $service->processAdminRefund($this->donation, $this->admin, 'duplicate charge');

        $refund = Refund::where('donation_id', $this->donation->id)->first();
        $this->assertEquals(Refund::STATUS_REVERSAL_PENDING, $refund->status);

        // Second attempt: funds available. Retry must NOT re-call the gateway —
        // the existing gateway_refund_id short-circuits straight to the reversal.
        Wallet::where('owner_id', $this->admin->id)->update(['balance' => 1000.00]);

        $retried = $service->processAdminRefund($this->donation, $this->admin, 'retry');

        $retried->refresh();
        $this->assertEquals(Refund::STATUS_PROCESSED, $retried->status);
        $this->assertNull($retried->notes);

        // Gateway refund was initiated exactly once across the whole lifecycle.
        $this->assertEquals(1, Refund::where('gateway_refund_id', 'rfnd_retry_1')->count());
    }

    #[Test]
    public function repeated_webhook_refund_event_is_idempotent(): void
    {
        $payload = [
            'payload' => [
                'refund' => [
                    'entity' => [
                        'id' => 'rfnd_webhook_1',
                        'payment_id' => 'pay_'.str_repeat('A', 15),
                        'amount' => 50000,
                    ],
                ],
            ],
        ];

        $service = app(RefundService::class);
        $service->processWebhookRefund($payload);
        $service->processWebhookRefund($payload);
        $service->processWebhookRefund($payload);

        // Only one refund row regardless of how many times the event is delivered.
        $this->assertEquals(1, Refund::where('gateway_refund_id', 'rfnd_webhook_1')->count());

        $refund = Refund::where('gateway_refund_id', 'rfnd_webhook_1')->first();
        $this->assertEquals(Refund::STATUS_PROCESSED, $refund->status);
    }

    #[Test]
    public function gateway_failure_records_a_failed_refund(): void
    {
        $this->mockRazorpay(null, true);

        $service = app(RefundService::class);

        try {
            $service->processAdminRefund($this->donation, $this->admin, 'duplicate charge');
            $this->fail('Expected RuntimeException on gateway failure.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('Refund failed at the payment gateway', $e->getMessage());
        }

        $refund = Refund::where('donation_id', $this->donation->id)->first();
        $this->assertNotNull($refund);
        $this->assertEquals(Refund::STATUS_FAILED, $refund->status);

        // Donation is untouched when the gateway refund never happened.
        $this->donation->refresh();
        $this->assertFalse($this->donation->is_refunded);
    }
}