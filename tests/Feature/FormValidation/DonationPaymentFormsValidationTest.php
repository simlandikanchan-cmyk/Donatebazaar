<?php

namespace Tests\Feature\FormValidation;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Donation;
use App\Models\FundraiserLevel;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationPaymentFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);
        $this->actingAs($this->user);

        $level = FundraiserLevel::create([
            'level_number' => 1, 'level_name' => 'Starter',
            'max_goal_amount' => 500000.00, 'max_active_campaigns' => 5, 'is_default' => true,
        ]);

        UserFundraiserLevel::create([
            'user_id' => $this->user->id, 'current_level_id' => $level->id, 'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Health', 'slug' => 'health', 'icon' => 'heart', 'color' => '#2563eb', 'is_active' => true,
        ]);

        $this->campaign = Campaign::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'campaign_state' => 'active',
        ]);
    }

    // ─── Donate Redirect ──────────────────────────────────────────────────

    public function test_donate_redirect_happy_path(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", [
            'amount' => '500',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_donate_redirect_amount_required(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '']);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_amount_numeric(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => 'not-a-number']);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_amount_min(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '0']);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_amount_max(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '500001']);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_amount_zero(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '0.50']);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_negative_amount(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '-100']);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_decimal_amount(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '100.50']);

        $response->assertSessionHasNoErrors();
    }

    public function test_donate_redirect_custom_error_message(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '']);

        $response->assertRedirect();
        $this->assertStringContainsString('enter a donation amount', session('error'));
    }

    // ─── Payment Verify ───────────────────────────────────────────────────

    public function test_payment_verify_required_fields(): void
    {
        $donation = Donation::factory()->create();

        $response = $this->post('/payment/verify', [
            'razorpay_order_id' => '',
            'razorpay_payment_id' => '',
            'razorpay_signature' => '',
            'donation_id' => '',
        ]);

        $response->assertSessionHasErrors(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'donation_id']);
    }

    public function test_payment_verify_donation_id_invalid(): void
    {
        $response = $this->post('/payment/verify', [
            'razorpay_order_id' => 'order_xxx',
            'razorpay_payment_id' => 'pay_xxx',
            'razorpay_signature' => 'sig_xxx',
            'donation_id' => 99999,
        ]);

        $response->assertSessionHasErrors('donation_id');
    }

    public function test_payment_verify_donation_id_not_integer(): void
    {
        $response = $this->post('/payment/verify', [
            'razorpay_order_id' => 'order_xxx',
            'razorpay_payment_id' => 'pay_xxx',
            'razorpay_signature' => 'sig_xxx',
            'donation_id' => 'not-int',
        ]);

        $response->assertSessionHasErrors('donation_id');
    }

    // ─── Coupon Validate ──────────────────────────────────────────────────

    public function test_coupon_validate_happy_path(): void
    {
        $coupon = Coupon::factory()->create();

        $response = $this->post('/coupon/validate', [
            'code' => $coupon->code,
            'amount' => 500,
            'campaign_id' => $this->campaign->id,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_coupon_validate_code_required(): void
    {
        $response = $this->post('/coupon/validate', [
            'code' => '', 'amount' => 500, 'campaign_id' => $this->campaign->id,
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_coupon_validate_amount_required(): void
    {
        $response = $this->post('/coupon/validate', [
            'code' => 'TEST', 'amount' => '', 'campaign_id' => $this->campaign->id,
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_coupon_validate_amount_numeric(): void
    {
        $response = $this->post('/coupon/validate', [
            'code' => 'TEST', 'amount' => 'not-number', 'campaign_id' => $this->campaign->id,
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_coupon_validate_amount_min(): void
    {
        $response = $this->post('/coupon/validate', [
            'code' => 'TEST', 'amount' => '0', 'campaign_id' => $this->campaign->id,
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_coupon_validate_campaign_id_exists(): void
    {
        $response = $this->post('/coupon/validate', [
            'code' => 'TEST', 'amount' => 500, 'campaign_id' => 99999,
        ]);

        $response->assertSessionHasErrors('campaign_id');
    }

    // ─── Recurring Donation ───────────────────────────────────────────────

    public function test_recurring_donation_happy_path(): void
    {
        $response = $this->post("/campaign/{$this->campaign->id}/recurring", [
            'amount' => '500',
            'frequency' => 'monthly',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_recurring_donation_amount_required(): void
    {
        $response = $this->post("/campaign/{$this->campaign->id}/recurring", [
            'amount' => '', 'frequency' => 'monthly',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_recurring_donation_amount_numeric(): void
    {
        $response = $this->post("/campaign/{$this->campaign->id}/recurring", [
            'amount' => 'not-number', 'frequency' => 'monthly',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_recurring_donation_amount_min(): void
    {
        $response = $this->post("/campaign/{$this->campaign->id}/recurring", [
            'amount' => '5', 'frequency' => 'monthly',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_recurring_donation_frequency_required(): void
    {
        $response = $this->post("/campaign/{$this->campaign->id}/recurring", [
            'amount' => '500', 'frequency' => '',
        ]);

        $response->assertSessionHasErrors('frequency');
    }

    public function test_recurring_donation_invalid_frequency(): void
    {
        $response = $this->post("/campaign/{$this->campaign->id}/recurring", [
            'amount' => '500', 'frequency' => 'yearly',
        ]);

        $response->assertSessionHasErrors('frequency');
    }

    // ─── Recurring Cancel / Pause / Resume ────────────────────────────────

    public function test_recurring_cancel_redirects_guest(): void
    {
        auth()->logout(); $this->flushSession();
        $response = $this->patch('/recurring/1/cancel');

        $response->assertRedirect('/login');
    }

    public function test_recurring_pause_redirects_guest(): void
    {
        auth()->logout(); $this->flushSession();
        $response = $this->patch('/recurring/1/pause');

        $response->assertRedirect('/login');
    }

    public function test_recurring_resume_redirects_guest(): void
    {
        auth()->logout(); $this->flushSession();
        $response = $this->patch('/recurring/1/resume');

        $response->assertRedirect('/login');
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_donate_redirect_sql_injection_amount(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", [
            'amount' => "'; DROP TABLE donations; --",
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_extremely_large_amount(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", [
            'amount' => '999999999',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_session_errors_on_failure(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '']);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_donate_redirect_no_csrf_protection_issue(): void
    {
        $response = $this->post("/donate/{$this->campaign->id}", ['amount' => '100']);

        $this->assertNotEquals(419, $response->getStatusCode());
    }
}
