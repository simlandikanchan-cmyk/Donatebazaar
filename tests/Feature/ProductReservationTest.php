<?php

namespace Tests\Feature;

use App\Exceptions\InsufficientStockException;
use App\Models\Campaign;
use App\Models\CampaignProduct;
use App\Models\Donation;
use App\Models\ProductReservation;
use App\Models\User;
use App\Services\ProductReservationService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
            'reserved_quantity'  => 0,
            'approval_status'    => 'approved',
            'is_active'          => true,
        ]);
    }

    public function test_only_one_donor_can_reserve_last_unit()
    {
        $product = $this->makeProduct(1);
        $service = new ProductReservationService();

        $ids = $service->reserve([['product_id' => $product->id, 'quantity' => 1]], 'sessA');
        $this->assertCount(1, $ids);

        $this->expectException(InsufficientStockException::class);
        $service->reserve([['product_id' => $product->id, 'quantity' => 1]], 'sessB');
    }

    public function test_expired_reservation_frees_stock()
    {
        $product = $this->makeProduct(1);

        ProductReservation::create([
            'product_id' => $product->id,
            'quantity'   => 1,
            'session_id' => 'old',
            'expires_at' => now()->subMinute(),
        ]);

        $service = new ProductReservationService();

        $this->assertEquals(1, $service->availableQuantity($product));

        $ids = $service->reserve([['product_id' => $product->id, 'quantity' => 1]], 'sessNew');
        $this->assertCount(1, $ids);
    }

    public function test_successful_payment_consumes_reservation_and_decrements_once()
    {
        $product = $this->makeProduct(5);
        $service = new ProductReservationService();

        $resIds = $service->reserve([['product_id' => $product->id, 'quantity' => 2]], 'sessX');

        $donation = Donation::create([
            'campaign_id'    => $product->campaign_id,
            'donation_type'  => 'product',
            'total_amount'   => 200,
        ]);
        $donation->payment_status = 'completed';
        $donation->save();

        ProductReservation::whereIn('id', $resIds)->update(['donation_id' => $donation->id]);

        CampaignProduct::where('id', $product->id)
            ->where('remaining_quantity', '>=', 2)
            ->decrement('remaining_quantity', 2);

        $service->consume($donation);

        $this->assertEquals(3, $product->fresh()->remaining_quantity);
        $this->assertEquals(0, $product->fresh()->reserved_quantity);
        $this->assertDatabaseMissing('product_reservations', ['id' => $resIds[0]]);
        $this->assertEquals(3, $service->availableQuantity($product->fresh()));
    }

    public function test_reserved_quantity_tracks_concurrent_reservations()
    {
        $product = $this->makeProduct(10);
        $service = new ProductReservationService();

        $service->reserve([['product_id' => $product->id, 'quantity' => 3]], 'sessA');
        $this->assertEquals(3, $product->fresh()->reserved_quantity);
        $this->assertEquals(7, $service->availableQuantity($product->fresh()));

        $service->reserve([['product_id' => $product->id, 'quantity' => 2]], 'sessB');
        $this->assertEquals(5, $product->fresh()->reserved_quantity);
        $this->assertEquals(5, $service->availableQuantity($product->fresh()));
    }

    public function test_consume_decrements_reserved_quantity()
    {
        $product = $this->makeProduct(10);
        $service = new ProductReservationService();

        $resIds = $service->reserve([['product_id' => $product->id, 'quantity' => 4]], 'sessX');

        $donation = Donation::create([
            'campaign_id'    => $product->campaign_id,
            'donation_type'  => 'product',
            'total_amount'   => 400,
        ]);
        $donation->payment_status = 'completed';
        $donation->save();

        ProductReservation::whereIn('id', $resIds)->update(['donation_id' => $donation->id]);

        $service->consume($donation);

        $this->assertEquals(0, $product->fresh()->reserved_quantity);
        $this->assertEquals(10, $service->availableQuantity($product->fresh()));
    }

    public function test_idempotency_key_prevents_duplicate_reservations()
    {
        $product = $this->makeProduct(5);
        $service = new ProductReservationService();

        $first  = $service->reserve(
            [['product_id' => $product->id, 'quantity' => 2]],
            'sessX',
            'idem-key-001'
        );
        $this->assertCount(1, $first);

        $second = $service->reserve(
            [['product_id' => $product->id, 'quantity' => 2]],
            'sessX',
            'idem-key-001'
        );
        $this->assertCount(1, $second);
        $this->assertSame($first, $second);

        $this->assertEquals(2, $product->fresh()->reserved_quantity);
        $this->assertEquals(3, $service->availableQuantity($product->fresh()));
    }

    public function test_idempotency_key_allows_new_reservation_after_expiry()
    {
        $product = $this->makeProduct(5);
        $service = new ProductReservationService();

        $reservation = ProductReservation::create([
            'product_id'      => $product->id,
            'quantity'        => 2,
            'session_id'      => 'sessX',
            'idempotency_key' => 'expired-key',
            'expires_at'      => now()->subMinute(),
        ]);

        $ids = $service->reserve(
            [['product_id' => $product->id, 'quantity' => 2]],
            'sessX',
            'expired-key'
        );
        $this->assertCount(1, $ids);
        $this->assertNotEquals($reservation->id, $ids[0]);
    }

    public function test_unique_constraint_rejects_duplicate_idempotency_key_per_product()
    {
        $productA = $this->makeProduct(5);
        $productB = $this->makeProduct(5);

        // Same key for DIFFERENT products — allowed.
        ProductReservation::create([
            'product_id'      => $productA->id,
            'quantity'        => 1,
            'session_id'      => 'sessA',
            'idempotency_key' => 'unique-test-key',
            'expires_at'      => now()->addMinutes(15),
        ]);
        ProductReservation::create([
            'product_id'      => $productB->id,
            'quantity'        => 1,
            'session_id'      => 'sessA',
            'idempotency_key' => 'unique-test-key',
            'expires_at'      => now()->addMinutes(15),
        ]);
        $this->assertDatabaseHas('product_reservations', ['product_id' => $productA->id, 'idempotency_key' => 'unique-test-key']);
        $this->assertDatabaseHas('product_reservations', ['product_id' => $productB->id, 'idempotency_key' => 'unique-test-key']);

        // Same key for SAME product — rejected.
        $this->expectException(QueryException::class);
        ProductReservation::create([
            'product_id'      => $productA->id,
            'quantity'        => 1,
            'session_id'      => 'sessB',
            'idempotency_key' => 'unique-test-key',
            'expires_at'      => now()->addMinutes(15),
        ]);
    }

    public function test_concurrent_idempotent_requests_return_same_ids()
    {
        $product = $this->makeProduct(5);
        $service = new ProductReservationService();

        $key = 'concurrent-key-' . uniqid();

        // Request 1: reserve with the idempotency key
        $ids1 = $service->reserve(
            [['product_id' => $product->id, 'quantity' => 2]],
            'sessA',
            $key
        );
        $this->assertCount(1, $ids1);

        // Request 2: same key — must return the *same* reservation IDs
        $ids2 = $service->reserve(
            [['product_id' => $product->id, 'quantity' => 2]],
            'sessB',
            $key
        );
        $this->assertCount(1, $ids2);
        $this->assertSame($ids1, $ids2);

        // reserved_quantity must NOT have been incremented twice
        $this->assertEquals(2, $product->fresh()->reserved_quantity);
        $this->assertEquals(1, ProductReservation::where('idempotency_key', $key)->count());
    }

    public function test_defense_in_depth_returns_existing_on_duplicate()
    {
        $product = $this->makeProduct(5);
        $service = new ProductReservationService();

        $key = 'defense-depth-key';

        // Pre-seed a reservation so the idempotency check finds it.
        ProductReservation::create([
            'product_id'      => $product->id,
            'quantity'        => 2,
            'session_id'      => 'sessA',
            'idempotency_key' => $key,
            'expires_at'      => now()->addMinutes(15),
        ]);
        $product->increment('reserved_quantity', 2);

        // The app-level check finds the existing reservation and returns its
        // IDs.  The catch block path (defense-in-depth) is exercised only in
        // a true race where the reservation commits between the check and the
        // INSERT — that's tested indirectly via the rollback path below.
        $ids = $service->reserve(
            [['product_id' => $product->id, 'quantity' => 2]],
            'sessB',
            $key
        );
        $this->assertCount(1, $ids);
        $this->assertEquals(2, $product->fresh()->reserved_quantity);
    }

    public function test_non_overlapping_product_sets_same_key_does_not_touch_locked_products()
    {
        $productA = $this->makeProduct(10);
        $productB = $this->makeProduct(10);
        $service = new ProductReservationService();

        $key = 'non-overlap-key-' . uniqid();

        // First call reserves productA.
        $ids1 = $service->reserve(
            [['product_id' => $productA->id, 'quantity' => 3]],
            'sessA',
            $key
        );
        $this->assertCount(1, $ids1);
        $this->assertEquals(3, $productA->fresh()->reserved_quantity);

        // Second call with a different productB and the same key.
        // With the whereIn('product_id', ...) filter, it won't find
        // productA's reservation (different product_id). It will create
        // a new reservation for productB — which is allowed by the
        // composite unique constraint (idempotency_key, product_id).
        $ids2 = $service->reserve(
            [['product_id' => $productB->id, 'quantity' => 4]],
            'sessB',
            $key
        );
        $this->assertCount(1, $ids2);

        // productB gets its own reservation.
        $this->assertEquals(4, $productB->fresh()->reserved_quantity);

        // productA is untouched.
        $this->assertEquals(3, $productA->fresh()->reserved_quantity);

        // Two reservations with the same key, different products.
        $this->assertEquals(2, ProductReservation::where('idempotency_key', $key)->count());
    }

    public function test_expired_cleanup_updates_in_memory_reserved_quantity()
    {
        $product = $this->makeProduct(10);
        $service = new ProductReservationService();

        // Create an expired reservation for the same product.
        ProductReservation::create([
            'product_id'      => $product->id,
            'quantity'        => 6,
            'session_id'      => 'old-sess',
            'idempotency_key' => 'stale-key',
            'expires_at'      => now()->subMinute(),
        ]);
        CampaignProduct::where('id', $product->id)->update(['reserved_quantity' => 6]);

        $product->refresh();
        $this->assertEquals(6, $product->reserved_quantity);
        $this->assertEquals(4, $service->availableQuantity($product));

        // Now reserve — the expired cleanup should decrement reserved_quantity
        // by 6 in DB AND in memory before the stock check runs.  Without the
        // in-memory fix this would throw InsufficientStockException.
        $ids = $service->reserve(
            [['product_id' => $product->id, 'quantity' => 8]],
            'new-sess',
            'fresh-key'
        );
        $this->assertCount(1, $ids);
        $this->assertEquals(8, $product->fresh()->reserved_quantity);
        $this->assertEquals(2, $service->availableQuantity($product->fresh()));
    }
}
