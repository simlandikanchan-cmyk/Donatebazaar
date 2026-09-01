<?php

namespace Tests\Unit\Gateway;

use App\Exceptions\GatewayException;
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
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError;
use Razorpay\Api\Errors\GatewayError;
use Razorpay\Api\Errors\ServerError;
use Razorpay\Api\Transfer;
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
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'net_amount' => 500.00,
        ]);

        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function create($attributes = []) {
                $entity = new class([]) extends Transfer {
                    public $id;
                    public $status = 'processed';
                    public $amount = 50000;
                    public $currency = 'INR';
                    public function __construct(array $data) {
                        $this->id = 'trans_ABCDEFG';
                    }
                    public function toArray(): array {
                        return [
                            'id' => $this->id,
                            'status' => $this->status,
                            'amount' => $this->amount,
                            'currency' => $this->currency,
                            'entity' => 'transfer',
                        ];
                    }
                };
                return $entity;
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );

        $result = $gateway->initiatePayout($org, 500.00, $settlement);

        $this->assertIsArray($result);
        $this->assertStringStartsWith('trans_', $result['gateway_reference']);
        $this->assertSame('processed', $result['provider_status']);
        $this->assertSame(500.00, $result['metadata']['amount']);
    }

    #[Test]
    public function timeout_throws_timeout_exception(): void
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
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'net_amount' => 500.00,
        ]);

        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function create($attributes = []) {
                throw new \WpOrg\Requests\Exception\Transport\Curl('Connection timed out', 'cURLEasy', null, 28);
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );

        $this->expectException(TimeoutException::class);
        $this->expectExceptionMessage('Gateway timeout: unable to process payout.');

        $gateway->initiatePayout($org, 500.00, $settlement);
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
    public function missing_fund_account_throws_permanent_failure(): void
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

        $this->expectException(PermanentFailureException::class);
        $this->expectExceptionMessage('Organization has no linked Razorpay fund account');

        $this->gateway->initiatePayout($org, 500.00, $settlement);
    }

    #[Test]
    public function get_payout_status_returns_status_from_provider(): void
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function fetch($id) {
                $entity = new class([]) extends Transfer {
                    public $id = 'trans_ABCDEFG';
                    public $status = 'processed';
                    public $amount = 50000;
                    public $currency = 'INR';
                    public function toArray(): array {
                        return [
                            'id' => $this->id,
                            'status' => $this->status,
                            'amount' => $this->amount,
                            'currency' => $this->currency,
                            'entity' => 'transfer',
                        ];
                    }
                };
                $entity->id = $id;
                return $entity;
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );

        $status = $gateway->getPayoutStatus('trans_ABCDEFG');

        $this->assertSame('trans_ABCDEFG', $status['id']);
        $this->assertSame('processed', $status['status']);
        $this->assertSame(500.00, $status['amount']);
        $this->assertSame('INR', $status['currency']);
    }

    #[Test]
    public function get_payout_status_throws_temporary_failure_on_gateway_error(): void
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function fetch($id) {
                throw new GatewayError('Provider temporarily unavailable', 502, 502);
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );

        $this->expectException(TemporaryFailureException::class);
        $this->expectExceptionMessage('Provider unable to retrieve payout status');

        $gateway->getPayoutStatus('trans_ABCDEFG');
    }

    #[Test]
    public function get_payout_status_throws_permanent_failure_on_invalid_reference(): void
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function fetch($id) {
                throw new BadRequestError('No such transfer', 'bad_request_error', 400);
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );

        $this->expectException(PermanentFailureException::class);
        $this->expectExceptionMessage('Invalid payout reference');

        $gateway->getPayoutStatus('invalid_ref');
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
        $signature = 'invalid_signature';

        $this->assertFalse($this->gateway->validateWebhook($payload, $signature, $secret));
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

    #[Test]
    public function create_order_with_notes_returns_order_array(): void
    {
        $mockApi = $this->createMock(\Razorpay\Api\Api::class);
        $mockApi->order = new class {
            public function create(array $data): object {
                return new class($data) {
                    private array $data;
                    public function __construct(array $data) { $this->data = $data; }
                    public function toArray(): array { return $this->data; }
                };
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );

        $result = $gateway->createOrderWithNotes(500.00, ['type' => 'gift_card'], 'gc_test_123');

        $this->assertIsArray($result);
        $this->assertSame('INR', $result['currency']);
        $this->assertSame(50000, $result['amount']);
        $this->assertSame('gc_test_123', $result['receipt']);
    }

    #[Test]
    public function create_order_with_notes_generates_receipt_when_not_provided(): void
    {
        $mockApi = $this->createMock(\Razorpay\Api\Api::class);
        $mockApi->order = new class {
            public function create(array $data): object {
                return new class($data) {
                    private array $data;
                    public function __construct(array $data) { $this->data = $data; }
                    public function toArray(): array { return $this->data; }
                };
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );

        $result = $gateway->createOrderWithNotes(500.00, ['type' => 'gift_card']);

        $this->assertIsArray($result);
        $this->assertNotNull($result['receipt']);
    }
}
