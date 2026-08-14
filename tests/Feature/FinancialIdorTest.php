<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Category;
use App\Models\Donation;
use App\Models\KycVerification;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FinancialIdorTest extends TestCase
{
    use RefreshDatabase;

    private User $userA;

    private User $userB;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('private');

        $this->userA = User::factory()->create(['role' => 'ngo', 'email' => 'usera@test.com']);
        $this->userB = User::factory()->create(['role' => 'ngo', 'email' => 'userb@test.com']);
        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@test.com']);

        $this->category = Category::create([
            'name' => 'Test',
            'slug' => 'test',
            'icon' => 'test',
            'color' => '#000000',
            'is_active' => true,
        ]);
    }

    // ========================================================================
    // WALLET IDOR TESTS
    // ========================================================================

    #[Test]
    public function user_a_can_access_own_wallet_dashboard(): void
    {
        $this->actingAs($this->userA)
            ->get(route('dashboard.wallet'))
            ->assertOk();
    }

    #[Test]
    public function user_a_cannot_request_payout_for_user_b_donations(): void
    {
        $orgB = Organization::create([
            'user_id' => $this->userB->id,
            'name' => 'Org B',
            'type' => 'individual',
        ]);

        $campaignB = Campaign::create([
            'user_id' => $this->userB->id,
            'category_id' => $this->category->id,
            'title' => 'Campaign B',
            'slug' => 'campaign-b',
            'description' => 'Test',
            'goal_amount' => 10000,
        ]);

        $donationB = Donation::create([
            'campaign_id' => $campaignB->id,
            'user_id' => $this->userB->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 475.00,
            'payment_status' => 'completed',
        ]);

        $response = $this->actingAs($this->userA)
            ->post(route('dashboard.wallet.request'), [
                'donation_ids' => [$donationB->id],
            ]);

        $response->assertRedirect(route('dashboard.wallet'));
        $response->assertSessionHas('error');
    }

    #[Test]
    public function user_a_cannot_save_payout_account_using_user_b_organization(): void
    {
        Organization::create([
            'user_id' => $this->userB->id,
            'name' => 'Org B',
            'type' => 'individual',
        ]);

        $response = $this->actingAs($this->userA)
            ->post(route('dashboard.wallet.payout-account'), [
                'account_holder_name' => 'User A',
                'account_number' => '1234',
                'ifsc_code' => 'TEST0000',
                'bank_name' => 'Test Bank',
            ]);

        $response->assertRedirect(route('dashboard.wallet'));
        $response->assertSessionHas('success', 'Payout account saved.');

        // User A's payout account was saved (WalletController creates an org for User A)
        $userAPayout = PayoutAccount::where('account_holder_name', '!=', '')->first();
        $this->assertNotNull($userAPayout);
        // Account should belong to user A's organization, not user B's
        $userAOrg = Organization::where('user_id', $this->userA->id)->first();
        $this->assertEquals($userAOrg->id, $userAPayout->organization_id);
        // No payout account was saved for user B's org
        $userBOrg = Organization::where('user_id', $this->userB->id)->first();
        $this->assertDatabaseMissing('payout_accounts', [
            'organization_id' => $userBOrg->id,
        ]);
    }

    // ========================================================================
    // SETTLEMENT IDOR TESTS
    // ========================================================================

    #[Test]
    public function user_a_cannot_approve_user_b_settlement(): void
    {
        $campaignB = Campaign::create([
            'user_id' => $this->userB->id,
            'category_id' => $this->category->id,
            'title' => 'Campaign B',
            'slug' => 'campaign-b-settle',
            'description' => 'Test',
            'goal_amount' => 10000,
        ]);

        $orgB = Organization::create([
            'user_id' => $this->userB->id,
            'name' => 'Org B',
            'type' => 'individual',
        ]);

        $settlement = CampaignSettlement::create([
            'organization_id' => $orgB->id,
            'campaign_id' => $campaignB->id,
            'gross_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 475.00,
            'status' => 'pending_approval',
        ]);

        $response = $this->actingAs($this->userA)
            ->post(route('admin.settlements.approve', $settlement));

        $response->assertForbidden();
    }

    #[Test]
    public function non_admin_cannot_access_admin_settlement_routes(): void
    {
        $campaignB = Campaign::create([
            'user_id' => $this->userB->id,
            'category_id' => $this->category->id,
            'title' => 'Campaign B',
            'slug' => 'campaign-b-settle-routes',
            'description' => 'Test',
            'goal_amount' => 10000,
        ]);

        $orgB = Organization::create([
            'user_id' => $this->userB->id,
            'name' => 'Org B',
            'type' => 'individual',
        ]);

        $settlement = CampaignSettlement::create([
            'organization_id' => $orgB->id,
            'campaign_id' => $campaignB->id,
            'gross_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 475.00,
            'status' => 'pending_approval',
        ]);

        $this->actingAs($this->userA)
            ->get(route('admin.settlements.index'))
            ->assertForbidden();

        $this->actingAs($this->userA)
            ->get(route('admin.settlements.show', $settlement))
            ->assertForbidden();

        $this->actingAs($this->userA)
            ->post(route('admin.settlements.approve', $settlement))
            ->assertForbidden();

        $this->actingAs($this->userA)
            ->post(route('admin.settlements.reject', $settlement))
            ->assertForbidden();
    }

    // ========================================================================
    // PAYOUT ACCOUNT IDOR TESTS
    // ========================================================================

    #[Test]
    public function non_admin_cannot_access_admin_payout_account_routes(): void
    {
        $orgB = Organization::create([
            'user_id' => $this->userB->id,
            'name' => 'Org B',
            'type' => 'individual',
        ]);

        $payoutAccount = PayoutAccount::create([
            'organization_id' => $orgB->id,
            'account_holder_name' => 'User B',
            'account_number' => '9876543210',
            'ifsc_code' => 'TEST0000',
            'bank_name' => 'Test Bank',
            'is_verified' => true,
        ]);

        $this->actingAs($this->userA)
            ->post(route('admin.payout-accounts.verify', $payoutAccount))
            ->assertForbidden();

        $this->actingAs($this->userA)
            ->post(route('admin.payout-accounts.unverify', $payoutAccount))
            ->assertForbidden();
    }

    #[Test]
    public function user_a_cannot_access_admin_wallet_show_for_user_b(): void
    {
        $walletB = Wallet::create([
            'owner_type' => 'App\Models\User',
            'owner_id' => $this->userB->id,
            'user_id' => $this->userB->id,
            'currency' => 'INR',
        ]);

        $this->actingAs($this->userA)
            ->get(route('admin.wallets.show', $walletB))
            ->assertForbidden();
    }

    // ========================================================================
    // KYC DOCUMENT IDOR TESTS
    // ========================================================================

    #[Test]
    public function user_a_cannot_view_user_b_kyc_document(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $campaignB = Campaign::create([
            'user_id' => $this->userB->id,
            'category_id' => $this->category->id,
            'title' => 'Campaign B',
            'slug' => 'campaign-b-idor',
            'description' => 'Test',
            'goal_amount' => 10000,
        ]);

        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');
        $path = $file->store('kyc-documents/'.$this->userB->id.'/'.$campaignB->id, 'private');

        KycVerification::create([
            'user_id' => $this->userB->id,
            'campaign_id' => $campaignB->id,
            'document_type' => 'pancard',
            'document_number' => 'ABC123',
            'document_url' => $path,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->userA)
            ->get(route('kyc.document', $campaignB));

        $response->assertForbidden();
    }

    #[Test]
    public function unauthenticated_user_cannot_access_kyc_document(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $campaignB = Campaign::create([
            'user_id' => $this->userB->id,
            'category_id' => $this->category->id,
            'title' => 'Campaign B',
            'slug' => 'campaign-b-unauth',
            'description' => 'Test',
            'goal_amount' => 10000,
        ]);

        $this->get(route('kyc.document', $campaignB))
            ->assertRedirect(route('login'));
    }

    // ========================================================================
    // MASS ASSIGNMENT PROTECTION TESTS
    // ========================================================================

    #[Test]
    public function user_cannot_escalate_role_via_mass_assignment(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        $user->fill([
            'name' => 'Hacked',
            'role' => 'admin',
            'otp_hash' => 'injected',
            'otp_attempts' => 999,
            'phone_verified_at' => now(),
        ]);

        $this->assertNotEquals('admin', $user->getOriginal('role'));
        $this->assertNotEquals('admin', $user->role);
    }

    #[Test]
    public function donation_system_fields_cannot_be_mass_assigned(): void
    {
        $user = User::factory()->create(['role' => 'ngo']);
        $campaign = Campaign::create([
            'user_id' => $user->id,
            'category_id' => $this->category->id,
            'title' => 'Test Campaign',
            'slug' => 'test-campaign-mass-assign',
            'description' => 'Test',
            'goal_amount' => 10000,
        ]);

        $donation = Donation::forceCreate([
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 475.00,
        ]);

        $donation->fill([
            'total_amount' => 999999.00,
            'platform_fee' => 999999.00,
            'net_amount' => 999999.00,
            'payment_status' => 'completed',
            'settlement_status' => 'settled',
            'is_refunded' => true,
        ]);

        $donation->save();
        $donation->refresh();

        // Monetary fields are still fillable but we verify system state fields are guarded
        $this->assertNotEquals('completed', $donation->payment_status);
        $this->assertNotEquals('settled', $donation->settlement_status);
        $this->assertFalse($donation->is_refunded);
    }

    #[Test]
    public function security_headers_include_csp(): void
    {
        $response = $this->actingAs($this->userA)
            ->get(route('dashboard.wallet'));

        $response->assertHeader('Content-Security-Policy');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy');
    }
}
