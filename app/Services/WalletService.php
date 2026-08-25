<?php

namespace App\Services;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Models\Donation;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\FundsAvailableNotification;
use App\Support\Money;
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
        ?string $notes = null,
        ?string $actorType = null,
        ?int $actorId = null
    ): WalletTransaction {
        $amount = Money::of($amount);

        if (! $amount->isPositive()) {
            throw new \InvalidArgumentException('Credit amount must be positive.');
        }

        return DB::transaction(function () use ($wallet, $amount, $source, $referenceId, $referenceType, $notes, $actorType, $actorId) {
            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

            $existing = $this->findExisting($locked->id, $referenceId, $referenceType, $source);
            if ($existing) {
                return $existing;
            }

            if ($source === WalletTransaction::SOURCE_DONATION) {
                $locked->reserved_balance = Money::of($locked->reserved_balance)->add($amount)->toString();
            } else {
                $locked->balance = Money::of($locked->balance)->add($amount)->toString();
            }
            $locked->save();

            return $this->record($locked, 'credit', $amount, $source, $referenceId, $referenceType, $notes, $actorType, $actorId);
        });
    }

    public function debit(
        Wallet $wallet,
        float $amount,
        string $source,
        $referenceId,
        $referenceType,
        ?string $notes = null,
        ?string $actorType = null,
        ?int $actorId = null
    ): WalletTransaction {
        $amount = Money::of($amount);

        if (! $amount->isPositive()) {
            throw new \InvalidArgumentException('Debit amount must be positive.');
        }

        return DB::transaction(function () use ($wallet, $amount, $source, $referenceId, $referenceType, $notes, $actorType, $actorId) {
            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

            $existing = $this->findExisting($locked->id, $referenceId, $referenceType, $source);
            if ($existing) {
                return $existing;
            }

            $fromReserved = false;
            if ($source === WalletTransaction::SOURCE_REFUND) {
                $fromReserved = Money::of($locked->reserved_balance)->isGreaterThanOrEqualTo($amount);
            }

            if ($fromReserved) {
                if (Money::of($locked->reserved_balance)->isLessThan($amount)) {
                    throw new InsufficientWalletBalanceException('Reserved balance insufficient for refund.');
                }
                $locked->reserved_balance = Money::of($locked->reserved_balance)->sub($amount)->toString();
            } else {
                if (Money::of($locked->balance)->isLessThan($amount)) {
                    throw new InsufficientWalletBalanceException(
                        "Wallet balance insufficient: have {$locked->balance}, need {$amount}."
                    );
                }
                $locked->balance = Money::of($locked->balance)->sub($amount)->toString();
            }
            $locked->save();

            return $this->record($locked, 'debit', $amount, $source, $referenceId, $referenceType, $notes, $actorType, $actorId);
        });
    }

    public function releaseMaturedReserves(): int
    {
        $cutoff = now()->subDays(self::DEFAULT_HOLD_DAYS);

        $matured = Donation::with('campaign')
            ->where('payment_status', 'completed')
            ->where('is_refunded', false)
            ->whereNotNull('paid_at')
            ->where('paid_at', '<=', $cutoff)
            ->whereNull('released_at')
            ->get()
            ->groupBy(fn ($d) => $this->ownerForDonation($d)?->id);

        $totalReleased = 0;

        foreach ($matured as $ownerId => $donations) {
            if (! $ownerId) {
                continue;
            }

            $owner = User::find($ownerId);
            if (! $owner) {
                continue;
            }

            $wallet = $this->getOrCreateWallet($owner);
            $lock = Cache::lock('wallet_release_'.$wallet->id, 30);

            try {
                if (! $lock->get()) {
                    continue;
                }

                $releasedAmount = 0;
                $releasedCount = 0;

                DB::transaction(function () use ($wallet, $donations, &$releasedAmount, &$releasedCount) {
                    $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

                    foreach ($donations as $donation) {
                        $tx = WalletTransaction::where('wallet_id', $locked->id)
                            ->where('source', WalletTransaction::SOURCE_DONATION)
                            ->where('reference_type', Donation::class)
                            ->where('reference_id', $donation->id)
                            ->where('type', 'credit')
                            ->first();

                        if (! $tx) {
                            continue;
                        }

                        $amount = Money::of($tx->amount);
                        if (Money::of($locked->reserved_balance)->isLessThan($amount)) {
                            continue;
                        }

                        $locked->reserved_balance = Money::of($locked->reserved_balance)->sub($amount)->toString();
                        $locked->balance = Money::of($locked->balance)->add($amount)->toString();

                        $this->record($locked, 'credit', $amount, WalletTransaction::SOURCE_ADJUSTMENT, $donation->id, Donation::class, 'Reserve matured');

                        $donation->released_at = now();
                        $donation->save();

                        $releasedAmount += $amount->toFloat();
                        $releasedCount++;
                    }

                    $locked->save();
                });

                if ($releasedCount > 0) {
                    $owner->notify(new FundsAvailableNotification($releasedAmount, $releasedCount));
                }

                $totalReleased += $releasedCount;
            } finally {
                $lock->release();
            }
        }

        return $totalReleased;
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
                        $amount = Money::of($tx->amount);
                        DB::transaction(function () use ($wallet, $donation, $amount) {
                            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

                            if (Money::of($locked->reserved_balance)->isGreaterThanOrEqualTo($amount)) {
                                $locked->reserved_balance = Money::of($locked->reserved_balance)->sub($amount)->toString();
                                $locked->balance = Money::of($locked->balance)->add($amount)->toString();
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
        Money|float|string $amount,
        string $source,
        $referenceId,
        $referenceType,
        ?string $notes = null,
        ?string $actorType = null,
        ?int $actorId = null
    ): WalletTransaction {
        $amount = Money::of($amount);

        $transaction = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => $type,
            'amount' => $amount->toString(),
            'source' => $source,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'balance_after' => Money::of($wallet->balance)->add(Money::of($wallet->reserved_balance))->toString(),
            'status' => WalletTransaction::STATUS_COMPLETED,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
        ]);

        return $transaction;
    }

    public function ownerForDonation(Donation $donation): ?User
    {
        $campaign = $donation->campaign()->withoutGlobalScopes()->first();
        if ($campaign && $campaign->user_id) {
            return User::find($campaign->user_id);
        }

        if ($donation->user_id) {
            return User::find($donation->user_id);
        }

        return null;
    }
}
