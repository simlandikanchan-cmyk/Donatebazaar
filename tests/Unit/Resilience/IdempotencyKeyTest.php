<?php

namespace Tests\Unit\Resilience;

use App\Models\CampaignSettlement;
use App\Models\PayoutAttempt;
use App\Models\User;
use App\Models\Organization;
use App\Models\PayoutAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IdempotencyKeyTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function idempotency_key_is_stable_across_retries(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 1000.00,
        ]);

        $key1 = PayoutAttempt::generateIdempotencyKey($settlement, 1);
        $settlement->update(['updated_at' => now()->addDay()]);
        $key2 = PayoutAttempt::generateIdempotencyKey($settlement, 1);

        $this->assertSame($key1, $key2, 'Idempotency key should not change when updated_at changes');
    }

    #[Test]
    public function idempotency_key_changes_between_attempts(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 1000.00,
        ]);

        $key1 = PayoutAttempt::generateIdempotencyKey($settlement, 1);
        $key2 = PayoutAttempt::generateIdempotencyKey($settlement, 2);

        $this->assertNotSame($key1, $key2, 'Different attempts should have different idempotency keys');
    }
}
