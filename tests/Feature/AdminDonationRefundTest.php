<?php

namespace Tests\Feature;

use App\Gateways\RazorpayGateway;
use App\Http\Controllers\PaymentController;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Refund;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Payment\PaymentWebhookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Razorpay\Api\Errors\Error as RazorpayError;
use ReflectionMethod;
use Tests\TestCase;

class AdminDonationRefundTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.razorpay.key', 'rzp_test_key');
        Config::set('services.razorpay.secret', 'rzp_test_secret');

        $this->admin = User::factory()->create();
        $this->admin->role = 'admin';
        $this->admin->save();

        $this->campaign = Campaign::create([
            'title' => 'Refund Test Campaign',
            'slug' => 'refund-test-campaign',
            'user_id' => $this->admin->id,
            'description' => 'Campaign used by the admin refund feature test.',
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
            'order_id' => 'order_manual_1',
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
            'balance' => 0.00,
            'reserved_balance' => 0.00,
            'currency' => 'INR',
        ]);

        Wallet::where('owner_id', $this->admin->id)->update(['balance' => '1000.00']);
    }

    private function mockRazorpayGateway(?string $refundId, bool $shouldThrow = false): void
    {
        $mock = $this->createMock(RazorpayGateway::class);

        if ($shouldThrow) {
            $mock->method('initiateRefund')
                ->willThrowException(new RazorpayError('gateway declined', 'BAD_REQUEST_ERROR', 400));
        } else {
            $entity = new \stdClass;
            $entity->id = $refundId;
            $mock->method('initiateRefund')
                ->willReturn($entity);
        }

        $this->app->instance(RazorpayGateway::class, $mock);
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
            ->assertSee($this->donation->payment_id);
    }

    #[Test]
    public function admin_can_refund_a_completed_donation(): void
    {
        $this->mockRazorpayGateway('rfnd_abc123');

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
        // Mark the donation as already refunded via a raw update
        // (payment_status / is_refunded are guarded on the model).
        DB::table('donations')->where('id', $this->donation->id)->update([
            'payment_status' => 'refunded',
            'is_refunded' => true,
            'refunded_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.donations.refund', $this->donation))
            ->assertRedirect(route('admin.donations.show', $this->donation))
            ->assertSessionHas('info');

        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);
        $this->assertEquals('refunded', $this->donation->payment_status);
        $this->assertDatabaseMissing('refunds', ['donation_id' => $this->donation->id, 'status' => 'processed']);
    }

    #[Test]
    public function gateway_failure_does_not_modify_donation_but_logs_failed_refund(): void
    {
        $this->mockRazorpayGateway(null, shouldThrow: true);

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
        $this->mockRazorpayGateway('rfnd_shared_1');

        $this->actingAs($this->admin)
            ->post(route('admin.donations.refund', $this->donation))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);
        $this->assertEquals(1, Refund::where('donation_id', $this->donation->id)->count());

        $payload = [
            'payload' => [
                'refund' => [
                    'entity' => [
                        'id' => 'rfnd_shared_1',
                        'payment_id' => $this->donation->payment_id,
                        'amount' => (int) round($this->donation->total_amount * 100),
                    ],
                ],
            ],
        ];

        $controller = app(PaymentWebhookService::class);
        $method = new ReflectionMethod($controller, 'handleRefundProcessed');
        $method->setAccessible(true);
        $method->invoke($controller, $payload);

        $this->donation->refresh();
        $this->assertTrue($this->donation->is_refunded);
        $this->assertEquals('refunded', $this->donation->payment_status);

        $refunds = Refund::where('donation_id', $this->donation->id)->get();
        $this->assertCount(1, $refunds);
        $this->assertEquals('processed', $refunds->first()->status);
        $this->assertEquals('rfnd_shared_1', $refunds->first()->gateway_refund_id);
    }
}
