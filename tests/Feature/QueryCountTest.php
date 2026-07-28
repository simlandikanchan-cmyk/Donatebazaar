<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\CampaignUpdate;
use App\Models\Category;
use App\Models\Donation;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QueryCountTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $owner;

    protected User $donor;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->owner = User::factory()->create(['role' => 'ngo']);
        $this->donor = User::factory()->create(['role' => 'donor']);

        KycVerification::create([
            'user_id' => $this->owner->id,
            'status' => KycVerification::STATUS_APPROVED,
        ]);

        $this->category = Category::create([
            'name' => 'Health',
            'slug' => 'health',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);

        $otherCategory = Category::create([
            'name' => 'Education',
            'slug' => 'education',
            'icon' => 'book',
            'color' => '#059669',
            'is_active' => true,
        ]);

        for ($i = 0; $i < 12; $i++) {
            $campaign = Campaign::create([
                'user_id' => $this->owner->id,
                'category_id' => $i % 2 === 0 ? $this->category->id : $otherCategory->id,
                'title' => "Test Campaign {$i}",
                'slug' => "test-campaign-{$i}",
                'description' => "Description for campaign {$i}",
                'goal_amount' => 100000 + ($i * 1000),
                'raised_amount' => $i * 5000,
                'campaign_state' => Campaign::STATE_ACTIVE,
                'is_featured' => $i < 3,
                'start_date' => now()->subDays(30)->toDateString(),
                'end_date' => now()->addDays(30 - $i)->toDateString(),
            ]);

            Donation::create([
                'campaign_id' => $campaign->id,
                'user_id' => $this->donor->id,
                'donor_name' => "Donor {$i}",
                'donor_email' => "donor{$i}@example.com",
                'donation_type' => 'money',
                'total_amount' => 1000 + ($i * 100),
                'platform_fee' => 30 + ($i * 3),
                'net_amount' => 970 + ($i * 97),
                'order_id' => "order_{$i}",
                'currency' => 'INR',
                'payment_status' => 'completed',
                'paid_at' => now()->subHours($i),
            ]);

            CampaignProduct::create([
                'campaign_id' => $campaign->id,
                'user_id' => $this->owner->id,
                'name' => "Product {$i}",
                'price' => 100 + $i,
                'quantity' => 50,
                'remaining_quantity' => 50 - $i,
                'is_active' => true,
                'approval_status' => 'approved',
            ]);

            CampaignUpdate::create([
                'campaign_id' => $campaign->id,
                'title' => "Update {$i}",
                'body' => "Update body {$i}",
                'description' => "Update description {$i}",
                'created_by' => $this->owner->id,
            ]);
        }

        for ($i = 0; $i < 6; $i++) {
            Blog::create([
                'author_id' => $this->owner->id,
                'title' => "Blog Post {$i}",
                'slug' => "blog-post-{$i}",
                'content' => "Content for blog post {$i}",
                'status' => 'published',
                'published_at' => now()->subHours($i),
            ]);
        }

        $this->campaign = Campaign::where('campaign_state', Campaign::STATE_ACTIVE)->first();

        DB::enableQueryLog();
    }

    protected function tearDown(): void
    {
        DB::disableQueryLog();
        parent::tearDown();
    }

    private function getQueryCount(): int
    {
        return count(DB::getQueryLog());
    }

    public function test_homepage_loads_with_fewer_than_20_queries(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(
            20,
            $this->getQueryCount(),
            'Homepage exceeded 20 queries: '.$this->getQueryCount()
        );
    }

    public function test_campaign_detail_page_loads_with_fewer_than_10_queries(): void
    {
        $response = $this->get(route('campaign.public', [
            'category' => $this->category->slug,
            'slug' => $this->campaign->slug,
        ]));

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(
            10,
            $this->getQueryCount(),
            'Campaign detail exceeded 10 queries: '.$this->getQueryCount()
        );
    }

    public function test_all_campaigns_paginated_loads_with_fewer_than_15_queries(): void
    {
        $response = $this->get(route('all.campaigns', ['page' => 1]));

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(
            15,
            $this->getQueryCount(),
            'All campaigns page exceeded 15 queries: '.$this->getQueryCount()
        );
    }

    public function test_donation_history_loads_with_fewer_than_25_queries(): void
    {
        $response = $this->actingAs($this->donor)
            ->get(route('donation.history'));

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(
            25,
            $this->getQueryCount(),
            'Donation history exceeded 25 queries: '.$this->getQueryCount()
        );
    }

    public function test_user_dashboard_loads_with_fewer_than_40_queries(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(
            40,
            $this->getQueryCount(),
            'User dashboard exceeded 40 queries: '.$this->getQueryCount()
        );
    }

    public function test_admin_dashboard_loads_with_fewer_than_30_queries(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(
            30,
            $this->getQueryCount(),
            'Admin dashboard exceeded 30 queries: '.$this->getQueryCount()
        );
    }

    public function test_campaign_detail_no_n_plus_one_for_donations(): void
    {
        $campaign = $this->campaign;

        for ($i = 0; $i < 15; $i++) {
            $user = User::factory()->create();
            Donation::create([
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
                'donor_name' => "Bulk Donor {$i}",
                'donor_email' => "bulk{$i}@example.com",
                'donation_type' => 'money',
                'total_amount' => 500,
                'net_amount' => 485,
                'order_id' => "order_bulk_{$i}",
                'currency' => 'INR',
                'payment_status' => 'completed',
                'paid_at' => now()->subMinutes($i),
            ]);
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->get(route('campaign.public', [
            'category' => $this->category->slug,
            'slug' => $campaign->slug,
        ]));

        $response->assertStatus(200);
        $qCount = $this->getQueryCount();

        $this->assertLessThanOrEqual(
            15,
            $qCount,
            'Campaign detail with 15+ donations exceeded 15 queries (N+1 detected): '.$qCount
        );
    }

    public function test_admin_donations_list_loads_with_fewer_than_10_queries(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.donations.index'));

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(
            10,
            $this->getQueryCount(),
            'Admin donations list exceeded 10 queries: '.$this->getQueryCount()
        );
    }
}
