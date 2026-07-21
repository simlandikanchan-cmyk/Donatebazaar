<?php

namespace Tests\Unit\Gateway;

use App\Contracts\Gateway\GatewayInterface;
use App\Contracts\Gateway\PayoutResult;
use App\Exceptions\DuplicateRequestException;
use App\Exceptions\GatewayException;
use App\Exceptions\InvalidSignatureException;
use App\Exceptions\PermanentFailureException;
use App\Exceptions\TemporaryFailureException;
use App\Exceptions\TimeoutException;
use App\Gateways\RazorpayGateway;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RazorpayGatewayTest extends TestCase
{
    use RefreshDatabase;

    private RazorpayGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret'
        );
    }

    #[Test]
    public function successful_payout_returns_payout_result_with_reference(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

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
            'net_amount' => 500.00,
        ]);

        $result = $this->gateway->initiatePayout($org, 500.00, $settlement);

        $this->assertInstanceOf(PayoutResult::class, $result);
        $this->assertTrue($result->success);
        $this->assertStringStartsWith('PAYOUT_', $result->gatewayReference);
        $this->assertSame('processed', $result->providerStatus);
        $this->assertFalse($result->retryable);
        $this->assertNull($result->failureReason);
        $this->assertSame(500.00, $result->metadata['amount']);
    }

    #[Test]
    public function timeout_throws_timeout_exception(): void
    {
        $org = Organization::factory()->create(['name' => 'FAIL Org']);
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

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
            'net_amount' => 500.00,
        ]);

        $this->expectException(TimeoutException::class);
        $this->expectExceptionMessage('Gateway timeout: unable to process payout.');

        $this->gateway->initiatePayout($org, 500.00, $settlement);
    }

    #[Test]
    public function missing_verified_account_throws_permanent_failure(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'net_amount' => 500.00,
        ]);

        $this->expectException(PermanentFailureException::class);
        $this->expectExceptionMessage('No verified payout account found for organization.');

        $this->gateway->initiatePayout($org, 500.00, $settlement);
    }

    #[Test]
    public function get_payout_status_returns_paid_status(): void
    {
        $reference = 'PAYOUT_123_test';
        $status = $this->gateway->getPayoutStatus($reference);

        $this->assertSame('paid', $status['status']);
        $this->assertSame($reference, $status['id']);
    }

    #[Test]
    public function get_payout_status_throws_temporary_failure_on_failure_reference(): void
    {
        $this->expectException(TemporaryFailureException::class);
        $this->expectExceptionMessage('Gateway unable to retrieve status.');

        $this->gateway->getPayoutStatus('FAIL_123');
    }

    #[Test]
    public function validate_webhook_returns_true_for_valid_signature(): void
    {
        $payload = '{"event":"payout.processed"}';
        $secret = 'test_webhook_secret';
        $signature = hash_hmac('sha256', $payload, $secret);

        $this->assertTrue($this->gateway->validateWebhook($payload, $signature, $secret));
    }

    #[Test]
    public function validate_webhook_returns_false_for_invalid_signature(): void
    {
        $payload = '{"event":"payout.processed"}';
        $secret = 'test_webhook_secret';

        $this->assertFalse($this->gateway->validateWebhook($payload, 'invalid_signature', $secret));
    }

    #[Test]
    public function parse_webhook_returns_decoded_payload(): void
    {
        $payload = json_encode(['event' => 'payout.processed', 'data' => ['id' => 'PAY_123']]);

        $result = $this->gateway->parseWebhook($payload);

        $this->assertSame('payout.processed', $result['event']);
        $this->assertSame('PAY_123', $result['data']['id']);
    }

    #[Test]
    public function parse_webhook_throws_on_malformed_json(): void
    {
        $this->expectException(GatewayException::class);
        $this->expectExceptionMessage('Malformed webhook payload.');

        $this->gateway->parseWebhook('not valid json');
    }
}
