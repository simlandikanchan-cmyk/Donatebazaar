<?php

namespace App\Console\Commands;

use App\Models\CampaignProduct;
use App\Models\ProductReservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneExpiredReservations extends Command
{
    protected $signature = 'product-reservations:prune-expired';

    protected $description = 'Delete expired product reservations and decrement reserved_quantity';

    public function handle(): int
    {
        $expired = ProductReservation::where('expires_at', '<', now())
            ->whereNull('donation_id')
            ->get()
            ->groupBy('product_id');

        $total = 0;

        foreach ($expired as $productId => $reservations) {
            DB::transaction(function () use ($productId, $reservations, &$total) {
                $qty = $reservations->sum('quantity');

                CampaignProduct::where('id', $productId)
                    ->where('reserved_quantity', '>=', $qty)
                    ->decrement('reserved_quantity', $qty);

                ProductReservation::whereIn('id', $reservations->pluck('id'))->delete();

                $total += $reservations->count();
            });
        }

        $this->info("Pruned {$total} expired reservation(s).");

        return self::SUCCESS;
    }
}
