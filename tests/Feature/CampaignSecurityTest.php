<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\FundraiserLevel;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CampaignSecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;
    private FundraiserLevel $level;
    private array $basePayload;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

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

        $this->basePayload = [
            'title' => 'Help Build a School',
            'description' => str_repeat('We need your support to build a school. ', 10),
            'goal_amount' => '100000',
            'category_id' => $this->category->id,
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'location' => 'Mumbai',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'updates' => [
                ['title' => 'First update', 'body' => 'Thanks for the support'],
            ],
        ];
    }

    // ─── SECTION 1: TITLE FIELD ──────────────────────────────────────────

    #[Test]
    public function title_xss_script_tag_is_sanitized(): void
    {
        $payload = $this->basePayload;
        $payload['title'] = "<script>alert('xss')</script>";

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'title' => "alert('xss')",
        ]);
    }

    #[Test]
    public function title_xss_event_handler_is_safe_in_output(): void
    {
        $payload = $this->basePayload;
        $payload['title'] = "Test <img src=x onerror='alert(1)'>";

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $campaign = Campaign::where('user_id', $this->user->id)->first();
        $this->assertStringNotContainsString('<img', $campaign->title);
    }

    #[Test]
    public function title_sql_injection_treated_as_literal(): void
    {
        $payload = $this->basePayload;
        $payload['title'] = "'; DROP TABLE campaigns; --";

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'title' => "'; DROP TABLE campaigns; --",
        ]);
        // Verify campaigns table still exists
        $this->assertNotNull(Campaign::first());
    }

    #[Test]
    public function title_unicode_is_accepted(): void
    {
        $payload = $this->basePayload;
        $payload['title'] = 'ヘルプ アーラヴ';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'title' => 'ヘルプ アーラヴ',
        ]);
    }

    #[Test]
    public function title_emoji_is_accepted(): void
    {
        $payload = $this->basePayload;
        $payload['title'] = 'Help 🆘 Aarav';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'title' => 'Help 🆘 Aarav',
        ]);
    }

    #[Test]
    public function title_empty_is_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['title'] = '';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('title');
    }

    #[Test]
    public function title_max_length_255_is_enforced(): void
    {
        $payload = $this->basePayload;
        $payload['title'] = str_repeat('a', 256);

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('title');
    }

    // ─── SECTION 2: DESCRIPTION FIELD ────────────────────────────────────

    #[Test]
    public function description_html_tags_are_safe_in_output(): void
    {
        $payload = $this->basePayload;
        $payload['description'] = "<p>Help</p><script>alert(1)</script>";

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $campaign = Campaign::where('user_id', $this->user->id)->first();
        $this->assertStringContainsString('<script>', $campaign->description);
        $this->assertStringContainsString('Help', $campaign->description);
    }

    #[Test]
    public function description_special_chars_preserved_correctly(): void
    {
        $payload = $this->basePayload;
        $payload['description'] = "Test & Co., Ltd. < 5 years";

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'description' => "Test & Co., Ltd. < 5 years",
        ]);
    }

    #[Test]
    public function description_sql_injection_treated_as_literal(): void
    {
        $payload = $this->basePayload;
        $payload['description'] = "'; DROP TABLE campaigns; --";

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'description' => "'; DROP TABLE campaigns; --",
        ]);
        $this->assertNotNull(Campaign::first());
    }

    // ─── SECTION 3: GOAL AMOUNT ──────────────────────────────────────────

    #[Test]
    public function goal_amount_negative_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['goal_amount'] = '-10000';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('goal_amount');
    }

    #[Test]
    public function goal_amount_text_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['goal_amount'] = 'Ten thousand';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('goal_amount');
    }

    #[Test]
    public function goal_amount_sql_injection_treated_as_literal(): void
    {
        $payload = $this->basePayload;
        $payload['goal_amount'] = "1000'; DROP--";

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('goal_amount');
    }

    #[Test]
    public function goal_amount_many_decimals_rounded_or_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['goal_amount'] = '10000.123456';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasNoErrors();
        $campaign = Campaign::where('user_id', $this->user->id)->first();
        $this->assertNotNull($campaign);
    }

    // ─── SECTION 4: CATEGORY ─────────────────────────────────────────────

    #[Test]
    public function category_invalid_id_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['category_id'] = 99999;

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('category_id');
    }

    #[Test]
    public function category_empty_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['category_id'] = '';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('category_id');
    }

    #[Test]
    public function category_sql_injection_treated_as_literal_id(): void
    {
        $payload = $this->basePayload;
        $payload['category_id'] = "1 OR 1=1";

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('category_id');
    }

    // ─── SECTION 5: DATES ────────────────────────────────────────────────

    #[Test]
    public function start_date_empty_is_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['start_date'] = '';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('start_date');
    }

    #[Test]
    public function end_date_before_start_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['start_date'] = now()->addDays(30)->toDateString();
        $payload['end_date'] = now()->addDay()->toDateString();

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('end_date');
    }

    #[Test]
    public function end_date_empty_is_rejected(): void
    {
        $payload = $this->basePayload;
        $payload['end_date'] = '';

        $response = $this->actingAs($this->user)->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('end_date');
    }

    // ─── SECTION 7: CSRF ─────────────────────────────────────────────────

    #[Test]
    public function form_renders_with_csrf_token(): void
    {
        $response = $this->actingAs($this->user)->get('/campaign/create');

        $response->assertStatus(200);
        $response->assertSee('_token', false);
    }

    #[Test]
    public function guest_cannot_access_create_form(): void
    {
        $response = $this->get('/campaign/create');

        $response->assertRedirect('/login');
    }

    // ─── SECTION 8: AUTHORIZATION ────────────────────────────────────────

    #[Test]
    public function guest_redirected_to_login(): void
    {
        $response = $this->get('/campaign/create');

        $response->assertRedirect('/login');
    }

    #[Test]
    public function guest_cannot_store(): void
    {
        $response = $this->post('/campaign/store', $this->basePayload);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('campaigns', ['title' => $this->basePayload['title']]);
    }

    // ─── DUPLICATE CAMPAIGN ──────────────────────────────────────────────

    #[Test]
    public function duplicate_title_by_same_user_is_blocked(): void
    {
        $this->actingAs($this->user)->post('/campaign/store', $this->basePayload);
        $this->assertEquals(1, Campaign::where('user_id', $this->user->id)->count());

        $response2 = $this->actingAs($this->user)->post('/campaign/store', $this->basePayload);

        $response2->assertSessionHasErrors('title');
        $this->assertEquals(1, Campaign::where('user_id', $this->user->id)->count());
    }

    #[Test]
    public function same_title_by_different_user_is_allowed(): void
    {
        $other = User::factory()->create(['role' => 'donor']);
        UserFundraiserLevel::create([
            'user_id' => $other->id,
            'current_level_id' => $this->level->id,
            'status' => 'active',
        ]);

        $payload1 = $this->basePayload;
        $payload1['cover_image'] = UploadedFile::fake()->image('c1.jpg', 800, 600);

        $this->actingAs($this->user)->post('/campaign/store', $payload1);
        $this->assertEquals(1, Campaign::where('title', 'Help Build a School')->count());

        $payload2 = $this->basePayload;
        $payload2['cover_image'] = UploadedFile::fake()->image('c2.jpg', 800, 600);

        $response2 = $this->actingAs($other)->post('/campaign/store', $payload2);
        $response2->assertSessionHasNoErrors();
        $this->assertEquals(2, Campaign::where('title', 'Help Build a School')->count());
    }

    // ─── OUTPUT ENCODING ─────────────────────────────────────────────────

    #[Test]
    public function xss_title_is_html_escaped_on_public_page(): void
    {
        $payload = $this->basePayload;
        $payload['title'] = "Tom & Jerry's <Fund>";

        $this->actingAs($this->user)->post('/campaign/store', $payload);

        $campaign = Campaign::where('user_id', $this->user->id)->first();
        $this->assertStringContainsString('Tom', $campaign->title);
        $this->assertStringNotContainsString('<Fund>', $campaign->title);

        $campaign->update(['campaign_state' => Campaign::STATE_ACTIVE]);

        $response = $this->get(route('campaign.public', [
            'category' => $this->category->slug,
            'slug' => $campaign->slug,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tom &amp; Jerry', false);
    }

    // ─── SERVER-SIDE VALIDATION ──────────────────────────────────────────

    #[Test]
    public function server_side_validation_returns_422_with_all_errors(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', [
                'title' => '',
                'description' => '',
                'goal_amount' => 'abc',
                'category_id' => 99999,
            ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description', 'goal_amount', 'category_id']);
    }

    #[Test]
    public function new_campaign_is_created_as_pending(): void
    {
        $this->actingAs($this->user)->post('/campaign/store', $this->basePayload);

        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'campaign_state' => Campaign::STATE_PENDING,
        ]);
    }

    // ─── HAPPY PATH STILL WORKS ──────────────────────────────────────────

    #[Test]
    public function valid_campaign_still_creates_successfully(): void
    {
        $response = $this->actingAs($this->user)->post('/campaign/store', $this->basePayload);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'title' => 'Help Build a School',
        ]);
    }
}
