<?php

namespace Tests\Feature\FormValidation;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Event;
use App\Models\FundraiserLevel;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);

        $level = FundraiserLevel::create([
            'level_number' => 1,
            'level_name' => 'Starter',
            'max_goal_amount' => 500000.00,
            'max_active_campaigns' => 5,
            'is_default' => true,
        ]);

        UserFundraiserLevel::create([
            'user_id' => $this->user->id,
            'current_level_id' => $level->id,
            'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Health', 'slug' => 'health', 'icon' => 'heart', 'color' => '#2563eb', 'is_active' => true,
        ]);

        $this->campaign = Campaign::factory()->create(['user_id' => $this->user->id, 'category_id' => $category->id]);
    }

    private function validEventPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Fundraising Gala Night',
            'description' => 'A grand gala to raise funds for the school project.',
            'event_date' => now()->addMonth()->format('Y-m-d'),
            'start_time' => '18:00',
            'end_time' => '22:00',
            'goal_amount' => '50000',
            'max_participants' => '100',
        ], $overrides);
    }

    // ─── Event Create ─────────────────────────────────────────────────────

    public function test_create_event_happy_path(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload());

        $response->assertSessionHasNoErrors();
    }

    public function test_create_event_guest_redirect(): void
    {
        $response = $this->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload());

        $response->assertRedirect('/login');
    }

    public function test_create_event_title_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    public function test_create_event_title_max_length(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['title' => str_repeat('A', 256)]));

        $response->assertSessionHasErrors('title');
    }

    public function test_create_event_description_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['description' => '']));

        $response->assertSessionHasErrors('description');
    }

    public function test_create_event_event_date_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['event_date' => '']));

        $response->assertSessionHasErrors('event_date');
    }

    public function test_create_event_event_date_invalid_format(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['event_date' => 'not-a-date']));

        $response->assertSessionHasErrors('event_date');
    }

    public function test_create_event_event_date_after_or_equal_today(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload([
                'event_date' => now()->subDay()->format('Y-m-d'),
            ]));

        $response->assertSessionHasErrors('event_date');
    }

    public function test_create_event_start_time_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['start_time' => '']));

        $response->assertSessionHasErrors('start_time');
    }

    public function test_create_event_end_time_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['end_time' => '']));

        $response->assertSessionHasErrors('end_time');
    }

    public function test_create_event_end_time_after_start_time(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload([
                'start_time' => '14:00',
                'end_time' => '12:00',
            ]));

        $response->assertSessionHasErrors('end_time');
    }

    public function test_create_event_goal_amount_numeric(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['goal_amount' => 'not-a-number']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_create_event_goal_amount_min_zero(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['goal_amount' => '-1']));

        $response->assertSessionHasErrors('goal_amount');
    }

    public function test_create_event_max_participants_integer(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['max_participants' => 'not-int']));

        $response->assertSessionHasErrors('max_participants');
    }

    public function test_create_event_max_participants_min_one(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['max_participants' => '0']));

        $response->assertSessionHasErrors('max_participants');
    }

    public function test_create_event_cover_image_invalid_type(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload([
                'cover_image' => UploadedFile::fake()->create('document.pdf', 100),
            ]));

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_create_event_cover_image_oversized(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload([
                'cover_image' => UploadedFile::fake()->create('large.jpg', 3000),
            ]));

        $response->assertSessionHasErrors('cover_image');
    }

    // ─── Event Update ─────────────────────────────────────────────────────

    public function test_update_event_happy_path(): void
    {
        $event = Event::factory()->create(['campaign_id' => $this->campaign->id, 'user_id' => $this->user->id, 'slug' => 'event-' . uniqid()]);

        $response = $this->actingAs($this->user)
            ->put("/events/{$event->id}", [
                'title' => 'Updated Event Title',
                'description' => 'Updated description for the event.',
                'event_date' => now()->addMonth()->format('Y-m-d'),
                'start_time' => '10:00',
                'end_time' => '12:00',
                'goal_amount' => '25000',
                'max_participants' => '50',
                'cover_image' => UploadedFile::fake()->image('new_cover.jpg', 800, 600),
            ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_update_event_title_required(): void
    {
        $event = Event::factory()->create(['campaign_id' => $this->campaign->id, 'user_id' => $this->user->id, 'slug' => 'event-' . uniqid()]);

        $response = $this->actingAs($this->user)
            ->put("/events/{$event->id}", $this->validEventPayload(['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    public function test_update_event_end_time_after_start_time(): void
    {
        $event = Event::factory()->create(['campaign_id' => $this->campaign->id, 'user_id' => $this->user->id, 'slug' => 'event-' . uniqid()]);

        $response = $this->actingAs($this->user)
            ->put("/events/{$event->id}", $this->validEventPayload([
                'start_time' => '14:00',
                'end_time' => '12:00',
            ]));

        $response->assertSessionHasErrors('end_time');
    }

    // ─── Event Registration ───────────────────────────────────────────────

    public function test_event_registration_happy_path(): void
    {
        $event = Event::factory()->create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'slug' => 'event-' . uniqid(),
            'status' => Event::STATUS_ACTIVE,
            'allow_registrations' => true,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9876543210',
            'message' => 'Looking forward to this event!',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_event_registration_name_required(): void
    {
        $event = Event::factory()->create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'slug' => 'event-' . uniqid(),
            'status' => Event::STATUS_ACTIVE,
            'allow_registrations' => true,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => '',
            'email' => 'john@example.com',
            'phone' => '9876543210',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_event_registration_email_required(): void
    {
        $event = Event::factory()->create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'slug' => 'event-' . uniqid(),
            'status' => Event::STATUS_ACTIVE,
            'allow_registrations' => true,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Doe',
            'email' => '',
            'phone' => '9876543210',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_event_registration_email_format(): void
    {
        $event = Event::factory()->create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'slug' => 'event-' . uniqid(),
            'status' => Event::STATUS_ACTIVE,
            'allow_registrations' => true,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'phone' => '9876543210',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_event_registration_phone_required(): void
    {
        $event = Event::factory()->create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'slug' => 'event-' . uniqid(),
            'status' => Event::STATUS_ACTIVE,
            'allow_registrations' => true,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_event_registration_special_chars(): void
    {
        $event = Event::factory()->create([
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'slug' => 'event-' . uniqid(),
            'status' => Event::STATUS_ACTIVE,
            'allow_registrations' => true,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => "O'Brian <test>",
            'email' => 'john@example.com',
            'phone' => '9876543210',
        ]);

        $response->assertSessionHasNoErrors();
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_create_event_session_errors_on_failure(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['title' => '']));

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    public function test_create_event_null_goal_amount(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['goal_amount' => null]));

        $response->assertSessionHasNoErrors();
    }

    public function test_create_event_empty_participants_field(): void
    {
        $response = $this->actingAs($this->user)
            ->post("/campaign/{$this->campaign->id}/events", $this->validEventPayload(['max_participants' => '']));

        $response->assertSessionHasNoErrors();
    }
}
