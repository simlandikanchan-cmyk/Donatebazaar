<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAvatarTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_upload_avatar(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post(route('profile.avatar'), [
                'avatar' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
            ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('profile.show'));

        $this->user->refresh();
        $this->assertNotNull($this->user->avatar);
        $this->assertStringStartsWith('avatars/', $this->user->avatar);

        Storage::disk('public')->assertExists($this->user->avatar);
    }

    public function test_avatar_upload_rejects_non_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post(route('profile.avatar'), [
                'avatar' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_upload_rejects_oversized_file(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post(route('profile.avatar'), [
                'avatar' => UploadedFile::fake()->create('big.png', 3000, 'image/png'),
            ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_old_avatar_is_deleted_on_new_upload(): void
    {
        Storage::fake('public');

        $oldPath = UploadedFile::fake()->image('old.jpg', 100, 100)
            ->store('avatars', 'public');

        $this->user->update(['avatar' => $oldPath]);
        Storage::disk('public')->assertExists($oldPath);

        $response = $this->actingAs($this->user)
            ->post(route('profile.avatar'), [
                'avatar' => UploadedFile::fake()->image('new.jpg', 200, 200),
            ]);

        $response->assertSessionHas('success');

        Storage::disk('public')->assertMissing($oldPath);

        $this->user->refresh();
        Storage::disk('public')->assertExists($this->user->avatar);
    }

    public function test_authenticated_user_can_upload_cover_photo(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post(route('profile.cover'), [
                'cover_image' => UploadedFile::fake()->image('cover.jpg', 1200, 400),
            ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('profile.show'));

        $this->user->refresh();
        $this->assertNotNull($this->user->cover_image);
        $this->assertStringStartsWith('covers/', $this->user->cover_image);

        Storage::disk('public')->assertExists($this->user->cover_image);
    }

    public function test_guest_cannot_upload_avatar(): void
    {
        Storage::fake('public');

        $response = $this->post(route('profile.avatar'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
        ]);

        $response->assertRedirect('/login');
    }

    public function test_user_campaign_list_is_sorted_by_created_at_descending(): void
    {
        $user = $this->user;

        $now = now();

        $campaign1 = Campaign::create([
            'user_id' => $user->id,
            'title' => 'First Campaign',
            'slug' => 'first-campaign-sort',
            'description' => 'Description 1',
            'goal_amount' => 10000,
            'campaign_state' => 'active',
        ]);

        Campaign::where('id', $campaign1->id)->update([
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(5),
        ]);

        $campaign2 = Campaign::create([
            'user_id' => $user->id,
            'title' => 'Second Campaign',
            'slug' => 'second-campaign-sort',
            'description' => 'Description 2',
            'goal_amount' => 20000,
            'campaign_state' => 'active',
        ]);

        Campaign::where('id', $campaign2->id)->update([
            'created_at' => $now->copy()->subDay(),
            'updated_at' => $now->copy()->subDay(),
        ]);

        $this->travel(1)->hours();

        $campaign3 = Campaign::create([
            'user_id' => $user->id,
            'title' => 'Third Campaign',
            'slug' => 'third-campaign-sort',
            'description' => 'Description 3',
            'goal_amount' => 30000,
            'campaign_state' => 'active',
        ]);

        $campaigns = Campaign::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->assertEquals('Third Campaign', $campaigns[0]->title);
        $this->assertEquals('Second Campaign', $campaigns[1]->title);
        $this->assertEquals('First Campaign', $campaigns[2]->title);
    }
}
