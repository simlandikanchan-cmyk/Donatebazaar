<?php

namespace Tests\Feature;

use App\Mail\DonationReceiptMail;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use App\Services\DonationReceiptService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class DonationReceiptTest extends TestCase
{
    use RefreshDatabase;

    private User $donor;

    private User $otherUser;

    private Campaign $campaign;

    private Donation $completedDonation;

    private Donation $pendingDonation;

    private Donation $refundedDonation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->donor = User::factory()->create(['role' => 'donor', 'email' => 'donor@receipt.test']);
        $this->otherUser = User::factory()->create(['role' => 'donor', 'email' => 'other@receipt.test']);

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
            'title' => 'Help Little Aarav Fight Cancer',
            'slug' => 'help-little-aarav-fight-cancer',
            'description' => 'A campaign for testing receipts.',
            'goal_amount' => 500000,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);

        $this->completedDonation = $this->makeDonation('RC-2026-TEST0001', 'completed', [
            'donor_name' => 'Priya Sharma',
            'donor_email' => 'donor@receipt.test',
            'user_id' => $this->donor->id,
            'total_amount' => 100.00,
            'original_amount' => 100.00,
            'discount_amount' => 0.00,
            'platform_fee' => 5.00,
            'net_amount' => 95.00,
            'payment_id' => 'pay_test_abc123',
            'payment_gateway' => 'razorpay',
            'order_id' => 'order_test_abc123',
            'paid_at' => now(),
        ]);

        $this->pendingDonation = $this->makeDonation('RC-2026-TEST0002', 'pending', [
            'donor_name' => 'Priya Sharma',
            'donor_email' => 'donor@receipt.test',
            'user_id' => $this->donor->id,
        ]);

        $this->refundedDonation = $this->makeDonation('RC-2026-TEST0003', 'completed', [
            'donor_name' => 'Priya Sharma',
            'donor_email' => 'donor@receipt.test',
            'user_id' => $this->donor->id,
            'is_refunded' => true,
            'paid_at' => now(),
        ]);
    }

    private function makeDonation(string $receiptNo, string $status, array $overrides = []): Donation
    {
        $donation = Donation::create(array_merge([
            'campaign_id' => $this->campaign->id,
            'donation_type' => 'money',
            'total_amount' => 100.00,
            'original_amount' => 100.00,
            'discount_amount' => 0.00,
            'platform_fee' => 5.00,
            'net_amount' => 95.00,
            'payment_gateway' => 'razorpay',
            'currency' => 'INR',
            'receipt_number' => $receiptNo,
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
    // EMAIL
    // =========================================================================

    public function test_receipt_email_renders_all_receipt_values(): void
    {
        $mail = new DonationReceiptMail($this->completedDonation);

        $html = $mail->render();

        $this->assertStringContainsString('Priya Sharma', $html);
        $this->assertStringContainsString('Help Little Aarav Fight Cancer', $html);
        $this->assertStringContainsString('₹100.00', $html);
        $this->assertStringContainsString('RC-2026-TEST0001', $html);
        $this->assertStringContainsString('₹5.00', $html);
        $this->assertStringContainsString('₹95.00', $html);
        $this->assertStringContainsString('Download Receipt PDF', $html);
        $this->assertStringContainsString('View Campaign Progress', $html);
        $this->assertStringContainsString('pay_test_abc123', $html);
        $this->assertStringContainsString('Razorpay', $html);
        $this->assertStringContainsString('/donation-receipt/', $html);
        $this->assertStringContainsString('signature=', $html);
    }

    public function test_receipt_email_includes_signed_download_url(): void
    {
        $data = app(DonationReceiptService::class)->data($this->completedDonation);

        $this->assertStringContainsString('signature=', $data['receiptDownloadUrl']);
        $this->assertStringContainsString('expires=', $data['receiptDownloadUrl']);
        $this->assertStringContainsString((string) $this->completedDonation->id, $data['receiptDownloadUrl']);
    }

    public function test_receipt_email_shows_coupon_row_when_discount_applied(): void
    {
        $this->completedDonation->forceFill([
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
            'original_amount' => 110.00,
        ])->save();

        $mail = new DonationReceiptMail($this->completedDonation);

        $html = $mail->render();

        $this->assertStringContainsString('Coupon (SAVE10)', $html);
        $this->assertStringContainsString('− ₹10.00', $html);
    }

    public function test_receipt_email_hides_coupon_row_when_no_discount(): void
    {
        $mail = new DonationReceiptMail($this->completedDonation);

        $html = $mail->render();

        $this->assertStringNotContainsString('Coupon (', $html);
    }

    public function test_receipt_email_uses_authoritative_financial_values(): void
    {
        $donation = $this->completedDonation;
        $data = app(DonationReceiptService::class)->data($donation);

        $this->assertSame($donation->total_amount, $data['amount']);
        $this->assertSame($donation->platform_fee, $data['platformFee']);
        $this->assertSame($donation->net_amount, $data['netAmount']);
        $this->assertSame($donation->receipt_number, $data['receiptNo']);
        $this->assertSame($donation->payment_id, $data['paymentReference']);
        $this->assertEquals($donation->paid_at, $data['paidAt']);
        $this->assertSame($donation->donor_name, $data['donorName']);
    }

    // =========================================================================
    // PDF DOWNLOAD — HAPPY PATH
    // =========================================================================

    public function test_valid_signed_url_downloads_receipt_pdf(): void
    {
        $response = $this->get($this->signedDownloadUrl($this->completedDonation));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertDownload('DONATEBAZAAR-Receipt-RC-2026-TEST0001.pdf');
    }

    public function test_donation_owner_logged_in_can_download(): void
    {
        $response = $this->actingAs($this->donor)
            ->get($this->signedDownloadUrl($this->completedDonation));

        $response->assertOk();
        $response->assertDownload('DONATEBAZAAR-Receipt-RC-2026-TEST0001.pdf');
    }

    public function test_pdf_view_contains_receipt_details(): void
    {
        $data = app(DonationReceiptService::class)->data($this->completedDonation, withUrls: false);

        $html = view('receipts.donation-pdf', $data)->render();

        $this->assertStringContainsString('RC-2026-TEST0001', $html);
        $this->assertStringContainsString('Priya Sharma', $html);
        $this->assertStringContainsString('Help Little Aarav Fight Cancer', $html);
        $this->assertStringContainsString('&#8377; 100.00', $html);
        $this->assertStringContainsString('&#8377; 5.00', $html);
        $this->assertStringContainsString('&#8377; 95.00', $html);
        $this->assertStringContainsString('pay_test_abc123', $html);
        $this->assertStringContainsString('Razorpay', $html);
        $this->assertStringContainsString('Official Donation Receipt', $html);
        $this->assertStringContainsString($this->completedDonation->paid_at->format('d M Y, h:i A'), $html);
        $this->assertStringContainsString('www.donatebazaar.com', $html);
        $this->assertStringContainsString('support@donatebazaar.com', $html);
    }

    public function test_pdf_view_includes_coupon_discount_when_applicable(): void
    {
        $this->completedDonation->forceFill([
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
        ])->save();

        $data = app(DonationReceiptService::class)->data($this->completedDonation, withUrls: false);

        $html = view('receipts.donation-pdf', $data)->render();

        $this->assertStringContainsString('Coupon (SAVE10)', $html);
        $this->assertStringContainsString('&#8722; &#8377; 10.00', $html);
    }

    // =========================================================================
    // PDF DOWNLOAD — SECURITY
    // =========================================================================

    public function test_expired_signed_url_is_rejected(): void
    {
        $url = $this->signedDownloadUrl($this->completedDonation, -5);

        $this->get($url)->assertForbidden();
    }

    public function test_tampered_signature_is_rejected(): void
    {
        $url = $this->signedDownloadUrl($this->completedDonation);
        $tampered = preg_replace('/signature=[^&]+/', 'signature=tampered-signature-value', $url);

        $this->get($tampered)->assertForbidden();
    }

    public function test_missing_signature_is_rejected(): void
    {
        $url = route('donations.receipt.download', $this->completedDonation->id);

        $this->get($url)->assertForbidden();
    }

    public function test_non_existent_donation_is_rejected(): void
    {
        $url = URL::temporarySignedRoute(
            'donations.receipt.download',
            now()->addHours(24),
            ['donation' => 999999]
        );

        $this->get($url)->assertNotFound();
    }

    public function test_pending_donation_is_rejected(): void
    {
        $this->get($this->signedDownloadUrl($this->pendingDonation))->assertForbidden();
    }

    public function test_refunded_donation_is_rejected(): void
    {
        $this->get($this->signedDownloadUrl($this->refundedDonation))->assertForbidden();
    }

    public function test_authenticated_non_owner_cannot_download(): void
    {
        $response = $this->actingAs($this->otherUser)
            ->get($this->signedDownloadUrl($this->completedDonation));

        $response->assertForbidden();
    }

    public function test_guest_with_valid_signed_url_can_download(): void
    {
        $response = $this->get($this->signedDownloadUrl($this->completedDonation));

        $response->assertOk();
    }

    // =========================================================================
    // ROUTE / NAMED ROUTE
    // =========================================================================

    public function test_download_route_exists_and_is_named(): void
    {
        $this->assertTrue(Route::has('donations.receipt.download'));
    }
}
