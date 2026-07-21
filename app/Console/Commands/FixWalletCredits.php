<?php

namespace App\Console\Commands;

use App\Models\Donation;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixWalletCredits extends Command
{
    protected $signature = 'wallet:fix-credits';

    protected $description = 'Credit wallet for completed donations that were never credited';

    public function handle(WalletService $walletService)
    {
        $donations = Donation::where('payment_status', 'completed')
            ->where('is_refunded', false)
            ->where('net_amount', '>', 0)
            ->get();

        $credited = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($donations as $donation) {
            $owner = $this->resolveWalletOwner($donation);
            if (! $owner) {
                $this->warn("Donation {$donation->id}: no wallet owner found, skipping");
                $skipped++;

                continue;
            }

            $wallet = $walletService->getOrCreateWallet($owner);

            // Check if already credited
            $existing = WalletTransaction::where('wallet_id', $wallet->id)
                ->where('source', WalletTransaction::SOURCE_DONATION)
                ->where('reference_type', Donation::class)
                ->where('reference_id', $donation->id)
                ->where('type', 'credit')
                ->first();

            if ($existing) {
                $skipped++;

                continue;
            }

            try {
                DB::transaction(function () use ($walletService, $wallet, $donation) {
                    $walletService->credit(
                        $wallet,
                        (float) $donation->net_amount,
                        WalletTransaction::SOURCE_DONATION,
                        $donation->id,
                        Donation::class,
                        'Donation #'.$donation->id.' (retroactive fix)'
                    );
                });
                $this->line("Donation {$donation->id}: credited {$donation->net_amount} to wallet {$wallet->id}");
                $credited++;
            } catch (\Throwable $e) {
                $this->error("Donation {$donation->id}: failed - {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("Done: {$credited} credited, {$skipped} skipped, {$failed} failed");
    }

    private function resolveWalletOwner(Donation $donation): ?User
    {
        if ($donation->user_id) {
            return User::find($donation->user_id);
        }

        $campaign = $donation->campaign;
        if ($campaign && $campaign->user_id) {
            return User::find($campaign->user_id);
        }

        return null;
    }
}
