<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\GiftCard;
use App\Models\KycVerification;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $donor;
    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->donor = User::factory()->create(['role' => 'donor']);
        $this->owner = User::factory()->create(['role' => 'ngo']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ── Donation soft delete ────────────────────────────────────────────────

    #[Test]
    public function completed_donation_cannot_be_deleted(): void
    {
        $donation = Donation::factory()->create([
            'payment_status' => 'completed',
            'total_amount' => 500.00,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.donations.destroy', $donation))
            ->assertRedirect(route('admin.donations.index'))
            ->assertSessionHas('error');

        $this->assertNotNull(Donation::withTrashed()->find($donation->id));
    }

    #[Test]
    public function pending_donation_is_cancelled_then_soft_deleted(): void
    {
        $donation = Donation::factory()->create([
            'payment_status' => 'pending',
            'total_amount' => 500.00,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.donations.destroy', $donation))
            ->assertRedirect(route('admin.donations.index'))
            ->assertSessionHas('success');

        $this->assertNotNull(Donation::withTrashed()->find($donation->id));
        $this->assertSoftDeleted('donations', ['id' => $donation->id]);
    }

    #[Test]
    public function donation_deletion_creates_audit_log(): void
    {
        $donation = Donation::factory()->create([
            'payment_status' => 'pending',
            'total_amount' => 500.00,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.donations.destroy', $donation));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'donation_archived',
            'loggable_type' => Donation::class,
            'loggable_id' => $donation->id,
            'user_id' => $this->admin->id,
        ]);
    }

    // ── Settlement soft delete ──────────────────────────────────────────────

    #[Test]
    public function paid_settlement_cannot_be_deleted(): void
    {
        $org = Organization::factory()->create(['user_id' => $this->owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'paid',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.settlements.destroy', $settlement))
            ->assertRedirect(route('admin.settlements.index'))
            ->assertSessionHas('error');

        $this->assertNotNull(CampaignSettlement::withTrashed()->find($settlement->id));
    }

    #[Test]
    public function pending_settlement_is_soft_deleted(): void
    {
        $org = Organization::factory()->create(['user_id' => $this->owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'pending_approval',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.settlements.destroy', $settlement))
            ->assertRedirect(route('admin.settlements.index'))
            ->assertSessionHas('success');

        $this->assertNotNull(CampaignSettlement::withTrashed()->find($settlement->id));
        $this->assertSoftDeleted('campaign_settlements', ['id' => $settlement->id]);
    }

    #[Test]
    public function settlement_deletion_creates_audit_log(): void
    {
        $org = Organization::factory()->create(['user_id' => $this->owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'pending_approval',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.settlements.destroy', $settlement));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'settlement_archived',
            'loggable_type' => CampaignSettlement::class,
            'loggable_id' => $settlement->id,
            'user_id' => $this->admin->id,
        ]);
    }

    // ── Gift card redemption ────────────────────────────────────────────────

    #[Test]
    public function gift_card_without_verified_at_cannot_be_redeemed(): void
    {
        $code = 'DNBZ'.substr(uniqid(), 0, 8);
        $giftCard = GiftCard::factory()->create([
            'code' => $code,
            'amount' => 500.00,
            'payment_status' => 'completed',
            'status' => 'sent',
            'payment_verified_at' => null,
        ]);

        $this->assertNull($giftCard->fresh()->payment_verified_at);

        $this->actingAs($this->donor)
            ->post(route('gift-cards.redeem.submit'), [
                'code' => $code,
                'campaign_id' => \App\Models\Campaign::factory()->create(['user_id' => $this->owner->id])->id,
                'donor_name' => 'Test',
                'donor_email' => $this->donor->email,
            ])
            ->assertNotFound();
    }

    #[Test]
    public function gift_card_with_verified_at_can_be_redeemed(): void
    {
        $code = 'DNBZ'.substr(uniqid(), 0, 8);
        $giftCard = GiftCard::factory()->create([
            'code' => $code,
            'amount' => 500.00,
            'payment_status' => 'completed',
            'status' => 'sent',
            'payment_verified_at' => now(),
        ]);

        $this->assertNotNull($giftCard->fresh()->payment_verified_at);

        $campaign = \App\Models\Campaign::factory()->create([
            'user_id' => $this->owner->id,
            'campaign_state' => 'active',
        ]);

        $this->actingAs($this->donor)
            ->post(route('gift-cards.redeem.submit'), [
                'code' => $code,
                'campaign_id' => $campaign->id,
                'donor_name' => 'Test',
                'donor_email' => $this->donor->email,
            ])
            ->assertRedirect();
    }

    // ── CampaignKycController admin middleware ──────────────────────────────

    #[Test]
    public function guest_cannot_request_campaign_kyc(): void
    {
        $campaign = \App\Models\Campaign::factory()->create();

        $this->post(route('admin.campaign.request-kyc', $campaign))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function normal_user_cannot_request_campaign_kyc(): void
    {
        $campaign = \App\Models\Campaign::factory()->create();

        $this->actingAs($this->donor)
            ->post(route('admin.campaign.request-kyc', $campaign))
            ->assertForbidden();
    }

    #[Test]
    public function admin_can_request_campaign_kyc(): void
    {
        $campaign = \App\Models\Campaign::factory()->create();

        $this->actingAs($this->admin)
            ->post(route('admin.campaign.request-kyc', $campaign))
            ->assertRedirect()
            ->assertSessionHas('success');
    }

    // ── Wallet adjustment audit log ─────────────────────────────────────────

    #[Test]
    public function wallet_adjustment_creates_audit_log(): void
    {
        $owner = User::factory()->create(['role' => 'ngo']);
        $wallet = Wallet::create([
            'user_id' => $owner->id,
            'owner_type' => User::class,
            'owner_id' => $owner->id,
            'balance' => 0,
            'currency' => 'INR',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.wallets.adjust', $wallet), [
                'direction' => 'credit',
                'amount' => 100.00,
                'notes' => 'Test adjustment',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'wallet_adjusted',
            'loggable_type' => Wallet::class,
            'loggable_id' => $wallet->id,
            'user_id' => $this->admin->id,
        ]);
    }

    // ── ApplicationController no exit behavior ──────────────────────────────

    #[Test]
    public function application_controller_uses_proper_redirect(): void
    {
        $this->actingAs($this->donor)
            ->get(route('application.step2'))
            ->assertRedirect();
    }

    // ── Settlement approve audit log ────────────────────────────────────────

    #[Test]
    public function settlement_approve_creates_audit_log(): void
    {
        $org = Organization::factory()->create(['user_id' => $this->owner->id]);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'pending_approval',
        ]);

        // Mock the settlement service to avoid complex wallet setup
        $mockService = Mockery::mock(\App\Services\SettlementService::class);
        $mockService->shouldReceive('approveSettlement')->andReturnNull();
        $mockService->shouldReceive('processSettlementPayout')->andReturn(['success' => true]);
        $this->app->instance(\App\Services\SettlementService::class, $mockService);

        $this->actingAs($this->admin)
            ->post(route('admin.settlements.approve', $settlement))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'settlement_approved',
            'loggable_type' => CampaignSettlement::class,
            'loggable_id' => $settlement->id,
            'user_id' => $this->admin->id,
        ]);
    }

    // ── KYC approve audit log ───────────────────────────────────────────────

    #[Test]
    public function kyc_approve_creates_audit_log(): void
    {
        $kyc = KycVerification::create([
            'user_id' => $this->donor->id,
            'status' => 'pending',
            'document_type' => 'pan',
            'document_number' => 'TEST123456',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.kyc.approve', $kyc))
            ->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'kyc_approved',
            'loggable_type' => KycVerification::class,
            'loggable_id' => $kyc->id,
            'user_id' => $this->admin->id,
        ]);
    }

    // ── Payout account verify audit log ────────────────────────────────────

    #[Test]
    public function payout_account_verify_creates_audit_log(): void
    {
        $org = Organization::factory()->create(['user_id' => $this->owner->id]);
        $payout = PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => false,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.payout-accounts.verify', $payout))
            ->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'payout_account_verified',
            'loggable_type' => PayoutAccount::class,
            'loggable_id' => $payout->id,
            'user_id' => $this->admin->id,
        ]);
    }

    // ── Rate limiting on financial endpoints ────────────────────────────────

    #[Test]
    public function wallet_adjust_endpoint_is_rate_limited(): void
    {
        $owner = User::factory()->create(['role' => 'ngo']);
        $wallet = Wallet::create([
            'user_id' => $owner->id,
            'owner_type' => User::class,
            'owner_id' => $owner->id,
            'balance' => 1000,
            'currency' => 'INR',
        ]);

        for ($i = 0; $i < 11; $i++) {
            $this->actingAs($this->admin)
                ->post(route('admin.wallets.adjust', $wallet), [
                    'direction' => 'credit',
                    'amount' => 10.00,
                    'notes' => 'Rate limit test',
                ]);
        }

        $this->actingAs($this->admin)
            ->post(route('admin.wallets.adjust', $wallet), [
                'direction' => 'credit',
                'amount' => 10.00,
                'notes' => 'Rate limit test',
            ])
            ->assertStatus(429);
    }

    // ── Organization unique constraint ──────────────────────────────────────

    #[Test]
    public function duplicate_organization_for_same_user_is_prevented(): void
    {
        Organization::factory()->create(['user_id' => $this->donor->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Organization::factory()->create(['user_id' => $this->donor->id]);
    }
}
