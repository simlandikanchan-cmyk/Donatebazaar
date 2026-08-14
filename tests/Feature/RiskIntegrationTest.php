<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\RiskConfig;
use App\Models\RiskRule;
use App\Models\RiskScore;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\RiskEngine;
use App\Services\Risk\RiskRuleRegistry;
use App\Services\Risk\Rules\AmlScreenRule;
use App\Services\Risk\Rules\KycVerifiedRule;
use App\Services\Risk\Rules\LargePayoutAmountRule;
use App\Services\Risk\ScoreCalculator;
use App\Services\Risk\VerdictResolver;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RiskIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $admin;
    private Organization $org;
    private WalletService $walletService;
    private SettlementService $settlementService;
    private Donation $donation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->admin = User::factory()->create(['role' => 'admin']);

        $this->seedRiskConfig();

        $this->org = Organization::factory()->create([
            'user_id' => $this->owner->id,
        ]);

        PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $this->walletService = app(WalletService::class);
        $this->settlementService = app(SettlementService::class);

        $wallet = $this->walletService->getOrCreateWallet($this->owner);
        $this->walletService->credit($wallet, 5000.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, Organization::class);

        $campaign = Campaign::create([
            'title' => 'Risk Test Campaign',
            'slug' => 'risk-test-' . uniqid(),
            'user_id' => $this->owner->id,
            'description' => 'Test campaign for risk integration',
            'goal_amount' => 10000.00,
        ]);

        $this->donation = Donation::create([
            'user_id' => $this->owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 5000.00,
            'platform_fee' => 250.00,
            'net_amount' => 5000.00,
        ]);
        $this->donation->payment_status = 'completed';
        $this->donation->is_refunded = false;
        $this->donation->paid_at = now()->subDays(10);
        $this->donation->save();
    }

    private function seedRiskConfig(): void
    {
        RiskConfig::create([
            'risk_version' => 1,
            'approval_threshold' => 10,
            'manual_review_threshold' => 30,
        ]);

        RiskRule::create([
            'name' => 'LARGE_PAYOUT_AMOUNT',
            'category' => 'LIMITS',
            'weight' => 25,
            'priority' => 1,
            'enabled' => true,
        ]);

        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 20,
            'priority' => 2,
            'enabled' => true,
        ]);

        RiskRule::create([
            'name' => 'AML_SCREEN',
            'category' => 'COMPLIANCE',
            'weight' => 50,
            'priority' => 3,
            'enabled' => true,
        ]);

        $this->app->singleton(RiskRuleRegistry::class, function () {
            $registry = new RiskRuleRegistry($this->app);
            $registry->register('LARGE_PAYOUT_AMOUNT', LargePayoutAmountRule::class);
            $registry->register('KYC_VERIFIED', KycVerifiedRule::class);
            $registry->register('AML_SCREEN', AmlScreenRule::class);
            return $registry;
        });
    }

    private function createSettlementOrg(string $prefix): Organization
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

        $wallet = $this->walletService->getOrCreateWallet($this->owner);
        $this->walletService->credit($wallet, 2000.00, WalletTransaction::SOURCE_ADJUSTMENT, 2, Organization::class);

        $campaign = Campaign::create([
            'title' => $prefix . ' Campaign',
            'slug' => strtolower($prefix) . '-' . uniqid(),
            'user_id' => $this->owner->id,
            'description' => 'Campaign for ' . $prefix,
            'goal_amount' => 10000.00,
        ]);

        $donation = Donation::create([
            'user_id' => $this->owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 1000.00,
            'platform_fee' => 50.00,
            'net_amount' => 1000.00,
        ]);
        $donation->payment_status = 'completed';
        $donation->is_refunded = false;
        $donation->paid_at = now()->subDays(10);
        $donation->save();

        return $org;
    }

    #[Test]
    public function high_payout_triggers_manual_review(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'net_amount' => 200000.00,
        ]);

        $riskEngine = app(RiskEngine::class);
        $result = $riskEngine->evaluate($settlement);

        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $result->verdict);
        $this->assertTrue($result->requiresManualReview);
    }

    #[Test]
    public function aml_hit_blocks_settlement(): void
    {
        $org = Organization::factory()->create(['user_id' => $this->owner->id]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'net_amount' => 5000.00,
        ]);

        $context = new RiskContext(
            settlement: $settlement,
            organization: $org,
            payoutAccount: null,
            signals: ['aml_hit' => true, 'aml_version' => 'v1'],
            extra: [],
        );

        $riskEngine = app(RiskEngine::class);
        $result = $riskEngine->evaluate($settlement, $context);

        $this->assertSame(RiskScore::VERDICT_REJECTED, $result->verdict);
        $this->assertTrue($result->requiresManualReview);
    }

    #[Test]
    public function manual_review_admin_can_override_to_approved(): void
    {
        $org = $this->createSettlementOrg('Approval');

        $settlement = $this->settlementService->requestSettlement($org, [
            Donation::where('campaign_id', Campaign::where('title', 'Approval Campaign')->first()->id)
                ->first()->id,
        ]);

        $this->assertEquals('manual_review', $settlement->status);

        $this->settlementService->approveSettlement($settlement, $this->admin);
        $settlement->refresh();

        $this->assertEquals('approved', $settlement->status);
    }

    #[Test]
    public function manual_review_admin_can_override_to_rejected(): void
    {
        $org = $this->createSettlementOrg('Rejection');

        $settlement = $this->settlementService->requestSettlement($org, [
            Donation::where('campaign_id', Campaign::where('title', 'Rejection Campaign')->first()->id)
                ->first()->id,
        ]);

        $this->assertEquals('manual_review', $settlement->status);

        $this->settlementService->rejectSettlement($settlement, $this->admin, 'Risk override rejection');
        $settlement->refresh();

        $this->assertEquals('rejected', $settlement->status);
    }

    #[Test]
    public function risk_evaluation_persists_score_and_logs(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $this->org->id,
            'net_amount' => 200000.00,
        ]);

        app(RiskEngine::class)->evaluate($settlement);

        $this->assertDatabaseHas('risk_scores', [
            'settlement_id' => $settlement->id,
        ]);

        $riskScore = RiskScore::firstWhere('settlement_id', $settlement->id);
        $this->assertNotNull($riskScore);

        $this->assertDatabaseHas('risk_rule_logs', [
            'risk_score_id' => $riskScore->id,
        ]);

        $this->assertDatabaseHas('campaign_settlements', [
            'id' => $settlement->id,
            'risk_verdict' => RiskScore::VERDICT_MANUAL_REVIEW,
        ]);
    }
}
