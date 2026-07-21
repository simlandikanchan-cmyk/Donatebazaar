<?php

namespace App\Console\Commands;

use App\Services\WalletService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReleaseWalletReserves extends Command
{
    protected $signature = 'wallet:release-reserves';

    protected $description = 'Release matured reserved wallet funds into available balance.';

    public function handle(): int
    {
        $count = app(WalletService::class)->releaseMaturedReserves();

        $this->info("Released matured reserves for {$count} donation(s).");

        Log::info('Wallet reserves released', ['count' => $count]);

        return self::SUCCESS;
    }
}
