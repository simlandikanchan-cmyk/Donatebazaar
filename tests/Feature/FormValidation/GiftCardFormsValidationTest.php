<?php

namespace Tests\Feature\FormValidation;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\FundraiserLevel;
use App\Models\GiftCard;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GiftCardFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);

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
            'user_id' => $this->user->id, 'category_id' => $category->id, 'campaign_state' => 'active',
        ]);
    }

    // ─── Gift Card Order ──────────────────────────────────────────────────

    public function test_gift_card_order_happy_path(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500,
            'theme' => 'purple',
            'sender_name' => 'John Doe',
            'sender_email' => 'john@example.com',
            'recipient_name' => 'Jane Doe',
            'recipient_email' => 'jane@example.com',
            'message' => 'Happy Birthday!',
            'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_gift_card_order_amount_required(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => '', 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_gift_card_order_amount_integer(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 'not-int', 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_gift_card_order_amount_min(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 50, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_gift_card_order_amount_max(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500001, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_gift_card_order_theme_required(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => '', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('theme');
    }

    public function test_gift_card_order_theme_invalid(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'invalid', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('theme');
    }

    public function test_gift_card_order_sender_name_required(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => '', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('sender_name');
    }

    public function test_gift_card_order_sender_email_required(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => '',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('sender_email');
    }

    public function test_gift_card_order_sender_email_format(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'invalid',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('sender_email');
    }

    public function test_gift_card_order_recipient_name_required(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => '', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('recipient_name');
    }

    public function test_gift_card_order_recipient_email_required(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => '', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('recipient_email');
    }

    public function test_gift_card_order_recipient_email_format(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'invalid', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('recipient_email');
    }

    public function test_gift_card_order_send_at_required(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => '',
        ]);

        $response->assertSessionHasErrors('send_at');
    }

    public function test_gift_card_order_send_at_after_or_equal_today(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com',
            'send_at' => now()->subDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('send_at');
    }

    public function test_gift_card_order_sender_name_max_length(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => str_repeat('A', 101), 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com', 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('sender_name');
    }

    // ─── Gift Card Verify ─────────────────────────────────────────────────

    public function test_gift_card_verify_required_fields(): void
    {
        $response = $this->post('/gift-cards/verify', [
            'razorpay_order_id' => '', 'razorpay_payment_id' => '', 'razorpay_signature' => '', 'gift_card_id' => '',
        ]);

        $response->assertSessionHasErrors(['razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'gift_card_id']);
    }

    public function test_gift_card_verify_invalid_id(): void
    {
        $response = $this->post('/gift-cards/verify', [
            'razorpay_order_id' => 'order_xxx', 'razorpay_payment_id' => 'pay_xxx',
            'razorpay_signature' => 'sig_xxx', 'gift_card_id' => 99999,
        ]);

        $response->assertSessionHasErrors('gift_card_id');
    }

    // ─── Gift Card Validate Code ──────────────────────────────────────────

    public function test_gift_card_validate_code_required(): void
    {
        $response = $this->post('/gift-cards/validate-code', ['code' => '']);

        $response->assertSessionHasErrors('code');
    }

    public function test_gift_card_validate_code_happy_path(): void
    {
        $giftCard = GiftCard::factory()->paid()->create();

        $response = $this->post('/gift-cards/validate-code', ['code' => $giftCard->code]);

        $response->assertSessionHasNoErrors();
    }

    // ─── Gift Card Redeem ─────────────────────────────────────────────────

    public function test_gift_card_redeem_happy_path(): void
    {
        $giftCard = GiftCard::factory()->paid()->create();

        $response = $this->post('/gift-cards/redeem', [
            'code' => $giftCard->code,
            'campaign_id' => $this->campaign->id,
            'donor_name' => 'John Doe',
            'donor_email' => 'john@example.com',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_gift_card_redeem_code_required(): void
    {
        $response = $this->post('/gift-cards/redeem', [
            'code' => '', 'campaign_id' => $this->campaign->id, 'donor_name' => 'John', 'donor_email' => 'john@test.com',
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_gift_card_redeem_campaign_id_required(): void
    {
        $response = $this->post('/gift-cards/redeem', [
            'code' => 'TEST', 'campaign_id' => '', 'donor_name' => 'John', 'donor_email' => 'john@test.com',
        ]);

        $response->assertSessionHasErrors('campaign_id');
    }

    public function test_gift_card_redeem_donor_name_required(): void
    {
        $response = $this->post('/gift-cards/redeem', [
            'code' => 'TEST', 'campaign_id' => $this->campaign->id, 'donor_name' => '', 'donor_email' => 'john@test.com',
        ]);

        $response->assertSessionHasErrors('donor_name');
    }

    public function test_gift_card_redeem_donor_email_required(): void
    {
        $response = $this->post('/gift-cards/redeem', [
            'code' => 'TEST', 'campaign_id' => $this->campaign->id, 'donor_name' => 'John', 'donor_email' => '',
        ]);

        $response->assertSessionHasErrors('donor_email');
    }

    public function test_gift_card_redeem_donor_email_format(): void
    {
        $response = $this->post('/gift-cards/redeem', [
            'code' => 'TEST', 'campaign_id' => $this->campaign->id, 'donor_name' => 'John', 'donor_email' => 'invalid',
        ]);

        $response->assertSessionHasErrors('donor_email');
    }

    public function test_gift_card_redeem_campaign_id_exists(): void
    {
        $response = $this->post('/gift-cards/redeem', [
            'code' => 'TEST', 'campaign_id' => 99999, 'donor_name' => 'John', 'donor_email' => 'john@test.com',
        ]);

        $response->assertSessionHasErrors('campaign_id');
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_gift_card_order_session_errors(): void
    {
        $response = $this->post('/gift-cards/order', ['amount' => '']);

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    public function test_gift_card_order_message_max_length(): void
    {
        $response = $this->post('/gift-cards/order', [
            'amount' => 500, 'theme' => 'purple', 'sender_name' => 'John', 'sender_email' => 'john@test.com',
            'recipient_name' => 'Jane', 'recipient_email' => 'jane@test.com',
            'message' => str_repeat('A', 501), 'send_at' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('message');
    }
}
