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
use Tests\TestCase;

class CampaignCreationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category;

    protected FundraiserLevel $level;

    protected function setUp(): void
    {
        parent::setUp();

        // This test user is a normal "donor" so the fundraiser-level gate applies.
        $this->user = User::factory()->create(['role' => 'donor']);

        // A Starter-equivalent level with a generous cap so the happy path passes,
        // but we can still test the cap/active-count gates explicitly elsewhere.
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

        $base = [
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

        return array_merge($base, $overrides);
    }

    // ── A. Happy path ─────────────────────────────────────────────────────
    public function test_authenticated_user_can_create_a_campaign(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload());

        $created = Campaign::where('user_id', $this->user->id)->first();
        $response->assertRedirect(route('kyc.upload.form', $created->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('campaigns', [
            'user_id' => $this->user->id,
            'title' => 'Help Build a School',
            'campaign_state' => Campaign::STATE_PENDING,
            'raised_amount' => 0,
        ]);

        // Cover image is converted to webp and persisted as a relative path.
        $saved = Campaign::where('user_id', $this->user->id)->first();
        $this->assertNotNull($saved->cover_image);
        $this->assertStringStartsWith('images/', $saved->cover_image);
        $this->assertStringEndsWith('.webp', $saved->cover_image);
    }

    public function test_guest_cannot_create_a_campaign(): void
    {
        Storage::fake('public');

        $response = $this->post('/campaign/store', $this->validPayload());

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('campaigns', 0);
    }

    // ── B. Validation rules ───────────────────────────────────────────────
    public function test_description_requires_max_length(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'description' => str_repeat('x', 20001),
            ]));

        $response->assertSessionHasErrors('description');
        $this->assertDatabaseCount('campaigns', 0);
    }

    public function test_goal_amount_enforces_min_and_max(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '0']));
        $response->assertSessionHasErrors('goal_amount');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '500001']));
        $response->assertSessionHasErrors('goal_amount');

        $this->assertDatabaseCount('campaigns', 0);
    }

    public function test_goal_amount_with_comma_is_sanitised_then_capped(): void
    {
        Storage::fake('public');

        // 1,000,000 → 1000000 → exceeds max:500000 → rejected.
        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '1,000,000']));

        $response->assertSessionHasErrors('goal_amount');
        $this->assertDatabaseCount('campaigns', 0);
    }

    public function test_invalid_category_is_rejected(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['category_id' => 999999]));

        $response->assertSessionHasErrors('category_id');
    }

    public function test_end_date_before_start_date_is_rejected(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload([
                'start_date' => now()->addDays(30)->toDateString(),
                'end_date' => now()->addDay()->toDateString(),
            ]));

        $response->assertSessionHasErrors('end_date');
    }

    public function test_at_least_one_update_is_required(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['updates' => []]));

        $response->assertSessionHasErrors('updates');
        $this->assertDatabaseCount('campaigns', 0);
    }

    // ── C. File-upload hardening (P0 fix #4) ──────────────────────────────
    public function test_malicious_product_image_is_rejected(): void
    {
        Storage::fake('public');

        $payload = $this->validPayload();
        $payload['products'] = [[
            'name' => 'T-shirt',
            'image' => UploadedFile::fake()->create('evil.php', 10, 'image/png'),
        ]];

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('products.0.image');
        Storage::disk('public')->assertMissing('campaign-products/evil.php');
    }

    public function test_oversized_product_image_is_rejected(): void
    {
        Storage::fake('public');

        $payload = $this->validPayload();
        $payload['products'] = [[
            'name' => 'T-shirt',
            'image' => UploadedFile::fake()->create('big.png', 3000, 'image/png'),
        ]];

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('products.0.image');
    }

    public function test_malicious_update_document_is_rejected(): void
    {
        Storage::fake('public');

        $payload = $this->validPayload();
        $payload['updates'] = [[
            'title' => 'Doc',
            'body' => 'Note',
            'document' => UploadedFile::fake()->create('script.html', 10, 'text/html'),
        ]];

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $payload);

        $response->assertSessionHasErrors('updates.0.document');
    }

    // ── D. Fundraiser-level business gate ─────────────────────────────────
    public function test_goal_above_level_cap_is_rejected(): void
    {
        Storage::fake('public');

        $this->level->update(['max_goal_amount' => 50000.00]);

        $response = $this->actingAs($this->user)
            ->post('/campaign/store', $this->validPayload(['goal_amount' => '100000']));

        $response->assertSessionHasErrors('goal_amount');
        $this->assertDatabaseCount('campaigns', 0);
    }

    // ── Regression: IDOR fix on show (P0 fix #3) ──────────────────────────
    public function test_user_cannot_view_another_users_campaign(): void
    {
        $other = User::factory()->create(['role' => 'donor']);
        UserFundraiserLevel::create([
            'user_id' => $other->id,
            'current_level_id' => $this->level->id,
            'status' => 'active',
        ]);

        $campaign = Campaign::create([
            'user_id' => $other->id,
            'category_id' => $this->category->id,
            'title' => 'Private Campaign',
            'slug' => 'private-campaign',
            'description' => 'secret',
            'goal_amount' => 1000,
            'campaign_state' => Campaign::STATE_PENDING,
        ]);

        $response = $this->actingAs($this->user)
            ->get('/campaign/'.$campaign->id);

        $response->assertForbidden(); // 403
    }
}
