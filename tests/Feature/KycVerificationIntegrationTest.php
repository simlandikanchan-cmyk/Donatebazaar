<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\FundraiserLevel;
use App\Models\KycVerification;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KycVerificationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;
    private Category $category;
    private FundraiserLevel $level;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'ngo']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        $this->category = Category::create([
            'name' => 'Health',
            'slug' => 'health',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);

        $this->level = FundraiserLevel::create([
            'level_number' => 1,
            'level_name' => 'Starter',
            'max_goal_amount' => 500000.00,
            'max_active_campaigns' => 5,
            'is_default' => true,
        ]);

        UserFundraiserLevel::create([
            'user_id' => $this->user->id,
            'current_level_id' => $this->level->id,
            'status' => 'active',
        ]);
    }

    public function test_unverified_user_can_create_campaign(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)
            ->post('/campaign/store', [
                'title' => 'My Unverified Campaign',
                'description' => 'Campaign created without KYC verification check',
                'goal_amount' => '50000',
                'category_id' => $this->category->id,
                'cover_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
                'location' => 'Mumbai',
                'start_date' => now()->addDay()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'updates' => [
                    ['title' => 'Kickoff', 'body' => 'Campaign launched'],
                ],
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('campaigns', [
            'title' => 'My Unverified Campaign',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_campaign_resume_blocked_without_kyc(): void
    {
        $campaign = Campaign::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Paused No KYC',
            'slug' => 'paused-no-kyc',
            'description' => 'Paused campaign without KYC',
            'goal_amount' => 50000,
            'campaign_state' => Campaign::STATE_PAUSED,
        ]);

        $this->actingAs($this->user)
            ->post(route('campaign.resume', $campaign))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_PAUSED,
        ]);
    }

    public function test_campaign_resume_succeeds_with_approved_kyc(): void
    {
        $kyc = KycVerification::create([
            'user_id' => $this->user->id,
        ]);
        $kyc->status = KycVerification::STATUS_APPROVED;
        $kyc->verified_by = $this->admin->id;
        $kyc->verified_at = now();
        $kyc->save();

        $campaign = Campaign::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'title' => 'Resume With KYC',
            'slug' => 'resume-with-kyc',
            'description' => 'Campaign that can be resumed after KYC',
            'goal_amount' => 50000,
            'campaign_state' => Campaign::STATE_PAUSED,
        ]);

        $this->actingAs($this->user)
            ->post(route('campaign.resume', $campaign))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'campaign_state' => Campaign::STATE_ACTIVE,
        ]);
    }

    public function test_kyc_resubmission_after_rejection(): void
    {
        $kyc = KycVerification::create([
            'user_id' => $this->user->id,
        ]);
        $kyc->status = KycVerification::STATUS_REJECTED;
        $kyc->verified_by = $this->admin->id;
        $kyc->rejection_reason = 'Invalid documents';
        $kyc->save();

        $this->assertDatabaseHas('kyc_verifications', [
            'id' => $kyc->id,
            'status' => KycVerification::STATUS_REJECTED,
        ]);

        $kyc->status = KycVerification::STATUS_APPROVED;
        $kyc->verified_by = $this->admin->id;
        $kyc->verified_at = now();
        $kyc->rejection_reason = null;
        $kyc->save();

        $this->assertDatabaseHas('kyc_verifications', [
            'id' => $kyc->id,
            'status' => KycVerification::STATUS_APPROVED,
        ]);
    }
}
