<?php

namespace Tests\Feature;

use App\Mail\DonationReceiptMail;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use App\Services\DonationReceiptService;
use App\Services\Payment\PaymentVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ReceiptIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $donor;

    private User $admin;

    private Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->donor = User::factory()->create(['role' => 'donor']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        $category = Category::create([
            'name' => 'Medical',
            'slug' => 'medical',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);

        $this->campaign = Campaign::create([
            'user_id' => User::factory()->create(['role' => 'ngo'])->id,
            'category_id' => $category->id,
            'title' => 'Integration Test Campaign',
            'slug' => 'integration-test-campaign',
            'description' => 'Campaign for integration testing.',
            'goal_amount' => 500000,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    private function makeDonation(string $status = 'pending', array $overrides = []): Donation
    {
        $donation = Donation::create(array_merge([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->donor->id,
            'donor_name' => 'Test Donor',
            'donor_email' => 'donor@integration.test',
            'donation_type' => 'money',
            'total_amount' => 100.00,
            'original_amount' => 100.00,
            'discount_amount' => 0.00,
            'platform_fee' => 5.00,
            'net_amount' => 95.00,
            'payment_gateway' => 'razorpay',
            'currency' => 'INR',
            'receipt_number' => 'INTEGRATION-'.uniqid(),
            'payment_status' => 'pending',
        ], $overrides));

        // Guarded columns must be assigned directly, not mass-assigned.
        $donation->payment_status = $status;
        $donation->payment_id = $overrides['payment_id'] ?? null;
        $donation->is_refunded = $overrides['is_refunded'] ?? false;
        $donation->paid_at = $overrides['paid_at'] ?? null;
        $donation->save();

        return $donation;
    }

    private function signedDownloadUrl(Donation $donation, int $hoursFromNow = 24): string
    {
        return URL::temporarySignedRoute(
            'donations.receipt.download',
            now()->addHours($hoursFromNow),
            ['donation' => $donation->id]
        );
    }

    // =========================================================================
    // SOFT-DELETED DONATION
    // =========================================================================

    public function test_soft_deleted_donation_receipt_is_unavailable(): void
    {
        $donation = $this->makeDonation('completed', [
            'paid_at' => now(),
        ]);
        $donation->delete();

        $this->get($this->signedDownloadUrl($donation))->assertNotFound();
    }

    public function test_soft_deleted_donation_receipt_history_is_forbidden(): void
    {
        $donation = $this->makeDonation('completed', [
            'paid_at' => now(),
        ]);
        $donation->delete();

        $this->actingAs($this->donor)
            ->get(route('donation.receipt', $donation))
            ->assertNotFound();
    }

    // =========================================================================
    // DUPLICATE BROWSER VERIFICATION IDEMPOTENCY
    // =========================================================================

    public function test_duplicate_browser_verification_does_not_double_credit(): void
    {
        $donation = $this->makeDonation('pending', [
            'order_id' => 'order_duplicate_test',
        ]);

        $mockGateway = new class extends \App\Gateways\RazorpayGateway {
            public function __construct() {}

            public function verifyPaymentSignature(array $payload): void {}
            public function verifyPaymentDetails(string $paymentId, string $orderId, float $amount, string $currency): void {}
        };

        $service = new PaymentVerificationService($mockGateway, app(\App\Services\Payment\DonationCompletionService::class));

        $request = request()->merge([
            'razorpay_order_id' => 'order_duplicate_test',
            'razorpay_payment_id' => 'pay_duplicate_test',
            'razorpay_signature' => 'sig_duplicate_test',
            'donation_id' => $donation->id,
        ]);

        $response1 = $service->verifyPayment($request);
        $this->assertSame(true, json_decode($response1->getContent())->success);

        $response2 = $service->verifyPayment($request);
        $this->assertSame(true, json_decode($response2->getContent())->success);

        $walletCount = \App\Models\WalletTransaction::where('reference_type', Donation::class)
            ->where('reference_id', $donation->id)
            ->count();

        $this->assertSame(1, $walletCount, 'Duplicate verification should not create duplicate wallet transactions');
    }

    // =========================================================================
    // CONFIG-DRIVEN AMOUNTS
    // =========================================================================

    public function test_config_driven_min_amount_is_enforced(): void
    {
        Config::set('services.donation.min_amount', 10);

        $response = $this->actingAs($this->donor)
            ->post(route('donate.redirect', $this->campaign->id), [
                'amount' => 5,
            ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Minimum donation is ₹10', session('error'));
    }

    public function test_config_driven_max_amount_is_enforced(): void
    {
        Config::set('services.donation.max_amount', 100);

        $response = $this->actingAs($this->donor)
            ->post(route('donate.redirect', $this->campaign->id), [
                'amount' => 200,
            ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Maximum donation is ₹100', session('error'));
    }

    // =========================================================================
    // CONFIG-DRIVEN PLATFORM FEE
    // =========================================================================

    public function test_config_driven_platform_fee_affects_calculation(): void
    {
        Config::set('services.donation.platform_fee_percent', 10.0);

        $service = app(\App\Services\Payment\PaymentOrderService::class);
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('calculateFees');
        $method->setAccessible(true);

        $result = $method->invoke($service, 100.00);

        $this->assertSame(10.00, $result['platform_fee']);
        $this->assertSame(90.00, $result['net_amount']);
    }

    // =========================================================================
    // CONFIG-DRIVEN CURRENCY
    // =========================================================================

    public function test_config_driven_currency_used_in_order_creation(): void
    {
        Config::set('services.donation.currency', 'USD');

        $mockGateway = $this->createMock(\App\Gateways\RazorpayGateway::class);
        $mockGateway->method('createOrder')
            ->willReturn([
                'id' => 'order_currency_test',
                'amount' => 10000,
                'currency' => Config::get('services.donation.currency'),
                'status' => 'created',
                'receipt' => 'rcpt_currency_test',
            ]);

        $result = $mockGateway->createOrder($this->campaign, $this->donor, 100.00, []);

        $this->assertSame('USD', $result['currency']);
    }

    // =========================================================================
    // RECEIPT URL TTL CONFIG
    // =========================================================================

    public function test_config_driven_receipt_url_ttl(): void
    {
        Config::set('services.donation.receipt_url_ttl_hours', 48);

        $donation = $this->makeDonation('completed', [
            'paid_at' => now(),
        ]);
        $url = app(DonationReceiptService::class)->receiptDownloadUrl($donation);

        $this->assertStringContainsString('expires=', $url);

        $expires = \Carbon\Carbon::createFromTimestamp(parse_url($url, PHP_URL_QUERY) ?: '');
        $this->assertTrue($expires->greaterThan(now()->addHours(47)));
    }

    // =========================================================================
    // ADMIN ACCESS TO RECEIPTS
    // =========================================================================

    public function test_admin_can_download_any_receipt(): void
    {
        $donation = $this->makeDonation('completed', [
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get($this->signedDownloadUrl($donation));

        $response->assertOk();
    }

    public function test_admin_can_view_receipt_history(): void
    {
        $donation = $this->makeDonation('completed', [
            'paid_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->get(route('donation.receipt', $donation))
            ->assertOk();
    }

    // =========================================================================
    // RECEIPT NUMBER UNIQUENESS
    // =========================================================================

    public function test_receipt_number_is_unique_across_donations(): void
    {
        $this->makeDonation('pending', ['receipt_number' => 'UNIQUE-RCPT-001']);
        $this->makeDonation('pending', ['receipt_number' => 'UNIQUE-RCPT-002']);

        $this->assertNotEquals(
            Donation::where('receipt_number', 'UNIQUE-RCPT-001')->value('receipt_number'),
            Donation::where('receipt_number', 'UNIQUE-RCPT-002')->value('receipt_number')
        );

        $this->expectException(\Illuminate\Database\QueryException::class);

        $this->makeDonation('pending', ['receipt_number' => 'UNIQUE-RCPT-001']);
    }

    // =========================================================================
    // PAYMENT ID UNIQUENESS
    // =========================================================================

    public function test_payment_id_is_unique_across_donations(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $this->makeDonation('pending', ['payment_id' => 'pay_unique_test']);
        $this->makeDonation('pending', ['payment_id' => 'pay_unique_test']);
    }
}
