<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Task 5 (Section 8) — settlement items are clickable in the admin
 * settlement show view. This covers the server-rendered Blade output for the
 * conditional campaign link: a link when the campaign exists, a plain dash
 * (no link) when it does not.
 */
class SettlementShowItemLinksTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $orgUser;

    private Organization $org;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->orgUser = User::factory()->create();
        $this->org = Organization::factory()->create(['user_id' => $this->orgUser->id]);
    }

    /**
     * Build a pending_approval settlement with a single donation and return it.
     */
    private function makeSettlement(?Campaign $campaign): CampaignSettlement
    {
        $campaign ??= Campaign::create([
            'title' => 'Linked Campaign',
            'slug' => 'linked-'.uniqid(),
            'user_id' => $this->orgUser->id,
            'description' => 'Campaign for settlement link test.',
            'goal_amount' => 10000.00,
        ]);

        $wallet = app(WalletService::class)->getOrCreateWallet($this->orgUser);
        app(WalletService::class)->credit(
            $wallet, 300.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, Organization::class
        );

        $donation = Donation::create([
            'user_id' => $this->orgUser->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 300.00,
            'platform_fee' => 15.00,
            'net_amount' => 300.00,
        ]);
        DB::table('donations')->where('id', $donation->id)->update([
            'payment_status' => 'completed',
            'is_refunded' => false,
        ]);

        return app(SettlementService::class)->requestSettlement($this->org, [$donation->id]);
    }

    #[Test]
    public function campaign_name_renders_as_link_when_campaign_exists(): void
    {
        $campaign = Campaign::create([
            'title' => 'Clean Water Drive',
            'slug' => 'clean-water-'.uniqid(),
            'user_id' => $this->orgUser->id,
            'description' => 'Campaign for settlement link test.',
            'goal_amount' => 10000.00,
        ]);

        $settlement = $this->makeSettlement($campaign);

        $this->actingAs($this->admin)
            ->get(route('admin.settlements.show', $settlement))
            ->assertOk()
            ->assertSee('Clean Water Drive')
            ->assertSee(route('admin.campaign.show', $campaign));
    }

    #[Test]
    public function campaign_cell_renders_dash_without_link_when_campaign_missing(): void
    {
        $campaign = Campaign::create([
            'title' => 'Soon Deleted',
            'slug' => 'soon-deleted-'.uniqid(),
            'user_id' => $this->orgUser->id,
            'description' => 'Campaign for settlement link test.',
            'goal_amount' => 10000.00,
        ]);

        $settlement = $this->makeSettlement($campaign);

        // Remove the campaign so $item->donation->campaign resolves to null,
        // without cascade-deleting the donation/settlement rows. Wrapped in
        // try/finally so FK checks are always restored even if delete() throws,
        // preventing a disabled-constraint leak into later tests.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            DB::table('campaigns')->where('id', $campaign->id)->delete();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $response = $this->actingAs($this->admin)
            ->get(route('admin.settlements.show', $settlement))
            ->assertOk();

        // No link to the (now missing) campaign should be rendered.
        $response->assertDontSee(route('admin.campaign.show', $campaign->id));
        // The item row still renders (donation id link present), just the
        // campaign cell falls back to a plain dash.
        $response->assertSee(route('admin.donations.show', $settlement->settlementItems->first()->donation_id));
    }
}
