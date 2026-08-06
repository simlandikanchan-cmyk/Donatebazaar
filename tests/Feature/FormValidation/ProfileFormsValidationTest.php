<?php

namespace Tests\Feature\FormValidation;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => Hash::make('currentPass1')]);
    }

    // ─── Profile Update ───────────────────────────────────────────────────

    public function test_profile_update_happy_path(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', [
                'name' => 'Updated Name',
                'phone' => '9876543210',
                'bio' => 'A short bio about me.',
            ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_profile_update_name_required(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', ['name' => '', 'phone' => '', 'bio' => '']);

        $response->assertSessionHasErrors('name');
    }

    public function test_profile_update_name_max_length(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', ['name' => str_repeat('A', 256)]);

        $response->assertSessionHasErrors('name');
    }

    public function test_profile_update_guest_redirect(): void
    {
        $response = $this->patch('/profile', ['name' => 'Test']);

        $response->assertRedirect('/login');
    }

    public function test_profile_update_phone_max_length(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', ['name' => 'Test', 'phone' => str_repeat('1', 21)]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_profile_update_bio_max_length(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', ['name' => 'Test', 'bio' => str_repeat('A', 1001)]);

        $response->assertSessionHasErrors('bio');
    }

    public function test_profile_update_special_chars_in_name(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', ['name' => "O'Brien-Smith & Co. (Test)", 'phone' => '9876543210']);

        $response->assertSessionHasNoErrors();
    }

    // ─── Avatar Upload ────────────────────────────────────────────────────

    public function test_avatar_upload_happy_path(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/profile/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg', 300, 300),
            ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_avatar_upload_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/profile/avatar', ['avatar' => '']);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_upload_invalid_type(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/profile/avatar', [
                'avatar' => UploadedFile::fake()->create('document.pdf', 100),
            ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_upload_too_large(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/profile/avatar', [
                'avatar' => UploadedFile::fake()->image('large.jpg', 3000, 3000)->size(3000),
            ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_upload_guest_redirect(): void
    {
        $response = $this->post('/profile/avatar');

        $response->assertRedirect('/login');
    }

    // ─── Cover Image Upload ───────────────────────────────────────────────

    public function test_cover_upload_happy_path(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/profile/cover', [
                'cover_image' => UploadedFile::fake()->image('cover.jpg', 1200, 400),
            ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_cover_upload_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/profile/cover', ['cover_image' => '']);

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_cover_upload_invalid_type(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/profile/cover', [
                'cover_image' => UploadedFile::fake()->create('document.pdf', 100),
            ]);

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_cover_upload_too_large(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/profile/cover', [
                'cover_image' => UploadedFile::fake()->create('large.jpg', 6000),
            ]);

        $response->assertSessionHasErrors('cover_image');
    }

    // ─── Password Update (Profile) ────────────────────────────────────────

    public function test_profile_password_update_happy_path(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/profile/password', [
                'current_password' => 'currentPass1',
                'password' => 'NewP@ss123',
                'password_confirmation' => 'NewP@ss123',
            ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_profile_password_update_current_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/profile/password', [
                'current_password' => '',
                'password' => 'NewP@ss123',
                'password_confirmation' => 'NewP@ss123',
            ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_profile_password_update_new_required(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/profile/password', [
                'current_password' => 'currentPass1',
                'password' => '',
                'password_confirmation' => '',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_profile_password_update_min_length(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/profile/password', [
                'current_password' => 'currentPass1',
                'password' => 'Short1',
                'password_confirmation' => 'Short1',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_profile_password_update_confirmation_mismatch(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/profile/password', [
                'current_password' => 'currentPass1',
                'password' => 'NewP@ss123',
                'password_confirmation' => 'Different1',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_profile_password_update_wrong_current(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/profile/password', [
                'current_password' => 'wrongCurrent1',
                'password' => 'NewP@ss123',
                'password_confirmation' => 'NewP@ss123',
            ]);

        $response->assertSessionHasErrors('current_password');
    }

    // ─── Account Deletion ─────────────────────────────────────────────────

    public function test_account_deletion_requires_password(): void
    {
        $response = $this->actingAs($this->user)
            ->delete('/profile', ['password' => 'currentPass1']);

        $response->assertSessionHasNoErrors();
    }

    public function test_account_deletion_wrong_password(): void
    {
        $response = $this->actingAs($this->user)
            ->delete('/profile', ['password' => 'wrong']);

        $response->assertSessionHasErrorsIn('userDeletion', 'password');
    }

    public function test_account_deletion_guest_redirect(): void
    {
        $response = $this->delete('/profile', ['password' => 'anything']);

        $response->assertRedirect('/login');
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_profile_update_session_errors(): void
    {
        $response = $this->actingAs($this->user)
            ->patch('/profile', ['name' => '']);

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    public function test_avatar_upload_malicious_file(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->post('/profile/avatar', [
                'avatar' => UploadedFile::fake()->create('evil.php', 100, 'image/png'),
            ]);

        $response->assertSessionHasErrors('avatar');
    }
}
