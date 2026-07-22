<?php

namespace App\Services;

use App\Exceptions\DuplicateReservationException;
use App\Exceptions\InsufficientStockException;
use App\Models\CampaignProduct;
use App\Models\Donation;
use App\Models\ProductReservation;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ProductReservationService
{
    public const TTL_MINUTES = 15;

    /**
     * Stock available to a new donor = remaining_quantity minus
     * reserved_quantity (maintained atomically on the product row).
     */
    public function availableQuantity(CampaignProduct $product): int
    {
        return max(0, $product->remaining_quantity - $product->reserved_quantity);
    }

    /**
     * Reserve the given cart items, preventing oversell via pessimistic
     * row locks inside a single DB transaction.
     *
     * @param  array<int, array{product_id: int, quantity: int}>  $items
     * @param  string  $sessionId
     * @param  string|null  $idempotencyKey  Optional key to prevent duplicate reservations
     * @return array<int>  The created reservation IDs
     *
     * @throws InsufficientStockException
     */
    public function reserve(array $items, string $sessionId, ?string $idempotencyKey = null): array
    {
        $productIds = collect($items)
            ->sortBy('product_id')
            ->pluck('product_id')
            ->unique()
            ->all();

        try {
            return DB::transaction(function () use ($items, $sessionId, $productIds, $idempotencyKey) {
                // 1. Lock the product rows FIRST — this is the serialization point.
                //    Any concurrent request (with or without idempotencyKey) will
                //    block here until the current transaction completes.
                $products = CampaignProduct::whereIn('id', $productIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // 2. Clean up ALL expired reservations for the locked products
                //    so that freed-up stock is visible immediately.
                $expired = ProductReservation::whereIn('product_id', $productIds)
                    ->where('expires_at', '<', now())
                    ->whereNull('donation_id')
                    ->lockForUpdate()
                    ->get();

                if ($expired->isNotEmpty()) {
                    foreach ($expired->groupBy('product_id') as $pid => $rows) {
                        $qty = $rows->sum('quantity');
                        CampaignProduct::where('id', $pid)
                            ->where('reserved_quantity', '>=', $qty)
                            ->decrement('reserved_quantity', $qty);

                        if ($products->has($pid)) {
                            $products->get($pid)->reserved_quantity -= $qty;
                        }
                    }
                    ProductReservation::whereIn('id', $expired->pluck('id'))->delete();
                }

                // 3. Idempotency check — performed AFTER acquiring the product lock
                //    so a second request with the same key sees the committed row.
                if ($idempotencyKey !== null) {
                    $existing = ProductReservation::where('idempotency_key', $idempotencyKey)
                        ->whereIn('product_id', $productIds)
                        ->lockForUpdate()
                        ->get();

                    $nonExpired = $existing->filter(fn ($r) => $r->expires_at > now());

                    if ($nonExpired->isNotEmpty()) {
                        return $nonExpired->pluck('id')->toArray();
                    }

                    // All existing reservations for this key are expired — remove
                    // them so the unique constraint doesn't block a fresh one.
                    if ($existing->isNotEmpty()) {
                        foreach ($existing->groupBy('product_id') as $pid => $rows) {
                            $qty = $rows->sum('quantity');
                            CampaignProduct::where('id', $pid)
                                ->where('reserved_quantity', '>=', $qty)
                                ->decrement('reserved_quantity', $qty);

                            if ($products->has($pid)) {
                                $products->get($pid)->reserved_quantity -= $qty;
                            }
                        }
                        ProductReservation::whereIn('id', $existing->pluck('id'))->delete();
                    }
                }

                $created = [];

                foreach ($items as $item) {
                    $product = $products->get((int) $item['product_id']);
                    $qty     = (int) $item['quantity'];

                    if (! $product || $qty <= 0) {
                        throw new InsufficientStockException('Product not available.');
                    }

                    $available = $product->remaining_quantity - $product->reserved_quantity;

                    if ($available < $qty) {
                        throw new InsufficientStockException(
                            "Only {$available} of \"{$product->name}\" left."
                        );
                    }

                    // 3. Create the reservation — wrapped in try/catch as a
                    //    defense-in-depth against the edge case where two
                    //    requests with the same idempotency key target
                    //    non-overlapping product sets (and thus don't contend
                    //    on the same lock).
                    //    NOTE: we NEVER return inside the catch block; instead
                    //    we throw DuplicateReservationException so the whole
                    //    transaction rolls back, preventing orphaned
                    //    reservations from earlier loop iterations.
                    try {
                        $reservation = ProductReservation::create([
                            'product_id'      => $product->id,
                            'quantity'        => $qty,
                            'session_id'      => $sessionId,
                            'idempotency_key' => $idempotencyKey,
                            'expires_at'      => now()->addMinutes(self::TTL_MINUTES),
                        ]);
                    } catch (QueryException $e) {
                        if ($this->isDuplicateKeyException($e, 'product_reservations_idempotency_key_product_id_unique')) {
                            throw new DuplicateReservationException(
                                "Duplicate idempotency_key '{$idempotencyKey}'."
                            );
                        }
                        throw $e;
                    }

                    $created[] = $reservation->id;

                    $product->increment('reserved_quantity', $qty);
                }

                return $created;
            });
        } catch (DuplicateReservationException $e) {
            // The transaction was rolled back — no orphaned reservations.
            // Re-fetch the existing reservation(s) for this key in a fresh
            // query and return their IDs as though idempotency had won.
            $existing = ProductReservation::where('idempotency_key', $idempotencyKey)
                ->where('expires_at', '>', now())
                ->get();

            if ($existing->isNotEmpty()) {
                return $existing->pluck('id')->toArray();
            }

            // Shouldn't happen — the duplicate key implies a row exists.
            // Re-throw as a safety net.
            throw $e;
        }
    }

    private function isDuplicateKeyException(QueryException $e, string $indexName): bool
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'mysql' => $e->getCode() === 23000
                && isset($e->errorInfo[1])
                && $e->errorInfo[1] === 1062
                && str_contains($e->getMessage(), $indexName),
            'pgsql' => $e->getCode() === 23505
                && str_contains($e->getMessage(), $indexName),
            'sqlite' => $e->getCode() === 23000
                && str_contains($e->getMessage(), 'UNIQUE constraint failed')
                && str_contains($e->getMessage(), $indexName),
            default => false,
        };
    }

    /**
     * Consume (delete) reservations linked to a completed donation so they
     * stop counting toward "reserved" stock.
     */
    public function consume(Donation $donation): void
    {
        $reservations = ProductReservation::where('donation_id', $donation->id)->get();

        foreach ($reservations as $reservation) {
            CampaignProduct::where('id', $reservation->product_id)
                ->decrement('reserved_quantity', $reservation->quantity);
        }

        ProductReservation::where('donation_id', $donation->id)->delete();
    }
}
