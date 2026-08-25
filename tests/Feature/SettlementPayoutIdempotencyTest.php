<?php

namespace Tests\Feature;

use App\Jobs\ProcessSettlementJob;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SettlementPayoutIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private User $admin;

    private Organization $org;

    private WalletService $walletService;

    private SettlementService $settlementService;

    private Donation $donation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->org = Organization::factory()->create(['user_id' => $this->owner->id]);

        PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'is_verified' => true,
        ]);

        $this->walletService = app(WalletService::class);
        $this->settlementService = app(SettlementService::class);
        $wallet = $this->walletService->getOrCreateWallet($this->owner);
        $this->walletService->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, Organization::class);

        $campaign = Campaign::create([
            'title' => 'Payout Key Campaign',
            'slug' => 'payout-key-'.uniqid(),
            'user_id' => $this->owner->id,
            'description' => 'Test campaign',
            'goal_amount' => 10000.00,
        ]);

        $this->donation = Donation::create([
            'user_id' => $this->owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 500.00,
        ]);
        $this->donation->payment_status = 'completed';
        $this->donation->is_refunded = false;
        $this->donation->paid_at = now()->subDays(10);
        $this->donation->save();
    }

    #[Test]
    public function payout_idempotency_key_is_persisted_and_reused_across_retries(): void
    {
        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        // First dispatch — gateway pays, DB phase-two records the outcome.
        $job = new ProcessSettlementJob($settlement);
        $job->handle($this->settlementService);

        $settlement->refresh();
        $key = $settlement->payout_idempotency_key;
        $this->assertNotNull($key);
        $this->assertEquals(
            $key,
            DB::table('payout_attempts')->where('settlement_id', $settlement->id)->value('idempotency_key')
        );
        $attemptsAfterFirst = DB::table('payout_attempts')->where('settlement_id', $settlement->id)->count();

        // Simulate the dangerous gap: gateway paid but the DB phase-two write
        // failed, leaving the settlement "processing", the payout attempt still
        // "initiated" (not yet marked completed), and a fresh job dispatched.
        DB::table('campaign_settlements')->where('id', $settlement->id)->update([
            'status' => 'processing',
            'gateway_reference' => 'PAYOUT_'.$settlement->id.'_'.time(),
        ]);
        DB::table('payout_attempts')->where('settlement_id', $settlement->id)
            ->update(['status' => 'initiated', 'finished_at' => null]);

        $retry = new ProcessSettlementJob($settlement->fresh());
        $retry->handle($this->settlementService);

        $settlement->refresh();
        // The same logical payout keeps the same idempotency key...
        $this->assertEquals($key, $settlement->payout_idempotency_key);
        // ...and does NOT create a second payout attempt (no duplicate payout).
        $this->assertEquals(
            $attemptsAfterFirst,
            DB::table('payout_attempts')->where('settlement_id', $settlement->id)->count()
        );
        $this->assertEquals('paid', $settlement->status);
    }
}
