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

class KycFormsValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'donor']);

        $level = FundraiserLevel::create([
            'level_number' => 1, 'level_name' => 'Starter',
            'max_goal_amount' => 500000.00, 'max_active_campaigns' => 5, 'is_default' => true,
        ]);

        UserFundraiserLevel::create([
            'user_id' => $this->user->id, 'current_level_id' => $level->id, 'status' => 'active',
        ]);

        $category = Category::create([
            'name' => 'Health', 'slug' => 'health', 'icon' => 'heart', 'color' => '#2563eb', 'is_active' => true,
        ]);

        $this->campaign = Campaign::factory()->create([
            'user_id' => $this->user->id, 'category_id' => $category->id, 'campaign_state' => 'pending',
        ]);
    }

    // ─── KYC Upload ───────────────────────────────────────────────────────

    public function test_kyc_upload_happy_path(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => 'pan',
            'document_number' => 'ABCDE1234F',
            'document_file' => UploadedFile::fake()->create('document.pdf', 500),
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_kyc_upload_document_type_required(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => '', 'document_number' => 'ABCDE1234F',
            'document_file' => UploadedFile::fake()->create('document.pdf', 500),
        ]);

        $response->assertSessionHasErrors('document_type');
    }

    public function test_kyc_upload_document_type_invalid(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => 'invalid_type', 'document_number' => 'ABCDE1234F',
            'document_file' => UploadedFile::fake()->create('document.pdf', 500),
        ]);

        $response->assertSessionHasErrors('document_type');
    }

    public function test_kyc_upload_document_number_required(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => 'pan', 'document_number' => '',
            'document_file' => UploadedFile::fake()->create('document.pdf', 500),
        ]);

        $response->assertSessionHasErrors('document_number');
    }

    public function test_kyc_upload_document_number_max_length(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => 'pan', 'document_number' => str_repeat('A', 256),
            'document_file' => UploadedFile::fake()->create('document.pdf', 500),
        ]);

        $response->assertSessionHasErrors('document_number');
    }

    public function test_kyc_upload_document_file_required(): void
    {
        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => 'pan', 'document_number' => 'ABCDE1234F',
        ]);

        $response->assertSessionHasErrors('document_file');
    }

    public function test_kyc_upload_document_file_invalid_type(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => 'pan', 'document_number' => 'ABCDE1234F',
            'document_file' => UploadedFile::fake()->create('script.exe', 500),
        ]);

        $response->assertSessionHasErrors('document_file');
    }

    public function test_kyc_upload_document_file_too_large(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => 'pan', 'document_number' => 'ABCDE1234F',
            'document_file' => UploadedFile::fake()->create('document.pdf', 6000),
        ]);

        $response->assertSessionHasErrors('document_file');
    }

    public function test_kyc_upload_guest_redirect(): void
    {
        Storage::fake('public');

        $response = $this->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => 'pan', 'document_number' => 'ABCDE1234F',
            'document_file' => UploadedFile::fake()->create('document.pdf', 500),
        ]);

        $response->assertRedirect('/login');
    }

    // ─── Edge Cases ───────────────────────────────────────────────────────

    public function test_kyc_upload_session_errors(): void
    {
        $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
            'document_type' => '', 'document_number' => '', 'document_file' => '',
        ]);

        $response->assertSessionHasErrors();
        $response->assertRedirect();
    }

    public function test_kyc_upload_all_valid_types(): void
    {
        Storage::fake('public');

        foreach (['pan', 'aadhaar', 'passport', 'other'] as $type) {
            $response = $this->actingAs($this->user)->post("/kyc/upload/{$this->campaign->id}", [
                'document_type' => $type,
                'document_number' => "NUM{$type}123",
                'document_file' => UploadedFile::fake()->image("{$type}.jpg", 500, 500),
            ]);

            $response->assertSessionHasNoErrors();
        }
    }
}
