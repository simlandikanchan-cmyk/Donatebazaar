<?php

namespace App\Console\Commands;

use App\Models\ProductReservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneExpiredReservations extends Command
{
    protected $signature = 'reservations:prune';

    protected $description = 'Delete expired product reservations to keep the table clean';

    public function handle(): int
    {
        $deleted = ProductReservation::where('expires_at', '<=', now())->delete();

        $this->info("Pruned {$deleted} expired reservation(s).");

        return self::SUCCESS;
    }
}
