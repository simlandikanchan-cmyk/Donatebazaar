<?php

namespace Tests\Feature;

use App\Models\FundraiserLevel;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use App\Services\FundraiserLevelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundraiserLevelAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private FundraiserLevel $defaultLevel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultLevel = FundraiserLevel::create([
            'level_number' => 1,
            'level_name' => 'Starter',
            'max_goal_amount' => 500000.00,
            'max_active_campaigns' => 5,
            'is_default' => true,
        ]);

        // A non-default level that must NOT be picked as a fallback.
        FundraiserLevel::create([
            'level_number' => 2,
            'level_name' => 'Premium',
            'max_goal_amount' => 9000000.00,
            'max_active_campaigns' => 20,
            'is_default' => false,
        ]);
    }

    public function test_registration_creates_single_default_level_row(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'New Fundraiser',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $user = User::where('email', 'new@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame(1, UserFundraiserLevel::where('user_id', $user->id)->count());

        $this->assertDatabaseHas('user_fundraiser_levels', [
            'user_id' => $user->id,
            'current_level_id' => $this->defaultLevel->id,
        ]);
    }

    public function test_registration_does_not_create_duplicate_level_rows(): void
    {
        $user = User::factory()->create(['role' => 'donor']);
        $user->ensureDefaultLevel();
        $user->ensureDefaultLevel();
        $user->ensureDefaultLevel();

        $this->assertSame(1, UserFundraiserLevel::where('user_id', $user->id)->count());
    }

    public function test_max_campaign_goal_returns_zero_when_no_level_assigned(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        // Delete every level so neither an assigned row nor a default fallback
        // exists — must NOT fabricate the old 25000 hardcoded default.
        FundraiserLevel::query()->delete();

        $this->assertSame(0.0, $user->maxCampaignGoal());
    }

    public function test_max_campaign_goal_uses_default_level_as_fallback(): void
    {
        $user = User::factory()->create(['role' => 'donor']);

        $this->assertSame(
            (float) $this->defaultLevel->max_goal_amount,
            $user->maxCampaignGoal()
        );
    }

    public function test_resolve_level_uses_default_not_arbitrary_first_row(): void
    {
        $service = app(FundraiserLevelService::class);
        $user = User::factory()->create(['role' => 'donor']);

        // Without an assigned row, resolveLevel must fall back to the default
        // (is_default = true), NOT the first row in the table.
        $resolved = $this->callPrivateResolve($service, $user);

        $this->assertNotNull($resolved);
        $this->assertSame($this->defaultLevel->id, $resolved->id);
    }

    public function test_no_level_means_campaign_creation_fails_explicitly(): void
    {
        $service = app(FundraiserLevelService::class);
        $user = User::factory()->create(['role' => 'donor']);

        // Delete all levels so there is no default to fall back to.
        FundraiserLevel::query()->delete();

        $result = $service->canCreateCampaign($user, 10000);

        $this->assertFalse($result['allowed']);
        $this->assertNotNull($result['reason']);
        $this->assertNull($result['level']);
    }

    private function callPrivateResolve(FundraiserLevelService $service, User $user): ?FundraiserLevel
    {
        $method = new \ReflectionMethod($service, 'resolveLevel');
        $method->setAccessible(true);

        return $method->invoke($service, $user);
    }
}
