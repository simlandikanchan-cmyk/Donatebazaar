<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\CampaignProduct;
use App\Models\Donation;
use App\Models\ProductReservation;
use Illuminate\Support\Facades\DB;

class ProductReservationService
{
    public const TTL_MINUTES = 15;

    /**
     * Stock available to a new donor = remaining_quantity minus
     * quantities currently held by non-expired reservations.
     */
    public function availableQuantity(CampaignProduct $product): int
    {
        $reserved = ProductReservation::where('product_id', $product->id)
            ->where('expires_at', '>', now())
            ->sum('quantity');

        return max(0, $product->remaining_quantity - (int) $reserved);
    }

    /**
     * Reserve the given cart items, preventing oversell via pessimistic
     * row locks inside a single DB transaction.
     *
     * @param  array<int, array{product_id: int, quantity: int}>  $items
     * @return array<int>  The created reservation IDs
     *
     * @throws InsufficientStockException
     */
    public function reserve(array $items, string $sessionId): array
    {
        $productIds = collect($items)
            ->sortBy('product_id')
            ->pluck('product_id')
            ->unique()
            ->all();

        return DB::transaction(function () use ($items, $sessionId, $productIds) {
            // Lock the product rows in a deterministic order to avoid deadlocks.
            $products = CampaignProduct::whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $created = [];

            foreach ($items as $item) {
                $product = $products->get((int) $item['product_id']);
                $qty     = (int) $item['quantity'];

                if (! $product || $qty <= 0) {
                    throw new InsufficientStockException('Product not available.');
                }

                // Re-read the reserved sum WITH a lock so concurrent
                // transactions serialize on the same product row.
                $reserved = (int) ProductReservation::where('product_id', $product->id)
                    ->where('expires_at', '>', now())
                    ->lockForUpdate()
                    ->sum('quantity');

                $available = $product->remaining_quantity - $reserved;

                if ($available < $qty) {
                    throw new InsufficientStockException(
                        "Only {$available} of “{$product->name}” left."
                    );
                }

                $created[] = ProductReservation::create([
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'session_id' => $sessionId,
                    'expires_at' => now()->addMinutes(self::TTL_MINUTES),
                ])->id;
            }

            return $created;
        });
    }

    /**
     * Consume (delete) reservations linked to a completed donation so they
     * stop counting toward "reserved" stock.
     */
    public function consume(Donation $donation): void
    {
        ProductReservation::where('donation_id', $donation->id)->delete();
    }
}
