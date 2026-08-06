<?php

namespace Tests\Feature\FormValidation;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\FundraiserLevel;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CampaignFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected FundraiserLevel $level;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);

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

        $this->category = Category::create([
            'name' => 'Health',
            'slug' => 'health',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);
    }

    private function validPayload(array $overrides = []): array
    {
        Storage::fake('public');

        return array_merge([
            'title' => 'Help Build a School',
            'description' => str_repeat('We need your support to build a school. ', 10),
            'goal_amount' => '100000',
            'category_id' => $this->category->id,
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'location' => 'Mumbai',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'updates' => [
                ['title' => 'First Update', 'body' => 'Campaign launch update.'],
            ],
        ], $overrides);
    }

    // ─── Campaign Create (StoreCampaignRequest) ───────────────────────────

    public function test_create_campaign_happy_path(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload());

        $response->assertSessionHasNoErrors();
        $created = Campaign::where('user_id', $this->user->id)->first();
        $response->assertRedirect(route('kyc.upload.form', $created->id));
    }

    public function test_create_campaign_guest_redirect(): void
    {
        Storage::fake('public');

        $response = $this->post('/campaign/store', $this->validPayload());

        $response->assertRedirect('/login');
    }

    public function test_create_campaign_title_required(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    public function test_create_campaign_title_max_length(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['title' => str_repeat('A', 256)]));

        $response->assertSessionHasErrors('title');
    }

    public function test_create_campaign_description_required(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['description' => '']));

        $response->assertSessionHasErrors('description');
    }

    public function test_create_campaign_description_max_length(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['description' => str_repeat('x', 20001)]));

        $response->assertSessionHasErrors('description');
    }

    public function test_create_campaign_goal_amount_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_create_campaign_goal_amount_numeric(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => 'not-a-number']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_create_campaign_goal_amount_min(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '0']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_create_campaign_goal_amount_max(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '500001']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_create_campaign_goal_amount_with_comma_sanitised(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '1,000,000']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_create_campaign_category_id_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['category_id' => '']));

        $response->assertSessionHasErrors('category_id');
    }

    public function test_create_campaign_category_id_exists(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['category_id' => 99999]));

        $response->assertSessionHasErrors('category_id');
    }

    public function test_create_campaign_cover_image_required(): void
    {
        $payload = $this->validPayload();
        unset($payload['cover_image']);

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_create_campaign_cover_image_invalid_type(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'cover_image' => UploadedFile::fake()->create('document.pdf', 100),
            ]));

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_create_campaign_cover_image_too_large(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'cover_image' => UploadedFile::fake()->create('large.jpg', 3000),
            ]));

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_create_campaign_start_date_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['start_date' => '']));

        $response->assertSessionHasErrors('start_date');
    }

    public function test_create_campaign_start_date_invalid_format(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['start_date' => 'not-a-date']));

        $response->assertSessionHasErrors('start_date');
    }

    public function test_create_campaign_end_date_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['end_date' => '']));

        $response->assertSessionHasErrors('end_date');
    }

    public function test_create_campaign_end_date_after_start_date(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'start_date' => now()->addDays(30)->toDateString(),
                'end_date' => now()->addDay()->toDateString(),
            ]));

        $response->assertSessionHasErrors('end_date');
    }

    public function test_create_campaign_video_url_invalid(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['video_url' => 'not-a-url']));

        $response->assertSessionHasErrors('video_url');
    }

    public function test_create_campaign_video_url_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['video_url' => 'https://www.youtube.com/watch?v=test']));

        $response->assertSessionHasNoErrors();
    }

    // ─── Campaign Update ──────────────────────────────────────────────────

    public function test_update_campaign_happy_path(): void
    {
        $campaign = Campaign::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->put("/campaign/{$campaign->id}", [
                'title' => 'Updated Title',
                'description' => str_repeat('Description content. ', 10),
                'goal_amount' => '50000',
                'category_id' => $this->category->id,
                'start_date' => now()->addDay()->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
            ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_update_campaign_title_required(): void
    {
        $campaign = Campaign::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->put("/campaign/{$campaign->id}", $this->validPayload(['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    public function test_update_campaign_goal_amount_required(): void
    {
        $campaign = Campaign::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->put("/campaign/{$campaign->id}", $this->validPayload(['goal_amount' => '']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_update_campaign_cover_image_optional(): void
    {
        $campaign = Campaign::factory()->create(['user_id' => $this->user->id]);

        $payload = $this->validPayload();
        unset($payload['cover_image']);

        $response = $this->actingAs($this->user)
            ->put("/campaign/{$campaign->id}", $payload);

        $response->assertSessionHasNoErrors();
    }

    // ─── Campaign Follow ──────────────────────────────────────────────────

    public function test_follow_campaign_requires_auth(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->post("/campaign/{$campaign->id}/follow");

        $response->assertRedirect('/login');
    }

    // ─── Campaign Pause / Resume ──────────────────────────────────────────

    public function test_pause_campaign_requires_auth(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->post("/campaign/{$campaign->id}/pause");

        $response->assertRedirect('/login');
    }

    public function test_resume_campaign_requires_auth(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->post("/campaign/{$campaign->id}/resume");

        $response->assertRedirect('/login');
    }

    // ─── Campaign Resubmit ────────────────────────────────────────────────

    public function test_resubmit_campaign_requires_auth(): void
    {
        $campaign = Campaign::factory()->create();

        $response = $this->post("/campaigns/{$campaign->id}/resubmit");

        $response->assertRedirect('/login');
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_create_campaign_sql_injection_in_title(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'title' => "'; DELETE FROM campaigns; --",
            ]));

        $response->assertSessionHasNoErrors();
    }

    public function test_create_campaign_xss_in_title(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'title' => '<script>alert("xss")</script>',
            ]));

        $response->assertSessionHasNoErrors();
        $campaign = Campaign::where('user_id', $this->user->id)->first();
        $this->assertStringNotContainsString('<script>', $campaign->title);
    }

    public function test_create_campaign_special_chars_in_title(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'title' => "School's Fundraiser — 100% for Kids! #Help",
            ]));

        $response->assertSessionHasNoErrors();
    }

    public function test_create_campaign_negative_goal(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '-100']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_create_campaign_goal_decimal(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '100.50']));

        $response->assertSessionHasNoErrors();
    }

    public function test_create_campaign_session_errors_on_failure(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['title' => '']));

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    public function test_create_campaign_old_input_retained(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'title' => '',
                'description' => 'Some description',
            ]));

        $response->assertRedirect();
        $session = $response->getSession()->get('_old_input');
        $this->assertEquals('Some description', $session['description'] ?? null);
    }
}
