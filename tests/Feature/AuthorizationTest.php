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
        ]);
        $donation->payment_status = 'completed';
        $donation->save();

        $this->actingAs($this->userA)
            ->get(route('donation.receipt', $donation))
            ->assertForbidden();
    }

    public function test_user_a_can_edit_own_blog(): void
    {
        $blog = \App\Models\Blog::factory()->create([
            'author_id' => $this->userA->id,
            'author_role' => 'ngo',
            'status' => \App\Models\Blog::STATUS_DRAFT,
        ]);

        $this->actingAs($this->userA)
            ->get(route('user.blogs.edit', $blog))
            ->assertOk();
    }

    public function test_user_a_cannot_edit_user_b_blog(): void
    {
        $blog = \App\Models\Blog::factory()->create([
            'author_id' => $this->userB->id,
            'author_role' => 'ngo',
            'status' => \App\Models\Blog::STATUS_DRAFT,
        ]);

        $this->actingAs($this->userA)
            ->get(route('user.blogs.edit', $blog))
            ->assertForbidden();
    }

    public function test_user_a_can_delete_own_blog(): void
    {
        $blog = \App\Models\Blog::factory()->create([
            'author_id' => $this->userA->id,
            'author_role' => 'ngo',
            'status' => \App\Models\Blog::STATUS_DRAFT,
        ]);

        $this->actingAs($this->userA)
            ->delete(route('user.blogs.destroy', $blog))
            ->assertSessionHas('success');
    }

    public function test_user_a_cannot_delete_user_b_blog(): void
    {
        $blog = \App\Models\Blog::factory()->create([
            'author_id' => $this->userB->id,
            'author_role' => 'ngo',
            'status' => \App\Models\Blog::STATUS_DRAFT,
        ]);

        $this->actingAs($this->userA)
            ->delete(route('user.blogs.destroy', $blog))
            ->assertForbidden();
    }

    public function test_user_a_cannot_restore_user_b_deleted_blog(): void
    {
        $blog = \App\Models\Blog::factory()->create([
            'author_id' => $this->userB->id,
            'author_role' => 'ngo',
            'status' => \App\Models\Blog::STATUS_DRAFT,
        ]);
        $blog->delete();

        $this->actingAs($this->userA)
            ->post(route('user.blogs.restore', $blog->id))
            ->assertNotFound();
    }

    public function test_user_a_cannot_update_user_b_blog(): void
    {
        $blog = \App\Models\Blog::factory()->create([
            'author_id' => $this->userB->id,
            'author_role' => 'ngo',
            'status' => \App\Models\Blog::STATUS_DRAFT,
        ]);

        $this->actingAs($this->userA)
            ->put(route('user.blogs.update', $blog), [
                'title' => 'Hacked Blog Title',
                'content' => str_repeat('This is a detailed blog post about fundraising strategies and tips. ', 20),
            ])
            ->assertForbidden();
    }

    public function test_user_a_cannot_submit_user_b_blog(): void
    {
        $blog = \App\Models\Blog::factory()->create([
            'author_id' => $this->userB->id,
            'author_role' => 'ngo',
            'status' => \App\Models\Blog::STATUS_DRAFT,
        ]);

        $this->actingAs($this->userA)
            ->post(route('user.blogs.submit', $blog))
            ->assertForbidden();
    }

    public function test_unauthorized_blog_requests_return_403(): void
    {
        $blog = \App\Models\Blog::factory()->create([
            'author_id' => $this->userB->id,
            'author_role' => 'ngo',
            'status' => \App\Models\Blog::STATUS_DRAFT,
        ]);

        $this->actingAs($this->userA)
            ->get(route('user.blogs.show', $blog))
            ->assertForbidden();
    }
}
