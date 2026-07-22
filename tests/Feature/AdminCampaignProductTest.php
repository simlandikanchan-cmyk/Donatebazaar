<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\ProductReservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminCampaignProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $ngo;
    protected Campaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->ngo = User::factory()->create(['role' => 'ngo']);

        $this->campaign = Campaign::create([
            'user_id'     => $this->ngo->id,
            'title'       => 'Test Campaign',
            'slug'        => 'test-campaign-' . uniqid(),
            'description' => 'desc',
            'goal_amount' => 1000,
        ]);
    }

    private function makeProduct(array $overrides = []): CampaignProduct
    {
        return CampaignProduct::create(array_merge([
            'campaign_id'        => $this->campaign->id,
            'user_id'            => $this->ngo->id,
            'name'               => 'Test Product',
            'price'              => 100,
            'quantity'           => 10,
            'remaining_quantity' => 10,
            'reserved_quantity'  => 0,
            'approval_status'    => 'pending',
            'is_active'          => false,
        ], $overrides));
    }

    // --- Destroy guard ---

    public function test_destroy_blocks_deletion_when_active_reservations_exist()
    {
        $product = $this->makeProduct(['approval_status' => 'approved', 'is_active' => true]);

        ProductReservation::create([
            'product_id' => $product->id,
            'quantity'   => 1,
            'session_id' => 'sess',
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.campaign-products.destroy', $product));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('campaign_products', ['id' => $product->id]);
    }

    public function test_destroy_blocks_deletion_when_donation_items_exist()
    {
        $product = $this->makeProduct(['approval_status' => 'approved', 'is_active' => true]);

        $donation = Donation::create([
            'campaign_id'    => $this->campaign->id,
            'donation_type'  => 'product',
            'total_amount'   => 100,
            'payment_status' => 'completed',
        ]);

        // donation_items.product_id FK references the legacy products table.
        DB::table('products')->insert([
            'id'          => $product->id,
            'campaign_id' => $this->campaign->id,
            'user_id'     => $this->ngo->id,
            'name'        => 'Legacy',
            'price'       => 100,
        ]);

        DonationItem::create([
            'donation_id' => $donation->id,
            'product_id'  => $product->id,
            'quantity'    => 1,
            'price'       => 100,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.campaign-products.destroy', $product));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('campaign_products', ['id' => $product->id]);
    }

    public function test_destroy_succeeds_when_no_reservations_or_donation_items()
    {
        $product = $this->makeProduct(['approval_status' => 'approved', 'is_active' => true]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.campaign-products.destroy', $product));

        $response->assertSessionHas('success');
        $this->assertSoftDeleted('campaign_products', ['id' => $product->id]);
    }

    // --- Bulk actions ---

    public function test_bulk_approve_updates_only_pending_products()
    {
        $pending1 = $this->makeProduct(['approval_status' => 'pending']);
        $pending2 = $this->makeProduct(['approval_status' => 'pending']);
        $approved = $this->makeProduct(['approval_status' => 'approved', 'is_active' => true]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign-products.bulk-approve'), [
                'ids' => [$pending1->id, $pending2->id, $approved->id],
            ]);

        $response->assertSessionHas('success', '2 product(s) approved.');

        $this->assertDatabaseHas('campaign_products', [
            'id' => $pending1->id, 'approval_status' => 'approved', 'is_active' => true,
        ]);
        $this->assertDatabaseHas('campaign_products', [
            'id' => $pending2->id, 'approval_status' => 'approved', 'is_active' => true,
        ]);
        $this->assertDatabaseHas('campaign_products', [
            'id' => $approved->id, 'approval_status' => 'approved', 'is_active' => true,
        ]);
    }

    public function test_bulk_reject_updates_only_pending_products()
    {
        $pending = $this->makeProduct(['approval_status' => 'pending']);
        $alreadyRejected = $this->makeProduct(['approval_status' => 'rejected']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign-products.bulk-reject'), [
                'ids' => [$pending->id, $alreadyRejected->id],
                'reason' => 'Not suitable for our platform.',
            ]);

        $response->assertSessionHas('success', '1 product(s) rejected.');

        $this->assertDatabaseHas('campaign_products', [
            'id' => $pending->id, 'approval_status' => 'rejected', 'is_active' => false,
        ]);
    }

    public function test_bulk_approve_skips_non_pending()
    {
        $approved = $this->makeProduct(['approval_status' => 'approved', 'is_active' => true]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.campaign-products.bulk-approve'), ['ids' => [$approved->id]]);

        $response->assertSessionHas('success', '0 product(s) approved.');
    }
}
