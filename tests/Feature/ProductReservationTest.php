<?php

namespace Tests\Feature;

use App\Exceptions\InsufficientStockException;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Donation;
use App\Models\ProductReservation;
use App\Models\User;
use App\Services\ProductReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReservationTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(int $remaining = 1): CampaignProduct
    {
        $user = User::factory()->create();

        $campaign = Campaign::create([
            'user_id'     => $user->id,
            'title'       => 'Test Campaign',
            'slug'        => 'test-campaign-' . uniqid(),
            'description' => 'desc',
            'goal_amount' => 1000,
        ]);

        return CampaignProduct::create([
            'campaign_id'        => $campaign->id,
            'user_id'            => $user->id,
            'name'               => 'Product',
            'price'              => 100,
            'quantity'           => $remaining,
            'remaining_quantity' => $remaining,
            'approval_status'    => 'approved',
            'is_active'          => true,
        ]);
    }

    public function test_only_one_donor_can_reserve_last_unit()
    {
        $product = $this->makeProduct(1);
        $service = new ProductReservationService();

        // Donor A reserves the last unit -> success
        $ids = $service->reserve([['product_id' => $product->id, 'quantity' => 1]], 'sessA');
        $this->assertCount(1, $ids);

        // Donor B tries for the same last unit -> must be rejected
        $this->expectException(InsufficientStockException::class);
        $service->reserve([['product_id' => $product->id, 'quantity' => 1]], 'sessB');
    }

    public function test_expired_reservation_frees_stock()
    {
        $product = $this->makeProduct(1);

        // An already-expired reservation must NOT block new stock.
        ProductReservation::create([
            'product_id' => $product->id,
            'quantity'   => 1,
            'session_id' => 'old',
            'expires_at' => now()->subMinute(),
        ]);

        $service = new ProductReservationService();

        // Available ignores the expired row -> full stock still available.
        $this->assertEquals(1, $service->availableQuantity($product));

        // A fresh reservation should succeed.
        $ids = $service->reserve([['product_id' => $product->id, 'quantity' => 1]], 'sessNew');
        $this->assertCount(1, $ids);
    }

    public function test_successful_payment_consumes_reservation_and_decrements_once()
    {
        $product = $this->makeProduct(5);
        $service = new ProductReservationService();

        $resIds = $service->reserve([['product_id' => $product->id, 'quantity' => 2]], 'sessX');

        // Simulate payment success: link reservations to the donation, then consume.
        $donation = Donation::create([
            'campaign_id'    => $product->campaign_id,
            'donation_type'  => 'product',
            'total_amount'   => 200,
            'payment_status' => 'completed',
        ]);

        ProductReservation::whereIn('id', $resIds)->update(['donation_id' => $donation->id]);

        // Mirror the controller's successful-payment sequence:
        // decrementProductStock() runs first (reservations never touch
        // remaining_quantity), then the reservation is consumed.
        CampaignProduct::where('id', $product->id)
            ->where('remaining_quantity', '>=', 2)
            ->decrement('remaining_quantity', 2);

        $service->consume($donation);

        // remaining dropped exactly once, reservation consumed, no double-count.
        $this->assertEquals(3, $product->fresh()->remaining_quantity);
        $this->assertDatabaseMissing('product_reservations', ['id' => $resIds[0]]);
        $this->assertEquals(3, $service->availableQuantity($product->fresh()));
    }
}
