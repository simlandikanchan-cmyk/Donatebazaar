<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WalletSettlementFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.razorpay.key', 'rzp_test_key');
        Config::set('services.razorpay.secret', 'rzp_test_secret');

        $this->orgUser = User::factory()->create();
        $this->orgUser->role = 'ngo';
        $this->orgUser->save();

        $this->admin = User::factory()->create();
        $this->admin->role = 'admin';
        $this->admin->save();

        $this->org = Organization::factory()->create(['user_id' => $this->orgUser->id]);

        PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $this->campaign = Campaign::create([
            'title' => 'Wallet Test Campaign',
            'slug' => 'wallet-test-campaign',
            'user_id' => $this->orgUser->id,
            'description' => 'Used by wallet settlement flow test.',
            'goal_amount' => 10000.00,
        ]);

        $this->wallet = app(WalletService::class)->getOrCreateWallet($this->orgUser);
        app(WalletService::class)->credit($this->wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        $this->donation = Donation::create([
            'user_id' => $this->orgUser->id,
            'campaign_id' => $this->campaign->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 500.00,
        ]);
        DB::table('donations')->where('id', $this->donation->id)->update([
            'payment_status' => 'completed',
            'is_refunded' => false,
            'paid_at' => now()->subDays(10),
        ]);
    }

    #[Test]
    public function non_admin_cannot_approve_settlement(): void
    {
        $settlement = app(SettlementService::class)->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->orgUser)
            ->post(route('admin.settlements.approve', $settlement))
            ->assertForbidden();
    }

    #[Test]
    public function non_admin_cannot_reject_settlement(): void
    {
        $settlement = app(SettlementService::class)->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->orgUser)
            ->post(route('admin.settlements.reject', $settlement), ['reason' => 'x'])
            ->assertForbidden();
    }

    #[Test]
    public function admin_can_approve_pending_settlement(): void
    {
        $settlement = app(SettlementService::class)->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->admin)
            ->post(route('admin.settlements.approve', $settlement))
            ->assertRedirect(route('admin.settlements.show', $settlement))
            ->assertSessionHas('success');

        $settlement->refresh();
        $this->assertContains($settlement->status, ['paid', 'approved']);
        $this->assertNotNull($settlement->paid_at);
        $this->assertNotNull($settlement->processed_at);
        $this->assertNotNull($settlement->gateway_reference);
        $this->wallet->refresh();
        $this->assertEquals(0.00, (float) $this->wallet->pending_settlement_balance);
        $this->donation->refresh();
        $this->assertEquals('settled', $this->donation->settlement_status);
        $this->assertEquals($settlement->id, $this->donation->campaign_settlement_id);
    }

    #[Test]
    public function approve_returns_intermediate_approved_when_queue_async(): void
    {
        $settlement = app(SettlementService::class)->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->admin)
            ->post(route('admin.settlements.approve', $settlement))
            ->assertRedirect();

        $settlement->refresh();
        $this->assertContains($settlement->status, ['paid', 'approved', 'processing']);
    }

    #[Test]
    public function admin_can_reject_pending_settlement_with_reason(): void
    {
        $settlement = app(SettlementService::class)->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->admin)
            ->post(route('admin.settlements.reject', $settlement), ['reason' => 'Bad bank details'])
            ->assertRedirect(route('admin.settlements.show', $settlement))
            ->assertSessionHas('success');

        $settlement->refresh();
        $this->assertEquals('rejected', $settlement->status);
        $this->assertEquals('Bad bank details', $settlement->rejection_reason);
        $this->wallet->refresh();
        $this->assertEquals(500.00, (float) $this->wallet->balance);
    }

    #[Test]
    public function reject_requires_a_reason(): void
    {
        $settlement = app(SettlementService::class)->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->admin)
            ->post(route('admin.settlements.reject', $settlement), [])
            ->assertSessionHasErrors('reason');
    }

    #[Test]
    public function org_sees_pending_status_after_request(): void
    {
        app(SettlementService::class)->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->orgUser)
            ->get(route('dashboard.wallet'))
            ->assertOk()
            ->assertSee('Pending admin approval');
    }

    #[Test]
    public function org_cannot_double_submit_locked_donation(): void
    {
        app(SettlementService::class)->requestSettlement($this->org, [$this->donation->id]);

        // Second request for same donation should be rejected (validation/error).
        $response = $this->actingAs($this->orgUser)
            ->post(route('dashboard.wallet.request'), ['donation_ids' => [$this->donation->id]]);

        $response->assertRedirect();
        $count = CampaignSettlement::where('organization_id', $this->org->id)
            ->whereIn('status', ['pending_approval', 'manual_review'])
            ->count();
        $this->assertEquals(1, $count);
    }

    #[Test]
    public function standalone_user_without_org_gets_personal_org_on_payout(): void
    {
        // A plain user (no organization) with a matured, credited, eligible donation.
        $user = User::factory()->create();
        $wallet = app(WalletService::class)->getOrCreateWallet($user);
        app(WalletService::class)->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 99, User::class);

        $campaign = Campaign::create([
            'title' => 'Standalone Campaign',
            'slug' => 'standalone-'.uniqid(),
            'user_id' => $user->id,
            'description' => 'Standalone fundraiser campaign.',
            'goal_amount' => 10000.00,
        ]);

        $donation = Donation::create([
            'user_id' => $user->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 500.00,
            'payment_status' => 'completed',
            'is_refunded' => false,
            'paid_at' => now()->subDays(10),
        ]);

        $this->assertNull(Organization::where('user_id', $user->id)->first());

        $this->actingAs($user)
            ->post(route('dashboard.wallet.request'), ['donation_ids' => [$donation->id]])
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('error');

        // A personal "individual" organization was auto-created for the user even
        // though the request was rejected (no payout account on file yet).
        $org = Organization::where('user_id', $user->id)->first();
        $this->assertNotNull($org);
        $this->assertEquals('individual', $org->type);

        // Add a payout account and try again — should succeed now.
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => $user->name ?? 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
        ]);

        $this->actingAs($user)
            ->post(route('dashboard.wallet.request'), ['donation_ids' => [$donation->id]])
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('campaign_settlements', [
            'organization_id' => $org->id,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Payout account verify/unverify
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function admin_can_verify_payout_account(): void
    {
        $payoutAccount = PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => false,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.payout-accounts.verify', $payoutAccount))
            ->assertRedirect();

        $payoutAccount->refresh();
        $this->assertTrue($payoutAccount->is_verified);
        $this->assertNotNull($payoutAccount->verified_by);
        $this->assertEquals($this->admin->id, $payoutAccount->verified_by);
        $this->assertNotNull($payoutAccount->verified_at);
    }

    #[Test]
    public function admin_can_unverify_payout_account(): void
    {
        $payoutAccount = PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
            'verified_by' => $this->admin->id,
            'verified_at' => now(),
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.payout-accounts.unverify', $payoutAccount))
            ->assertRedirect();

        $payoutAccount->refresh();
        $this->assertFalse($payoutAccount->is_verified);
        $this->assertNull($payoutAccount->verified_by);
        $this->assertNull($payoutAccount->verified_at);
    }

    #[Test]
    public function non_admin_cannot_verify_payout_account(): void
    {
        $payoutAccount = PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => false,
        ]);

        $this->actingAs($this->orgUser)
            ->post(route('admin.payout-accounts.verify', $payoutAccount))
            ->assertForbidden();
    }

    #[Test]
    public function cannot_request_payout_without_payout_account(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $user->id]);
        $wallet = app(WalletService::class)->getOrCreateWallet($user);
        app(WalletService::class)->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, User::class);

        $campaign = Campaign::create([
            'title' => 'Payout Account Test',
            'slug' => 'payout-test-'.uniqid(),
            'user_id' => $user->id,
            'description' => 'Test.',
            'goal_amount' => 10000.00,
        ]);

        $donation = Donation::create([
            'user_id' => $user->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 500.00,
            'payment_status' => 'completed',
            'is_refunded' => false,
            'paid_at' => now()->subDays(10),
        ]);

        // No payout account → request must be rejected.
        $this->actingAs($user)
            ->post(route('dashboard.wallet.request'), ['donation_ids' => [$donation->id]])
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('error');

        // After adding one, the same request succeeds.
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
        ]);

        $this->actingAs($user)
            ->post(route('dashboard.wallet.request'), ['donation_ids' => [$donation->id]])
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('success');
    }
}
