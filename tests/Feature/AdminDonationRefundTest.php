<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentController;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Razorpay\Api\Errors\Error as RazorpayError;
use Razorpay\Api\Payment as RazorpayPayment;
use ReflectionMethod;
use Tests\TestCase;

class AdminDonationRefundTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->role = 'admin';
        $this->admin->save();

        $this->campaign = Campaign::create([
            'title'       => 'Refund Test Campaign',
            'slug'        => 'refund-test-campaign',
            'user_id'     => $this->admin->id,
            'description' => 'Campaign used by the admin refund feature test.',
            'goal_amount' => 10000.00,
        ]);

        $this->donation = Donation::create([
            'campaign_id'     => $this->campaign->id,
            'user_id'         => $this->admin->id,
            'donor_name'      => 'Jane Donor',
            'donor_email'     => 'jane@example.com',
            'donation_type'   => 'money',
            'total_amount'    => 500.00,
            'platform_fee'    => 15.00,
            'net_amount'      => 485.00,
            'currency'        => 'INR',
            'order_id'        => 'order_manual_1',
        ]);

        // payment_id / payment_status / is_refunded are guarded on the model,
        // so set them via direct assignment (mirrors how PaymentController persists them).
        $this->donation->payment_id = 'pay_manual_1';
        $this->donation->payment_status = 'completed';
        $this->donation->is_refunded = false;
        $this->donation->save();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Mock the Razorpay Payment entity so no real network call is made.
     * $refundId = the gateway refund id the SDK should return (or null to throw).
     * The controller does `$api->payment->fetch($id)->refund($attrs)`; Api lazily
     * instantiates `Razorpay\Api\Payment` via __get, so overloading that class
     * is enough to intercept the whole chain.
     */
    private function mockRazorpay(?string $refundId, bool $shouldThrow = false): void
    {
        $paymentMock = Mockery::mock('overload:' . RazorpayPayment::class);
        $paymentMock->shouldReceive('fetch')->andReturnSelf();

        if ($shouldThrow) {
            $paymentMock->shouldReceive('refund')
                ->andThrow(new RazorpayError('gateway declined', 'BAD_REQUEST_ERROR', 400));
        } else {
            $entity = new \stdClass();
            $entity->id = $refundId;
            $paymentMock->shouldReceive('refund')->andReturn($entity);
        }
    }

    #[Test]
    public function admin_can_view_donations_list(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.donations.index'))
            ->assertOk()
            ->assertSee('Donations')
            ->assertSee('Jane Donor');
    }

    #[Test]
    public function admin_can_view_donation_detail(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.donations.show', $this->donation))
            ->assertOk()
            ->assertSee('Refund Test Campaign')
            ->assertSee('pay_manual_1');
    }

    #[Test]
    public function admin_can_refund_a_completed_donation(): void
    {
        $this->mockRazorpay('rfnd_abc123');

        $this->actingAs($this->admin)
            ->post(route('admin.donations.refund', $this->donation), ['reason' => 'duplicate charge'])
            ->assertRedirect(route('admin.donations.show', $this->donation))
            ->assertSessionHas('success');

        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);
        $this->assertEquals('refunded', $this->donation->payment_status);
        $this->assertNotNull($this->donation->refunded_at);

        $refund = Refund::where('donation_id', $this->donation->id)->first();
        $this->assertNotNull($refund);
        $this->assertEquals('processed', $refund->status);
        $this->assertEquals('rfnd_abc123', $refund->gateway_refund_id);
        $this->assertEquals(500.00, (float) $refund->amount);
    }

    #[Test]
    public function refund_is_rejected_for_non_completed_donation(): void
    {
        $this->donation->payment_status = 'pending';
        $this->donation->save();

        $this->actingAs($this->admin)
            ->post(route('admin.donations.refund', $this->donation))
            ->assertRedirect(route('admin.donations.show', $this->donation))
            ->assertSessionHas('error');

        $this->donation->refresh();
        $this->assertFalse($this->donation->is_refunded);
        $this->assertEquals('pending', $this->donation->payment_status);
        $this->assertDatabaseMissing('refunds', ['donation_id' => $this->donation->id]);
    }

    #[Test]
    public function refund_is_rejected_for_already_refunded_donation(): void
    {
        $this->donation->payment_status = 'refunded';
        $this->donation->is_refunded = true;
        $this->donation->save();

        $this->actingAs($this->admin)
            ->post(route('admin.donations.refund', $this->donation))
            ->assertRedirect(route('admin.donations.show', $this->donation))
            ->assertSessionHas('error');

        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);
        $this->assertEquals('refunded', $this->donation->payment_status);
        $this->assertDatabaseMissing('refunds', ['donation_id' => $this->donation->id, 'status' => 'processed']);
    }

    #[Test]
    public function gateway_failure_does_not_modify_donation_but_logs_failed_refund(): void
    {
        $this->mockRazorpay(null, shouldThrow: true);

        $this->actingAs($this->admin)
            ->post(route('admin.donations.refund', $this->donation))
            ->assertRedirect(route('admin.donations.show', $this->donation))
            ->assertSessionHas('error');

        $this->donation->refresh();
        $this->assertFalse($this->donation->is_refunded);
        $this->assertEquals('completed', $this->donation->payment_status);

        $refund = Refund::where('donation_id', $this->donation->id)->first();
        $this->assertNotNull($refund);
        $this->assertEquals('failed', $refund->status);
        $this->assertNull($refund->gateway_refund_id);
    }

    #[Test]
    public function admin_refund_then_webhook_is_idempotent_no_double_processing(): void
    {
        // 1) Admin triggers the refund (creates Refund + flips donation flags).
        $this->mockRazorpay('rfnd_shared_1');

        $this->actingAs($this->admin)
            ->post(route('admin.donations.refund', $this->donation))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);
        $this->assertEquals(1, Refund::where('donation_id', $this->donation->id)->count());

        // 2) Simulate the refund.processed webhook firing for the SAME gateway_refund_id.
        $payload = [
            'payload' => [
                'refund' => [
                    'entity' => [
                        'id'         => 'rfnd_shared_1',
                        'payment_id' => $this->donation->payment_id,
                        'amount'     => (int) round($this->donation->total_amount * 100),
                    ],
                ],
            ],
        ];

        $controller = new PaymentController();
        $method = new ReflectionMethod($controller, 'handleRefundProcessed');
        $method->setAccessible(true);
        $method->invoke($controller, $payload);

        // 3) Still exactly one refund, donation still flipped once, no double-processing.
        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);
        $this->assertEquals('refunded', $this->donation->payment_status);

        $refunds = Refund::where('donation_id', $this->donation->id)->get();
        $this->assertCount(1, $refunds);
        $this->assertEquals('processed', $refunds->first()->status);
        $this->assertEquals('rfnd_shared_1', $refunds->first()->gateway_refund_id);
    }
}
