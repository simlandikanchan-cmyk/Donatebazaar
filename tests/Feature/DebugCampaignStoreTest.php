<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\FundraiserLevel;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DebugCampaignStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_debug_store(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'donor']);
        $level = FundraiserLevel::create([
            'level_number' => 1, 'level_name' => 'Starter',
            'max_goal_amount' => 500000.00, 'max_active_campaigns' => 5, 'is_default' => true,
        ]);
        UserFundraiserLevel::create([
            'user_id' => $user->id, 'current_level_id' => $level->id, 'status' => 'active',
        ]);
        $category = Category::create([
            'name' => 'Health', 'slug' => 'health', 'icon' => 'heart', 'color' => '#2563eb', 'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post('/campaign/store', [
            'title' => '<script>alert("xss")</script>',
            'description' => str_repeat('We need your support. ', 10),
            'goal_amount' => '100000',
            'category_id' => $category->id,
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'location' => 'Mumbai',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'updates' => [
                ['title' => 'First update', 'body' => 'Thanks'],
            ],
        ]);

        fwrite(STDERR, 'STATUS: '.$response->getStatusCode().PHP_EOL);
        fwrite(STDERR, 'REDIRECT: '.($response->headers->get('Location') ?? 'none').PHP_EOL);
        fwrite(STDERR, 'ERRORS: '.json_encode(session('errors') ? session('errors')->toArray() : []).PHP_EOL);
        fwrite(STDERR, 'CAMPAIGNS: '.(\App\Models\Campaign::count()).PHP_EOL);

        $this->assertTrue(true);
    }
}