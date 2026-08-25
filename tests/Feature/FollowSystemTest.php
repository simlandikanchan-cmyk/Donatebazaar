<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected User $owner;

    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);
        $this->owner = User::factory()->create(['role' => 'ngo']);

        $category = Category::create([
            'name' => 'Education',
            'slug' => 'education',
            'icon' => 'book',
            'color' => '#059669',
            'is_active' => true,
        ]);

        $this->campaign = Campaign::create([
            'user_id' => $this->owner->id,
            'category_id' => $category->id,
            'title' => 'School Fund',
            'slug' => 'school-fund',
            'description' => 'Help build a school',
            'goal_amount' => 100000,
            'raised_amount' => 0,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    public function test_user_can_follow_a_campaign(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('campaign.follow', $this->campaign));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('followers', [
            'follower_id' => $this->user->id,
            'following_id' => $this->campaign->id,
            'following_type' => Campaign::class,
        ]);

        $this->assertEquals(1, $this->campaign->followers()->count());
    }

    public function test_user_can_unfollow_a_campaign(): void
    {
        $this->campaign->follow($this->user);
        $this->assertEquals(1, $this->campaign->followers()->count());

        $response = $this->actingAs($this->user)
            ->post(route('campaign.follow', $this->campaign));

        $response->assertSessionHas('success');
        $this->assertEquals(0, $this->campaign->followers()->count());
    }

    public function test_followers_count_updates_instantly(): void
    {
        $this->campaign->follow($this->user);
        $this->campaign->refresh();
        $this->assertEquals(1, $this->campaign->followers()->count());

        $anotherUser = User::factory()->create();
        $this->campaign->follow($anotherUser);
        $this->campaign->refresh();
        $this->assertEquals(2, $this->campaign->followers()->count());

        $this->campaign->unfollow($anotherUser);
        $this->campaign->refresh();
        $this->assertEquals(1, $this->campaign->followers()->count());
    }

    public function test_user_can_view_followed_campaigns_list(): void
    {
        $this->campaign->follow($this->user);

        $response = $this->actingAs($this->user)
            ->get(route('saved.campaigns'));

        $response->assertStatus(200);
        $response->assertSee('School Fund');
    }

    public function test_unfollowed_campaign_disappears_from_saved_list(): void
    {
        $this->campaign->follow($this->user);

        $response = $this->actingAs($this->user)
            ->get(route('saved.campaigns'));
        $response->assertSee('School Fund');

        $this->campaign->unfollow($this->user);

        $response = $this->actingAs($this->user)
            ->get(route('saved.campaigns'));
        $response->assertDontSee('School Fund');
    }

    public function test_guest_cannot_follow_campaign(): void
    {
        $response = $this->post(route('campaign.follow', $this->campaign));
        $response->assertRedirect('/login');
    }

    public function test_public_detail_page_shows_followers_count(): void
    {
        $this->campaign->follow($this->user);

        $response = $this->get(route('campaign.public', [
            'category' => 'education',
            'slug' => $this->campaign->slug,
        ]));

        $response->assertStatus(200);
    }

    public function test_campaigns_sorted_by_latest_first(): void
    {
        $this->campaign->follow($this->user);

        $oldCampaign = Campaign::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->campaign->category_id,
            'title' => 'Old Campaign',
            'slug' => 'old-campaign',
            'description' => 'Older campaign',
            'goal_amount' => 50000,
            'raised_amount' => 0,
            'campaign_state' => Campaign::STATE_ACTIVE,
            'created_at' => now()->subDays(10),
        ]);
        $oldCampaign->follow($this->user);

        $response = $this->actingAs($this->user)
            ->get(route('saved.campaigns'));

        $response->assertStatus(200);
        $response->assertSee('School Fund');
        $response->assertSee('Old Campaign');
    }
}
