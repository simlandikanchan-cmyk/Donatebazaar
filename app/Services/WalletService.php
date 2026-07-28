<?php

namespace App\Services;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public const DEFAULT_HOLD_DAYS = 7;

    public function getOrCreateWallet($owner): Wallet
    {
        return Wallet::firstOrCreate(
            ['owner_type' => get_class($owner), 'owner_id' => $owner->getKey()],
            [
                'user_id' => $owner instanceof User ? $owner->getKey() : null,
                'balance' => 0,
                'currency' => 'INR',
            ]
        );
    }

    public function credit(
        Wallet $wallet,
        float $amount,
        string $source,
        $referenceId,
        $referenceType,
        ?string $notes = null
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be positive.');
        }

        $existing = $this->findExisting($wallet->id, $referenceId, $referenceType, $source);
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($wallet, $amount, $source, $referenceId, $referenceType, $notes) {
            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

            if ($source === WalletTransaction::SOURCE_DONATION) {
                $locked->reserved_balance = (float) $locked->reserved_balance + $amount;
            } else {
                $locked->balance = (float) $locked->balance + $amount;
            }
            $locked->save();

            return $this->record($locked, 'credit', $amount, $source, $referenceId, $referenceType, $notes);
        });
    }

    public function debit(
        Wallet $wallet,
        float $amount,
        string $source,
        $referenceId,
        $referenceType,
        ?string $notes = null
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Debit amount must be positive.');
        }

        $existing = $this->findExisting($wallet->id, $referenceId, $referenceType, $source);
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($wallet, $amount, $source, $referenceId, $referenceType, $notes) {
            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

            $fromReserved = false;
            if ($source === WalletTransaction::SOURCE_REFUND) {
                $fromReserved = (float) $locked->reserved_balance >= $amount;
            }

            if ($fromReserved) {
                if ((float) $locked->reserved_balance < $amount) {
                    throw new InsufficientWalletBalanceException('Reserved balance insufficient for refund.');
                }
                $locked->reserved_balance = (float) $locked->reserved_balance - $amount;
            } else {
                if ((float) $locked->balance < $amount) {
                    throw new InsufficientWalletBalanceException(
                        "Wallet balance insufficient: have {$locked->balance}, need {$amount}."
                    );
                }
                $locked->balance = (float) $locked->balance - $amount;
            }
            $locked->save();

            return $this->record($locked, 'debit', $amount, $source, $referenceId, $referenceType, $notes);
        });
    }

    public function releaseMaturedReserves(): int
    {
        $count = 0;
        $cutoff = now()->subDays(self::DEFAULT_HOLD_DAYS);

        $matured = Donation::with('campaign')
            ->where('payment_status', 'completed')
            ->where('is_refunded', false)
            ->whereNotNull('paid_at')
            ->where('paid_at', '<=', $cutoff)
            ->whereNull('released_at')
            ->get();

        foreach ($matured as $donation) {
            $owner = $this->ownerForDonation($donation);
            if (! $owner) {
                continue;
            }

            $wallet = $this->getOrCreateWallet($owner);
            $lock = Cache::lock('wallet_release_'.$wallet->id, 10);
            if (! $lock->get()) {
                continue;
            }

            try {
                $released = DB::transaction(function () use ($wallet, $donation) {
                    $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

                    $tx = WalletTransaction::where('wallet_id', $locked->id)
                        ->where('source', WalletTransaction::SOURCE_DONATION)
                        ->where('reference_type', Donation::class)
                        ->where('reference_id', $donation->id)
                        ->where('type', 'credit')
                        ->first();

                    if (! $tx) {
                        return 0;
                    }

                    $amount = (float) $tx->amount;
                    if ((float) $locked->reserved_balance < $amount) {
                        return 0;
                    }

                    $locked->reserved_balance = (float) $locked->reserved_balance - $amount;
                    $locked->balance = (float) $locked->balance + $amount;
                    $locked->save();

                    $this->record($locked, 'credit', $amount, WalletTransaction::SOURCE_ADJUSTMENT, $donation->id, Donation::class, 'Reserve matured');

                    $donation->released_at = now();
                    $donation->save();

                    return 1;
                });

                $count += $released;
            } finally {
                $lock->release();
            }
        }

        return $count;
    }

    public function releaseReservesForDonations(Wallet $wallet, array $donations): int
    {
        $count = 0;
        $cutoff = now()->subDays(self::DEFAULT_HOLD_DAYS);

        foreach ($donations as $donation) {
            if ($donation->paid_at && $donation->paid_at <= $cutoff && ! $donation->released_at) {
                $lock = Cache::lock('donation_release_'.$donation->id, 10);
                if (! $lock->get()) {
                    continue;
                }

                try {
                    $tx = WalletTransaction::where('wallet_id', $wallet->id)
                        ->where('source', WalletTransaction::SOURCE_DONATION)
                        ->where('reference_type', Donation::class)
                        ->where('reference_id', $donation->id)
                        ->where('type', 'credit')
                        ->first();

                    if ($tx) {
                        $amount = (float) $tx->amount;
                        DB::transaction(function () use ($wallet, $donation, $amount) {
                            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

                            if ((float) $locked->reserved_balance >= $amount) {
                                $locked->reserved_balance = (float) $locked->reserved_balance - $amount;
                                $locked->balance = (float) $locked->balance + $amount;
                                $locked->save();

                                $this->record($locked, 'credit', $amount, WalletTransaction::SOURCE_ADJUSTMENT, $donation->id, Donation::class, 'Reserve matured');

                                $donation->released_at = now();
                                $donation->save();
                            }
                        });
                        $count++;
                    }
                } finally {
                    $lock->release();
                }
            }
        }

        return $count;
    }

    protected function findExisting(int $walletId, $referenceId, $referenceType, string $source): ?WalletTransaction
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->where('reference_id', $referenceId)
            ->where('reference_type', $referenceType)
            ->where('source', $source)
            ->first();
    }

    public function record(
        Wallet $wallet,
        string $type,
        float $amount,
        string $source,
        $referenceId,
        $referenceType,
        ?string $notes
    ): WalletTransaction {
        return WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => $type,
            'amount' => $amount,
            'source' => $source,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'balance_after' => (float) $wallet->balance + (float) $wallet->reserved_balance,
            'status' => WalletTransaction::STATUS_COMPLETED,
            'notes' => $notes,
        ]);
    }

    protected function ownerForDonation(Donation $donation): ?User
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
