<?php

namespace Tests\Feature\FormValidation;

use App\Models\Blog;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Event;
use App\Models\FundraiserLevel;
use App\Models\KycVerification;
use App\Models\LegalPage;
use App\Models\Organization;
use App\Models\OrganizationApplication;
use App\Models\Partnership;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Models\VolunteerAssignment;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActionFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'donor']);

        $level = FundraiserLevel::create([
            'level_number' => 1, 'level_name' => 'Starter',
            'max_goal_amount' => 500000.00, 'max_active_campaigns' => 5, 'is_default' => true,
        ]);

        UserFundraiserLevel::create([
            'user_id' => $this->regularUser->id, 'current_level_id' => $level->id, 'status' => 'active',
        ]);
    }

    private function createCampaign(array $overrides = []): Campaign
    {
        $category = Category::create(['name' => 'Health', 'slug' => 'health', 'icon' => 'heart', 'color' => '#2563eb', 'is_active' => true]);

        return Campaign::factory()->create(array_merge([
            'user_id' => $this->regularUser->id,
            'category_id' => $category->id,
            'campaign_state' => Campaign::STATE_PENDING,
        ], $overrides));
    }

    // ─── Campaign Approve ─────────────────────────────────────────────────

    public function test_campaign_approve_guest_redirect(): void
    {
        $campaign = $this->createCampaign();

        $response = $this->post("/admin/campaign/{$campaign->id}/approve");

        $response->assertRedirect('/login');
    }

    public function test_campaign_approve_happy_path(): void
    {
        $campaign = $this->createCampaign();

        $response = $this->actingAs($this->admin)->post("/admin/campaign/{$campaign->id}/approve");

        $response->assertSessionHasNoErrors();
    }

    // ─── Campaign Reject ──────────────────────────────────────────────────

    public function test_campaign_reject_requires_reason(): void
    {
        $campaign = $this->createCampaign();

        $response = $this->actingAs($this->admin)->post("/admin/campaign/{$campaign->id}/reject", []);

        $response->assertSessionHasErrors('reason');
    }

    public function test_campaign_reject_reason_min_length(): void
    {
        $campaign = $this->createCampaign();

        $response = $this->actingAs($this->admin)->post("/admin/campaign/{$campaign->id}/reject", [
            'reason' => 'Short',
        ]);

        $response->assertSessionHasErrors('reason');
    }

    public function test_campaign_reject_reason_max_length(): void
    {
        $campaign = $this->createCampaign();

        $response = $this->actingAs($this->admin)->post("/admin/campaign/{$campaign->id}/reject", [
            'reason' => str_repeat('A', 501),
        ]);

        $response->assertSessionHasErrors('reason');
    }

    // ─── Campaign Pause ───────────────────────────────────────────────────

    public function test_campaign_pause_happy_path(): void
    {
        $campaign = $this->createCampaign(['campaign_state' => Campaign::STATE_ACTIVE]);

        $response = $this->actingAs($this->admin)->post("/admin/campaign/{$campaign->id}/pause", [
            'reason' => 'Under review',
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ─── Campaign Resume ──────────────────────────────────────────────────

    public function test_campaign_resume_happy_path(): void
    {
        $campaign = $this->createCampaign(['campaign_state' => Campaign::STATE_PAUSED]);

        $response = $this->actingAs($this->admin)->post("/admin/campaign/{$campaign->id}/resume");

        $response->assertSessionHasNoErrors();
    }

    // ─── Campaign Bulk Approve ────────────────────────────────────────────

    public function test_campaign_bulk_approve_ids_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/campaigns/bulk-approve', ['ids' => '']);

        $response->assertSessionHasErrors('ids');
    }

    public function test_campaign_bulk_reject_ids_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/campaigns/bulk-reject', ['ids' => '']);

        $response->assertSessionHasErrors('ids');
    }

    public function test_campaign_bulk_reject_reason_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/campaigns/bulk-reject', ['ids' => [1, 2]]);

        $response->assertSessionHasErrors('reason');
    }

    // ─── Campaign Update (Admin) ──────────────────────────────────────────

    public function test_admin_campaign_update_happy_path(): void
    {
        $campaign = $this->createCampaign();

        $response = $this->actingAs($this->admin)->put("/admin/campaign/{$campaign->id}/update", [
            'title' => 'Admin Updated Title',
            'description' => 'Updated description for the campaign.',
            'goal_amount' => 200000,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_admin_campaign_update_title_required(): void
    {
        $campaign = $this->createCampaign();

        $response = $this->actingAs($this->admin)->put("/admin/campaign/{$campaign->id}/update", [
            'title' => '', 'description' => 'Desc', 'goal_amount' => 1000,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_admin_campaign_update_goal_amount_numeric(): void
    {
        $campaign = $this->createCampaign();

        $response = $this->actingAs($this->admin)->put("/admin/campaign/{$campaign->id}/update", [
            'title' => 'Test', 'description' => 'Desc', 'goal_amount' => 'not-number',
        ]);

        $response->assertSessionHasErrors('goal_amount');
    }

    // ─── KYC Approve / Reject ─────────────────────────────────────────────

    public function test_kyc_approve_happy_path(): void
    {
        $campaign = $this->createCampaign();
        $kyc = KycVerification::create([
            'campaign_id' => $campaign->id,
            'user_id' => $this->regularUser->id,
            'document_type' => 'pan',
            'document_number' => 'ABCDE1234F',
            'document_url' => 'kyc/test.pdf',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/kyc/{$kyc->id}/approve");

        $response->assertSessionHasNoErrors();
    }

    public function test_kyc_reject_requires_reason(): void
    {
        $campaign = $this->createCampaign();
        $kyc = KycVerification::create([
            'campaign_id' => $campaign->id,
            'user_id' => $this->regularUser->id,
            'document_type' => 'pan',
            'document_number' => 'ABCDE1234F',
            'document_url' => 'kyc/test.pdf',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/kyc/{$kyc->id}/reject", []);

        $response->assertSessionHasErrors('rejection_reason');
    }

    // ─── Application Approve / Reject ─────────────────────────────────────

    public function test_application_approve_happy_path(): void
    {
        $application = OrganizationApplication::create([
            'user_id' => $this->regularUser->id,
            'organization_type' => 'NGO',
            'name' => 'Test NGO',
            'contact_name' => 'Contact Person',
            'contact_phone' => '9876543210',
            'contact_email' => 'contact@testngo.org',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/applications/{$application->id}/approve");

        $response->assertSessionHasNoErrors();
    }

    public function test_application_reject_requires_reason(): void
    {
        $application = OrganizationApplication::create([
            'user_id' => $this->regularUser->id,
            'organization_type' => 'NGO',
            'name' => 'Test NGO',
            'contact_name' => 'Contact Person',
            'contact_phone' => '9876543210',
            'contact_email' => 'contact@testngo.org',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/applications/{$application->id}/reject", []);

        $response->assertSessionHasErrors('rejection_reason');
    }

    // ─── Partnership Update ───────────────────────────────────────────────

    public function test_partnership_update_happy_path(): void
    {
        $partnership = Partnership::create([
            'name' => 'John', 'email' => 'john@test.com', 'organization_name' => 'Org',
            'partnership_type' => 'csr', 'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/partnerships/{$partnership->id}", [
            'status' => 'approved',
            'admin_notes' => 'Approved after review.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_partnership_update_status_required(): void
    {
        $partnership = Partnership::create([
            'name' => 'John', 'email' => 'john@test.com', 'organization_name' => 'Org',
            'partnership_type' => 'csr', 'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/partnerships/{$partnership->id}", ['status' => '']);

        $response->assertSessionHasErrors('status');
    }

    public function test_partnership_update_status_invalid(): void
    {
        $partnership = Partnership::create([
            'name' => 'John', 'email' => 'john@test.com', 'organization_name' => 'Org',
            'partnership_type' => 'csr', 'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/partnerships/{$partnership->id}", ['status' => 'invalid']);

        $response->assertSessionHasErrors('status');
    }

    // ─── Wallet Adjust ────────────────────────────────────────────────────

    public function test_wallet_adjust_happy_path(): void
    {
        $wallet = Wallet::create([
            'user_id' => $this->regularUser->id,
            'owner_type' => 'user',
            'owner_id' => $this->regularUser->id,
            'balance' => 0,
            'currency' => 'INR',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/wallets/{$wallet->id}/adjust", [
            'direction' => 'credit',
            'amount' => 1000,
            'notes' => 'Manual adjustment for testing.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_wallet_adjust_direction_required(): void
    {
        $wallet = Wallet::create([
            'user_id' => $this->regularUser->id,
            'owner_type' => 'user', 'owner_id' => $this->regularUser->id,
            'balance' => 0, 'currency' => 'INR',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/wallets/{$wallet->id}/adjust", [
            'direction' => '', 'amount' => 1000, 'notes' => 'Test',
        ]);

        $response->assertSessionHasErrors('direction');
    }

    public function test_wallet_adjust_direction_invalid(): void
    {
        $wallet = Wallet::create([
            'user_id' => $this->regularUser->id,
            'owner_type' => 'user', 'owner_id' => $this->regularUser->id,
            'balance' => 0, 'currency' => 'INR',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/wallets/{$wallet->id}/adjust", [
            'direction' => 'invalid', 'amount' => 1000, 'notes' => 'Test',
        ]);

        $response->assertSessionHasErrors('direction');
    }

    public function test_wallet_adjust_amount_required(): void
    {
        $wallet = Wallet::create([
            'user_id' => $this->regularUser->id,
            'owner_type' => 'user', 'owner_id' => $this->regularUser->id,
            'balance' => 0, 'currency' => 'INR',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/wallets/{$wallet->id}/adjust", [
            'direction' => 'credit', 'amount' => '', 'notes' => 'Test',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_wallet_adjust_amount_min(): void
    {
        $wallet = Wallet::create([
            'user_id' => $this->regularUser->id,
            'owner_type' => 'user', 'owner_id' => $this->regularUser->id,
            'balance' => 0, 'currency' => 'INR',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/wallets/{$wallet->id}/adjust", [
            'direction' => 'credit', 'amount' => 0, 'notes' => 'Test',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_wallet_adjust_notes_required(): void
    {
        $wallet = Wallet::create([
            'user_id' => $this->regularUser->id,
            'owner_type' => 'user', 'owner_id' => $this->regularUser->id,
            'balance' => 0, 'currency' => 'INR',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/wallets/{$wallet->id}/adjust", [
            'direction' => 'credit', 'amount' => 1000, 'notes' => '',
        ]);

        $response->assertSessionHasErrors('notes');
    }

    // ─── Settlement Approve / Reject ──────────────────────────────────────

    public function test_settlement_reject_requires_reason(): void
    {
        $campaign = $this->createCampaign();
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => Organization::create(['name' => 'Test Org', 'type' => 'trust'])->id,
            'gross_amount' => 5000,
            'platform_fee' => 250,
            'net_amount' => 4750,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/settlements/{$settlement->id}/reject", []);

        $response->assertSessionHasErrors('reason');
    }

    public function test_settlement_reject_reason_max_length(): void
    {
        $campaign = $this->createCampaign();
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => Organization::create(['name' => 'Test Org', 'type' => 'trust'])->id,
            'gross_amount' => 5000,
            'platform_fee' => 250,
            'net_amount' => 4750,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/settlements/{$settlement->id}/reject", [
            'reason' => str_repeat('A', 1001),
        ]);

        $response->assertSessionHasErrors('reason');
    }

    // ─── Volunteer Application Approve / Reject ───────────────────────────

    public function test_volunteer_application_approve_happy_path(): void
    {
        $volunteer = Volunteer::factory()->create(['user_id' => $this->regularUser->id]);
        $campaign = $this->createCampaign();
        $application = VolunteerApplication::create([
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $campaign->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/volunteer-applications/{$application->id}/approve");

        $response->assertSessionHasNoErrors();
    }

    public function test_volunteer_application_reject_happy_path(): void
    {
        $volunteer = Volunteer::factory()->create(['user_id' => $this->regularUser->id]);
        $campaign = $this->createCampaign();
        $application = VolunteerApplication::create([
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $campaign->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/volunteer-applications/{$application->id}/reject");

        $response->assertSessionHasNoErrors();
    }

    // ─── Volunteer Assignment ─────────────────────────────────────────────

    public function test_volunteer_assignment_create_happy_path(): void
    {
        $volunteer = Volunteer::factory()->create(['user_id' => $this->regularUser->id]);
        $campaign = $this->createCampaign();

        $response = $this->actingAs($this->admin)->post('/admin/volunteer-assignments', [
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $campaign->id,
            'role' => 'Event Coordinator',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonth()->format('Y-m-d'),
            'status' => 'active',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_volunteer_assignment_create_volunteer_id_required(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/volunteer-assignments', [
            'volunteer_id' => '', 'role' => 'Coordinator', 'status' => 'active',
        ]);

        $response->assertSessionHasErrors('volunteer_id');
    }

    public function test_volunteer_assignment_create_role_required(): void
    {
        $volunteer = Volunteer::factory()->create(['user_id' => $this->regularUser->id]);

        $response = $this->actingAs($this->admin)->post('/admin/volunteer-assignments', [
            'volunteer_id' => $volunteer->id, 'role' => '', 'status' => 'active',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_volunteer_assignment_create_status_required(): void
    {
        $volunteer = Volunteer::factory()->create(['user_id' => $this->regularUser->id]);

        $response = $this->actingAs($this->admin)->post('/admin/volunteer-assignments', [
            'volunteer_id' => $volunteer->id, 'role' => 'Coordinator', 'status' => '',
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_volunteer_assignment_create_end_date_after_start_date(): void
    {
        $volunteer = Volunteer::factory()->create(['user_id' => $this->regularUser->id]);

        $response = $this->actingAs($this->admin)->post('/admin/volunteer-assignments', [
            'volunteer_id' => $volunteer->id, 'role' => 'Coordinator', 'status' => 'active',
            'start_date' => now()->addMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('end_date');
    }

    // ─── Payout Account Verify / Unverify ─────────────────────────────────

    public function test_payout_account_verify_happy_path(): void
    {
        $org = Organization::create(['name' => 'Test Org', 'type' => 'trust']);
        $account = PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Holder',
            'bank_name' => 'SBI',
            'account_number' => '12345',
            'ifsc_code' => 'SBIN001',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/payout-accounts/{$account->id}/verify");

        $response->assertSessionHasNoErrors();
    }

    // ─── Legal Page Update ────────────────────────────────────────────────

    public function test_legal_page_update_happy_path(): void
    {
        LegalPage::create(['title' => 'Privacy Policy', 'slug' => 'privacy', 'content' => 'Policy content']);

        $response = $this->actingAs($this->admin)->put('/admin/legal/privacy', [
            'title' => 'Updated Privacy Policy',
            'content' => 'Updated policy content.',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_legal_page_update_title_required(): void
    {
        LegalPage::create(['title' => 'Terms', 'slug' => 'terms', 'content' => 'Terms content']);

        $response = $this->actingAs($this->admin)->put('/admin/legal/terms', [
            'title' => '', 'content' => 'Content',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_legal_page_update_content_required(): void
    {
        LegalPage::create(['title' => 'Terms', 'slug' => 'terms', 'content' => 'Terms content']);

        $response = $this->actingAs($this->admin)->put('/admin/legal/terms', [
            'title' => 'Title', 'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    // ─── Event Approve / Reject (Admin) ───────────────────────────────────

    public function test_admin_event_approve_happy_path(): void
    {
        $category = Category::create(['name' => 'Test', 'slug' => 'test', 'color' => '#000']);
        $campaign = Campaign::factory()->create(['user_id' => $this->regularUser->id, 'category_id' => $category->id]);
        $event = Event::factory()->create(['campaign_id' => $campaign->id, 'user_id' => $this->regularUser->id, 'slug' => 'event-' . uniqid()]);

        $response = $this->actingAs($this->admin)->post("/admin/events/{$event->id}/approve");

        $response->assertSessionHasNoErrors();
    }

    public function test_admin_event_toggle_setting_field_required(): void
    {
        $category = Category::create(['name' => 'Test', 'slug' => 'test', 'color' => '#000']);
        $campaign = Campaign::factory()->create(['user_id' => $this->regularUser->id, 'category_id' => $category->id]);
        $event = Event::factory()->create(['campaign_id' => $campaign->id, 'user_id' => $this->regularUser->id, 'slug' => 'event-' . uniqid()]);

        $response = $this->actingAs($this->admin)->post("/admin/events/{$event->id}/toggle-setting", ['field' => '']);

        $response->assertSessionHasErrors('field');
    }

    // ─── Donation Refund ──────────────────────────────────────────────────

    public function test_donation_refund_happy_path(): void
    {
        $category = Category::create(['name' => 'Health', 'slug' => 'health', 'color' => '#000']);
        $campaign = Campaign::factory()->create(['user_id' => $this->regularUser->id, 'category_id' => $category->id]);
        $donation = Donation::factory()->completed()->create(['campaign_id' => $campaign->id]);

        $response = $this->actingAs($this->admin)->post("/admin/donations/{$donation->id}/refund");

        $response->assertSessionHasNoErrors();
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_all_admin_actions_redirect_guest(): void
    {
        $response = $this->post('/admin/campaigns/bulk-approve', ['ids' => [1, 2]]);
        $response->assertRedirect('/login');

        $response = $this->post('/admin/campaigns/bulk-reject', ['ids' => [1, 2], 'reason' => 'Test']);
        $response->assertRedirect('/login');

        $response = $this->post('/admin/kyc/1/approve');
        $response->assertRedirect('/login');
    }

    public function test_wallet_adjust_negative_amount(): void
    {
        $wallet = Wallet::create([
            'user_id' => $this->regularUser->id,
            'owner_type' => 'user', 'owner_id' => $this->regularUser->id,
            'balance' => 0, 'currency' => 'INR',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/wallets/{$wallet->id}/adjust", [
            'direction' => 'debit', 'amount' => -100, 'notes' => 'Test',
        ]);

        $response->assertSessionHasErrors('amount');
    }
}
