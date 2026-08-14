<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Models\Wallet;
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
            ->assertSee('Manual review');
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
        ]);
        $donation->payment_status = 'completed';
        $donation->is_refunded = false;
        $donation->paid_at = now()->subDays(10);
        $donation->save();

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
        ]);
        $donation->payment_status = 'completed';
        $donation->is_refunded = false;
        $donation->paid_at = now()->subDays(10);
        $donation->save();

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

    #[Test]
    public function wallet_page_lists_donations_made_to_my_campaigns_only(): void
    {
        // Donor is a DIFFERENT user from the campaign owner (real-world case).
        $donor = User::factory()->create();
        $myCampaign = Campaign::create([
            'title' => 'My Campaign',
            'slug' => 'my-campaign-'.uniqid(),
            'user_id' => $this->orgUser->id,
            'description' => 'Test.',
            'goal_amount' => 10000.00,
        ]);

        $myDonation = Donation::create([
            'user_id' => $donor->id,
            'campaign_id' => $myCampaign->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 475.00,
        ]);
        $myDonation->payment_status = 'completed';
        $myDonation->is_refunded = false;
        $myDonation->paid_at = now()->subDays(10);
        $myDonation->save();

        // The owner donated to someone else's campaign — must NOT appear as eligible.
        $otherCampaign = Campaign::create([
            'title' => 'Other Campaign',
            'slug' => 'other-campaign-'.uniqid(),
            'user_id' => $donor->id,
            'description' => 'Test.',
            'goal_amount' => 10000.00,
        ]);

        $otherDonation = Donation::create([
            'user_id' => $this->orgUser->id,
            'campaign_id' => $otherCampaign->id,
            'donation_type' => 'money',
            'total_amount' => 700.00,
            'platform_fee' => 35.00,
            'net_amount' => 665.00,
        ]);
        $otherDonation->payment_status = 'completed';
        $otherDonation->is_refunded = false;
        $otherDonation->paid_at = now()->subDays(10);
        $otherDonation->save();

        $response = $this->actingAs($this->orgUser)
            ->get(route('dashboard.wallet'))
            ->assertOk();

        // Only the donation made TO the owner's campaign is eligible.
        $response->assertSee('My Campaign');
        $response->assertDontSee('Other Campaign');
        $this->assertDatabaseHas('donations', ['id' => $myDonation->id]);
    }

    #[Test]
    public function wallet_page_hides_donations_already_locked_in_settlement(): void
    {
        $donor = User::factory()->create();
        $campaign = Campaign::create([
            'title' => 'Locked Campaign',
            'slug' => 'locked-campaign-'.uniqid(),
            'user_id' => $this->orgUser->id,
            'description' => 'Test.',
            'goal_amount' => 10000.00,
        ]);

        $donation = Donation::create([
            'user_id' => $donor->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 500.00,
        ]);
        $donation->payment_status = 'completed';
        $donation->is_refunded = false;
        $donation->paid_at = now()->subDays(10);
        $donation->save();

        // Fund the wallet so the settlement request succeeds.
        $wallet = app(WalletService::class)->getOrCreateWallet($this->orgUser);
        app(WalletService::class)->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 77, User::class);

        app(SettlementService::class)->requestSettlement($this->org, [$donation->id]);

        // Donation is now locked in a settlement — must not appear in the eligible list.
        $this->actingAs($this->orgUser)
            ->get(route('dashboard.wallet'))
            ->assertOk()
            ->assertDontSee('Locked Campaign');
    }

    #[Test]
    public function available_balance_equals_wallet_balance_not_minus_settlement_lock(): void
    {
        $wallet = app(WalletService::class)->getOrCreateWallet($this->orgUser);
        $wallet->forceFill([
            'balance' => 600.00,
            'pending_settlement_balance' => 400.00,
        ])->save();
        $wallet->refresh();

        // Settlement funds are already moved OUT of balance when a payout is
        // requested, so available must equal the balance (not balance - lock).
        $this->assertEquals(600.00, $wallet->available_balance);
    }

    #[Test]
    public function admin_can_credit_wallet_via_manual_adjustment(): void
    {
        $wallet = app(WalletService::class)->getOrCreateWallet($this->orgUser);

        $this->actingAs($this->admin)
            ->post(route('admin.wallets.adjust', $wallet), [
                'direction' => 'credit',
                'amount' => 250.00,
                'notes' => 'Bonus credit',
            ])
            ->assertRedirect(route('admin.wallets.show', $wallet))
            ->assertSessionHas('success');

        $wallet->refresh();
        // setUp already credited 500 to this wallet.
        $this->assertEquals(750.00, (float) $wallet->balance);

        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $wallet->id,
            'type' => 'credit',
            'source' => 'adjustment',
            'reference_type' => Wallet::class,
            'amount' => 250.00,
        ]);

        // reference_id must be a stored integer (string ids break the
        // unsignedBigInteger column) and unique per adjustment.
        $tx = WalletTransaction::where('wallet_id', $wallet->id)
            ->where('source', 'adjustment')->first();
        $this->assertIsNumeric($tx->reference_id);
        $this->assertGreaterThan(0, (int) $tx->reference_id);
    }

    #[Test]
    public function admin_can_debit_wallet_via_manual_adjustment(): void
    {
        $wallet = app(WalletService::class)->getOrCreateWallet($this->orgUser);

        $this->actingAs($this->admin)
            ->post(route('admin.wallets.adjust', $wallet), [
                'direction' => 'debit',
                'amount' => 120.00,
                'notes' => 'Correction',
            ])
            ->assertRedirect(route('admin.wallets.show', $wallet))
            ->assertSessionHas('success');

        $wallet->refresh();
        // setUp already credited 500 to this wallet.
        $this->assertEquals(380.00, (float) $wallet->balance);
    }

    #[Test]
    public function admin_debit_adjustment_fails_gracefully_when_balance_insufficient(): void
    {
        $user = User::factory()->create();
        $wallet = app(WalletService::class)->getOrCreateWallet($user);

        $this->actingAs($this->admin)
            ->post(route('admin.wallets.adjust', $wallet), [
                'direction' => 'debit',
                'amount' => 100.00,
                'notes' => 'Overdraw attempt',
            ])
            ->assertRedirect(route('admin.wallets.show', $wallet))
            ->assertSessionHas('error');

        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->balance);
    }
}
