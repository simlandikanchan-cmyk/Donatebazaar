<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Campaign;
use App\Models\KycVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $ngo;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin_test@donatebazar.com']);

        $this->ngo = User::factory()->create(['role' => 'ngo', 'email' => 'ngo_test@donatebazar.com']);
        KycVerification::create([
            'user_id' => $this->ngo->id,
            'status'  => KycVerification::STATUS_APPROVED,
        ]);

        $this->category = Category::create([
            'name'     => 'Test Category',
            'slug'     => 'test-category',
            'icon'     => 'heart',
            'color'    => '#2563eb',
            'is_active' => true,
        ]);
    }

    private function makeCampaign(string $state, array $attrs = []): Campaign
    {
        return Campaign::create(array_merge([
            'user_id'        => $this->ngo->id,
            'category_id'    => $this->category->id,
            'title'          => 'Campaign ' . Str::random(6),
            'slug'           => 'camp-' . Str::random(8),
            'description'    => 'Test campaign',
            'goal_amount'    => 100000,
            'raised_amount'  => 0,
            'campaign_state' => $state,
        ], $attrs));
    }

    public function test_dashboard_page_loads(): void
    {
        $this->makeCampaign(Campaign::STATE_PENDING);
        $this->makeCampaign(Campaign::STATE_ACTIVE);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Campaign Activity');
    }

    public function test_dashboard_campaigns_endpoint_per_state(): void
    {
        $this->makeCampaign(Campaign::STATE_PENDING);
        $this->makeCampaign(Campaign::STATE_ACTIVE);
        $this->makeCampaign(Campaign::STATE_PAUSED);
        $this->makeCampaign(Campaign::STATE_REJECTED);
        $this->makeCampaign(Campaign::STATE_EXPIRED);

        foreach (['all', 'pending', 'active', 'paused', 'rejected', 'inactive'] as $state) {
            $response = $this->actingAs($this->admin)
                ->getJson(route('admin.dashboard.campaigns', ['state' => $state]));

            $response->assertStatus(200);
            $response->assertJsonStructure(['cards', 'pagination', 'total', 'counts']);
        }
    }

    public function test_campaign_quick_view_loads(): void
    {
        $campaign = $this->makeCampaign(Campaign::STATE_ACTIVE);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.campaign.quick', $campaign));

        $response->assertStatus(200);
    }

    public function test_bulk_approve(): void
    {
        $campaign = $this->makeCampaign(Campaign::STATE_PENDING);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.campaigns.bulk-approve'), ['ids' => [$campaign->id]]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'type']);
        $this->assertDatabaseHas('campaigns', [
            'id'            => $campaign->id,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    public function test_bulk_reject(): void
    {
        $campaign = $this->makeCampaign(Campaign::STATE_PENDING);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.campaigns.bulk-reject'), [
                'ids'    => [$campaign->id],
                'reason' => 'Fraudulent or misleading content',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'type']);
        $this->assertDatabaseHas('campaigns', [
            'id'             => $campaign->id,
            'campaign_state' => Campaign::STATE_REJECTED,
        ]);
    }

    public function test_bulk_pause(): void
    {
        $campaign = $this->makeCampaign(Campaign::STATE_ACTIVE);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.campaigns.bulk-pause'), [
                'ids'    => [$campaign->id],
                'reason' => 'Under review by admin team',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'type']);
        $this->assertDatabaseHas('campaigns', [
            'id'             => $campaign->id,
            'campaign_state' => Campaign::STATE_PAUSED,
        ]);
    }
}
