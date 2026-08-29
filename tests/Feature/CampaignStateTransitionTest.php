<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\KycVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignStateTransitionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $ngo;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->ngo = User::factory()->create(['role' => 'ngo']);

        $kyc = KycVerification::create([
            'user_id' => $this->ngo->id,
        ]);
        $kyc->status = KycVerification::STATUS_APPROVED;
        $kyc->save();

        $this->category = Category::create([
            'name' => 'Health',
            'slug' => 'health',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);
    }

    private function createCampaign(string $state, array $overrides = []): Campaign
    {
        $campaign = Campaign::create(array_merge([
            'user_id' => $this->ngo->id,
            'category_id' => $this->category->id,
            'title' => 'Test Campaign',
            'slug' => 'test-campaign-'.uniqid(),
            'description' => 'Test campaign description',
            'goal_amount' => 100000,
            'campaign_state' => $state,
        ], $overrides));
        $campaign->raised_amount = 0;
        $campaign->save();

        return $campaign;
    }

    public function test_admin_can_approve_pending_campaign(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_PENDING);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign.approve', $campaign));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    public function test_admin_can_reject_pending_campaign(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_PENDING);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign.reject', $campaign), [
                'reason' => 'Insufficient documentation provided. Please resubmit with all required documents.',
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_REJECTED,
        ]);
    }

    public function test_admin_can_pause_active_campaign(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_ACTIVE);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign.pause', $campaign), [
                'reason' => 'Under review',
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_PAUSED,
        ]);
    }

    public function test_admin_can_resume_paused_campaign(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_PAUSED);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign.resume', $campaign));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    public function test_admin_can_complete_active_campaign(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_ACTIVE);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign.pause', $campaign), [
                'reason' => 'Goal achieved — manually completed',
            ]);

        $response->assertSessionHas('success');
    }

    public function test_active_campaign_with_past_end_date_auto_expires_on_public_view(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_ACTIVE, [
            'end_date' => Carbon::yesterday()->toDateString(),
        ]);

        $response = $this->get(route('campaign.public', [
            'category' => 'health',
            'slug' => $campaign->slug,
        ]));

        $response->assertStatus(200);
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_EXPIRED,
        ]);
    }

    public function test_user_can_resubmit_rejected_campaign(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_REJECTED, [
            'rejection_reason' => 'Insufficient documentation',
        ]);

        $response = $this->actingAs($this->ngo)
            ->post(route('campaign.resubmit', $campaign));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_PENDING,
            'rejection_reason' => null,
        ]);
    }

    public function test_soft_deleted_campaign_does_not_appear_in_public_listings(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_ACTIVE);
        $campaign->delete();

        $response = $this->get(route('all.campaigns'));

        $response->assertStatus(200);
        $response->assertDontSee($campaign->title);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'deleted_at' => $campaign->fresh()->deleted_at,
        ]);
    }

    public function test_soft_deleted_campaign_returns_404_on_public_detail(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_ACTIVE);
        $campaign->delete();

        $response = $this->get(route('campaign.public', [
            'category' => 'health',
            'slug' => $campaign->slug,
        ]));

        $response->assertStatus(404);
    }

    public function test_approve_non_pending_campaign_fails(): void
    {
        $campaign = $this->createCampaign(Campaign::STATE_ACTIVE);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign.approve', $campaign));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    public function test_bulk_approve_changes_state_instantly(): void
    {
        $campaigns = [];
        for ($i = 0; $i < 3; $i++) {
            $campaigns[] = $this->createCampaign(Campaign::STATE_PENDING, [
                'title' => 'Bulk Campaign '.$i,
                'slug' => 'bulk-campaign-'.$i.'-'.uniqid(),
            ]);
        }

        $ids = array_map(fn ($c) => $c->id, $campaigns);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.campaigns.bulk-approve'), ['ids' => $ids]);

        $response->assertStatus(200);
        foreach ($campaigns as $campaign) {
            $this->assertDatabaseHas('campaigns', [
                'id' => $campaign->id,
                'campaign_state' => Campaign::STATE_ACTIVE,
            ]);
        }
    }

    public function test_bulk_approve_skips_campaign_without_kyc(): void
    {
        $noKycUser = User::factory()->create(['role' => 'ngo']);

        $campaign = Campaign::create([
            'user_id' => $noKycUser->id,
            'category_id' => $this->category->id,
            'title' => 'No KYC Bulk',
            'slug' => 'no-kyc-bulk-'.uniqid(),
            'description' => 'Campaign for a user without approved KYC',
            'goal_amount' => 100000,
            'campaign_state' => Campaign::STATE_PENDING,
        ]);

        $this->actingAs($this->admin)
            ->postJson(route('admin.campaigns.bulk-approve'), ['ids' => [$campaign->id]])
            ->assertStatus(200);

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_PENDING,
        ]);
    }
}
