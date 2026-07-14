<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Refund;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RefundWebhookTest extends TestCase
{
    use RefreshDatabase;

    private const SECRET = 'test-secret-value';

    protected function setUp(): void
    {
        parent::setUp();

        // Point the webhook handler at our fixed test secret (self-contained, no .env change).
        // Must run after parent::setUp() so the app container (and the 'config' binding) exists.
        Config::set('services.razorpay.webhook_secret', self::SECRET);

        // The webhook route lives in the web group (CSRF-enforced). Razorpay cannot
        // send a CSRF token, so disable CSRF for the duration of these tests only.
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    /**
     * Build a completed donation (with a Razorpay payment_id) and its campaign.
     * Returns [campaignId, donationPaymentId].
     */
    private function seedCompletedDonation(float $amount = 5000.00): array
    {
        $user = \App\Models\User::factory()->create();

        $campaignId = DB::table('campaigns')->insertGetId([
            'user_id'     => $user->id,
            'title'       => 'Test Campaign',
            'description' => 'Test description',
            'slug'        => 'test-campaign-' . uniqid(),
            'goal_amount' => 100000.00,
            'raised_amount' => 0.00,
            'campaign_state' => 'active',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $paymentId = 'pay_' . uniqid();

        DB::table('donations')->insert([
            'campaign_id'    => $campaignId,
            'user_id'        => $user->id,
            'donation_type'  => 'money',
            'total_amount'   => $amount,
            'currency'       => 'INR',
            'payment_status' => 'completed',
            'is_refunded'    => 0,
            'payment_id'     => $paymentId,
            'order_id'       => 'order_' . uniqid(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return [$campaignId, $paymentId];
    }

    private function sign(array $payload): string
    {
        $body = json_encode($payload);

        return hash_hmac('sha256', $body, self::SECRET);
    }

    private function postWebhook(array $payload, ?string $signature = null): \Illuminate\Testing\TestResponse
    {
        $body = json_encode($payload);
        $sig  = $signature ?? $this->sign($payload);

        return $this->call(
            'POST',
            '/payment/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE'             => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => $sig,
            ],
            $body
        );
    }

    private function refundProcessedPayload(string $paymentId, string $refundId, int $amountPaise): array
    {
        return [
            'event'  => 'refund.processed',
            'payload' => [
                'refund' => [
                    'entity' => [
                        'id'        => $refundId,
                        'payment_id' => $paymentId,
                        'amount'     => $amountPaise,
                    ],
                ],
            ],
        ];
    }

    public function test_refund_processed_flips_status_creates_record_and_decrements_raised_amount(): void
    {
        [$campaignId, $paymentId] = $this->seedCompletedDonation(5000.00);

        // The INSERT trigger should have raised the campaign by 5000.
        $this->assertEquals('5000.00', DB::table('campaigns')->where('id', $campaignId)->value('raised_amount'));

        $refundId = 'rfnd_' . uniqid();
        $response = $this->postWebhook($this->refundProcessedPayload($paymentId, $refundId, 500000));

        $response->assertStatus(200);

        $donation = DB::table('donations')->where('payment_id', $paymentId)->first();
        $this->assertEquals('refunded', $donation->payment_status);
        $this->assertEquals(1, $donation->is_refunded);
        $this->assertNotNull($donation->refunded_at);

        $this->assertDatabaseHas('refunds', [
            'gateway_refund_id' => $refundId,
            'status'            => 'processed',
            'amount'            => 5000.00,
            'donation_id'       => $donation->id,
        ]);

        // The UPDATE trigger should have decremented raised_amount back to 0.
        $this->assertEquals('0.00', DB::table('campaigns')->where('id', $campaignId)->value('raised_amount'));
    }

    public function test_refund_failed_marks_refund_failed_but_leaves_donation_untouched(): void
    {
        [$campaignId, $paymentId] = $this->seedCompletedDonation(5000.00);

        $refundId = 'rfnd_' . uniqid();
        $payload  = [
            'event'  => 'refund.failed',
            'payload' => [
                'refund' => [
                    'entity' => [
                        'id'        => $refundId,
                        'payment_id' => $paymentId,
                        'amount'     => 500000,
                    ],
                ],
            ],
        ];

        $response = $this->postWebhook($payload);
        $response->assertStatus(200);

        $donation = DB::table('donations')->where('payment_id', $paymentId)->first();
        $this->assertEquals('completed', $donation->payment_status); // untouched
        $this->assertEquals(0, $donation->is_refunded);

        $this->assertDatabaseHas('refunds', [
            'gateway_refund_id' => $refundId,
            'status'            => 'failed',
        ]);

        // raised_amount must NOT change on a failed refund.
        $this->assertEquals('5000.00', DB::table('campaigns')->where('id', $campaignId)->value('raised_amount'));
    }

    public function test_replay_idempotency_does_not_double_create_or_double_decrement(): void
    {
        [$campaignId, $paymentId] = $this->seedCompletedDonation(5000.00);
        $refundId = 'rfnd_' . uniqid();

        $this->postWebhook($this->refundProcessedPayload($paymentId, $refundId, 500000))->assertStatus(200);
        $this->postWebhook($this->refundProcessedPayload($paymentId, $refundId, 500000))->assertStatus(200);

        $this->assertEquals(1, DB::table('refunds')->where('gateway_refund_id', $refundId)->count());
        $this->assertEquals('0.00', DB::table('campaigns')->where('id', $campaignId)->value('raised_amount'));
    }

    public function test_guard_non_completed_donation_is_not_refunded(): void
    {
        $user = \App\Models\User::factory()->create();
        $campaignId = DB::table('campaigns')->insertGetId([
            'user_id'     => $user->id,
            'title'       => 'Pending Campaign',
            'description' => 'desc',
            'slug'        => 'pending-campaign-' . uniqid(),
            'goal_amount' => 100000.00,
            'raised_amount' => 0.00,
            'campaign_state' => 'active',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        $paymentId = 'pay_' . uniqid();
        DB::table('donations')->insert([
            'campaign_id'    => $campaignId,
            'user_id'        => $user->id,
            'donation_type'  => 'money',
            'total_amount'   => 5000.00,
            'currency'       => 'INR',
            'payment_status' => 'pending',
            'is_refunded'    => 0,
            'payment_id'     => $paymentId,
            'order_id'       => 'order_' . uniqid(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $refundId = 'rfnd_' . uniqid();
        $this->postWebhook($this->refundProcessedPayload($paymentId, $refundId, 500000))->assertStatus(200);

        $donation = DB::table('donations')->where('payment_id', $paymentId)->first();
        $this->assertEquals('pending', $donation->payment_status);
        $this->assertEquals(0, DB::table('refunds')->count());
        $this->assertEquals('0.00', DB::table('campaigns')->where('id', $campaignId)->value('raised_amount'));
    }

    public function test_bad_signature_is_rejected_without_side_effects(): void
    {
        [$campaignId, $paymentId] = $this->seedCompletedDonation(5000.00);
        $refundId = 'rfnd_' . uniqid();

        $payload = $this->refundProcessedPayload($paymentId, $refundId, 500000);
        $response = $this->call(
            'POST',
            '/payment/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE'             => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => 'deadbeef',
            ],
            json_encode($payload)
        );

        $response->assertStatus(400);

        $donation = DB::table('donations')->where('payment_id', $paymentId)->first();
        $this->assertEquals('completed', $donation->payment_status);
        $this->assertEquals(0, DB::table('refunds')->count());
    }
}
