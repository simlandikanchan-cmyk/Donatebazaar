<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $userA;
    private User $userB;
    private Campaign $campaignB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userA = User::factory()->create(['role' => 'ngo']);
        $this->userB = User::factory()->create(['role' => 'ngo']);

        $category = Category::create([
            'name' => 'Health',
            'slug' => 'health',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);

        $this->campaignB = Campaign::create([
            'user_id' => $this->userB->id,
            'category_id' => $category->id,
            'title' => 'User B Campaign',
            'slug' => 'user-b-campaign',
            'description' => 'Campaign owned by user B',
            'goal_amount' => 50000,
            'raised_amount' => 0,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    public function test_user_a_cannot_edit_user_b_campaign(): void
    {
        $this->actingAs($this->userA)
            ->get(route('campaign.edit', $this->campaignB))
            ->assertForbidden();
    }

    public function test_user_a_cannot_update_user_b_campaign(): void
    {
        $this->actingAs($this->userA)
            ->put(route('campaign.update', $this->campaignB), [
                'title' => 'Hacked Title',
            ])
            ->assertForbidden();
    }

    public function test_user_a_cannot_pause_user_b_campaign(): void
    {
        $this->actingAs($this->userA)
            ->post(route('campaign.pause', $this->campaignB))
            ->assertForbidden();
    }

    public function test_user_a_cannot_resume_user_b_campaign(): void
    {
        $this->actingAs($this->userA)
            ->post(route('campaign.resume', $this->campaignB))
            ->assertForbidden();
    }

    public function test_user_a_cannot_resubmit_user_b_campaign(): void
    {
        $this->actingAs($this->userA)
            ->post(route('campaign.resubmit', $this->campaignB))
            ->assertForbidden();
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->userA)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_non_admin_cannot_access_admin_campaign_list(): void
    {
        $this->actingAs($this->userA)
            ->get(route('admin.campaign.index'))
            ->assertForbidden();
    }

    public function test_non_admin_cannot_access_admin_donations_list(): void
    {
        $this->actingAs($this->userA)
            ->get(route('admin.donations.index'))
            ->assertForbidden();
    }

    public function test_non_admin_cannot_approve_campaign(): void
    {
        $this->actingAs($this->userA)
            ->post(route('admin.campaign.approve', $this->campaignB))
            ->assertForbidden();
    }

    public function test_user_a_cannot_view_user_b_donation_receipt(): void
    {
        $donation = Donation::create([
            'user_id' => $this->userB->id,
            'campaign_id' => $this->campaignB->id,
            'donation_type' => 'money',
            'total_amount' => 100.00,
            'platform_fee' => 5.00,
            'net_amount' => 100.00,
            'payment_status' => 'completed',
        ]);

        $this->actingAs($this->userA)
            ->get(route('donation.receipt', $donation))
            ->assertForbidden();
    }
}
