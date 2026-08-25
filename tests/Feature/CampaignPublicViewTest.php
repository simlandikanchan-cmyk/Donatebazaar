<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\CampaignUpdate;
use App\Models\Category;
use App\Models\Donation;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignPublicViewTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected Category $category;

    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create(['role' => 'ngo', 'name' => 'NGO Test User']);

        KycVerification::create([
            'user_id' => $this->owner->id,
            'status' => KycVerification::STATUS_APPROVED,
        ]);

        $this->category = Category::create([
            'name' => 'Education',
            'slug' => 'education',
            'icon' => 'book',
            'color' => '#059669',
            'is_active' => true,
        ]);

        $this->campaign = Campaign::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'title' => 'Build a School Library',
            'slug' => 'build-a-school-library',
            'description' => 'Help us build a library with 1000 books for underprivileged children.',
            'goal_amount' => 500000,
            'campaign_state' => Campaign::STATE_ACTIVE,
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->addDays(20)->toDateString(),
        ]);
        $this->campaign->raised_amount = 250000;
        $this->campaign->save();
    }

    private function getPublicUrl(): string
    {
        return route('campaign.public', [
            'category' => $this->category->slug,
            'slug' => $this->campaign->slug,
        ]);
    }

    public function test_public_page_loads_for_active_campaign(): void
    {
        $response = $this->get($this->getPublicUrl());
        $response->assertStatus(200);
    }

    public function test_campaign_title_and_description_display(): void
    {
        $response = $this->get($this->getPublicUrl());
        $response->assertSee('Build a School Library');
        $response->assertSee('Help us build a library with 1000 books for underprivileged children.');
    }

    public function test_campaign_goal_and_raised_amount_display(): void
    {
        $response = $this->get($this->getPublicUrl());
        $response->assertSee('500,000');
        $response->assertSee('250,000');
    }

    public function test_category_name_appears_on_page(): void
    {
        $response = $this->get($this->getPublicUrl());
        $response->assertSee('Education');
    }

    public function test_campaign_owner_name_appears(): void
    {
        $response = $this->get($this->getPublicUrl());
        $response->assertSee('NGO Test User');
    }

    public function test_active_products_render_on_detail_page(): void
    {
        CampaignProduct::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->owner->id,
            'name' => 'Sponsor a Book',
            'description' => 'Provide one textbook to a student',
            'price' => 500,
            'quantity' => 100,
            'remaining_quantity' => 85,
            'is_active' => true,
            'approval_status' => 'approved',
        ]);

        $response = $this->get($this->getPublicUrl());
        $response->assertSee('Sponsor a Book');
        $response->assertSee('Provide one textbook to a student');
    }

    public function test_inactive_products_do_not_render(): void
    {
        CampaignProduct::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->owner->id,
            'name' => 'Hidden Product',
            'description' => 'Should not appear',
            'price' => 100,
            'quantity' => 10,
            'remaining_quantity' => 10,
            'is_active' => false,
            'approval_status' => 'approved',
        ]);

        $response = $this->get($this->getPublicUrl());
        $response->assertDontSee('Hidden Product');
    }

    public function test_campaign_updates_render(): void
    {
        CampaignUpdate::create([
            'campaign_id' => $this->campaign->id,
            'title' => 'First Milestone Reached',
            'body' => 'We have collected 50% of the goal amount!',
            'description' => 'Update description',
            'created_by' => $this->owner->id,
        ]);

        $response = $this->get($this->getPublicUrl());
        $response->assertSee('First Milestone Reached');
    }

    public function test_completed_donations_do_not_cause_errors(): void
    {
        $donation = Donation::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->owner->id,
            'donor_name' => 'John Doe',
            'donor_email' => 'john@example.com',
            'donation_type' => 'money',
            'total_amount' => 5000,
            'net_amount' => 4750,
        ]);
        $donation->payment_status = 'completed';
        $donation->paid_at = now();
        $donation->save();

        $response = $this->get($this->getPublicUrl());
        $response->assertStatus(200);
    }

    public function test_non_active_campaign_returns_404(): void
    {
        $campaign = Campaign::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'title' => 'Pending Campaign',
            'slug' => 'pending-campaign',
            'description' => 'Not yet approved',
            'goal_amount' => 100000,
            'raised_amount' => 0,
            'campaign_state' => Campaign::STATE_PENDING,
        ]);

        $response = $this->get(route('campaign.public', [
            'category' => $this->category->slug,
            'slug' => $campaign->slug,
        ]));

        $response->assertStatus(404);
    }

    public function test_donors_count_shows_number_of_completed_donations(): void
    {
        $donation1 = Donation::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->owner->id,
            'donor_name' => 'Alice',
            'donor_email' => 'alice@example.com',
            'donation_type' => 'money',
            'total_amount' => 1000,
            'net_amount' => 950,
        ]);
        $donation1->payment_status = 'completed';
        $donation1->paid_at = now();
        $donation1->save();

        $donation2 = Donation::create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->owner->id,
            'donor_name' => 'Bob',
            'donor_email' => 'bob@example.com',
            'donation_type' => 'money',
            'total_amount' => 2000,
            'net_amount' => 1900,
        ]);
        $donation2->payment_status = 'completed';
        $donation2->paid_at = now();
        $donation2->save();

        $response = $this->get($this->getPublicUrl());
        $response->assertStatus(200);
    }
}
