<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WalletRepository
{
    public function findByUser(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->first();
    }

    public function findWithLock(int $userId): ?Wallet
    {
        return Wallet::where('user_id', $userId)->lockForUpdate()->first();
    }

    public function findOrCreate(int $userId): Wallet
    {
        return Wallet::firstOrCreate(['user_id' => $userId]);
    }

    public function getRecentTransactions(int $walletId, int $limit = 5): Collection
    {
        return WalletTransaction::where('wallet_id', $walletId)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function createTransaction(array $data): WalletTransaction
    {
        return WalletTransaction::create($data);
    }

    public function getPendingSettlementsByCampaignIds(array $campaignIds): int
    {
        return DB::table('campaign_settlements')
            ->whereIn('campaign_id', $campaignIds)
            ->whereIn('status', ['pending', 'processing', 'pending_approval'])
            ->count();
    }

    public function getDonationIdsFromSettlements(array $campaignIds, array $statuses): Collection
    {
        return DB::table('settlement_items')
            ->join('campaign_settlements', 'settlement_items.campaign_settlement_id', '=', 'campaign_settlements.id')
            ->whereIn('campaign_settlements.status', $statuses)
            ->whereIn('campaign_settlements.campaign_id', $campaignIds)
            ->pluck('settlement_items.donation_id');
    }

    public function findExistingTransaction(string $type, string $referenceId): ?WalletTransaction
    {
        return WalletTransaction::where('type', $type)
            ->where('reference_id', $referenceId)
            ->first();
    }

    public function credit(int $walletId, float $amount, string $description, ?string $referenceType = null, ?string $referenceId = null): WalletTransaction
    {
        return DB::transaction(function () use ($walletId, $amount, $description, $referenceType, $referenceId) {
            $wallet = Wallet::where('id', $walletId)->lockForUpdate()->firstOrFail();
            $wallet->increment('balance', $amount);

            return WalletTransaction::create([
                'wallet_id' => $walletId,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $wallet->balance - $amount,
                'balance_after' => $wallet->balance,
                'description' => $description,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);
        });
    }

    public function debit(int $walletId, float $amount, string $description, ?string $referenceType = null, ?string $referenceId = null): WalletTransaction
    {
        return DB::transaction(function () use ($walletId, $amount, $description, $referenceType, $referenceId) {
            $wallet = Wallet::where('id', $walletId)->lockForUpdate()->firstOrFail();

            if ($wallet->balance < $amount) {
                throw new \RuntimeException("Insufficient wallet balance");
            }

            $wallet->decrement('balance', $amount);

            return WalletTransaction::create([
                'wallet_id' => $walletId,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $wallet->balance + $amount,
                'balance_after' => $wallet->balance,
                'description' => $description,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);
        });
    }

    public function getMaturedReserves(): Collection
    {
        return WalletTransaction::where('type', 'reserve')
            ->whereNotNull('release_at')
            ->where('released', false)
            ->where('release_at', '<=', now())
            ->get();
    }

    public function getReservesForDonations(array $donationIds): Collection
    {
        return WalletTransaction::where('type', 'reserve')
            ->whereIn('reference_id', $donationIds)
            ->where('released', false)
            ->get();
    }

    public function markAsReleased(int $transactionId): void
    {
        WalletTransaction::where('id', $transactionId)->update(['released' => true]);
    }

    public function adminAdjust(int $userId, string $direction, float $amount, string $notes): WalletTransaction
    {
        return DB::transaction(function () use ($userId, $direction, $amount, $notes) {
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->firstOrFail();

            if ($direction === 'debit' && $wallet->balance < $amount) {
                throw new \RuntimeException("Insufficient balance");
            }

            $direction === 'credit'
                ? $wallet->increment('balance', $amount)
                : $wallet->decrement('balance', $amount);

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => $direction,
                'amount' => $amount,
                'balance_before' => $direction === 'credit' ? $wallet->balance - $amount : $wallet->balance + $amount,
                'balance_after' => $wallet->balance,
                'description' => "Admin {$direction}: {$notes}",
            ]);
        });
    }
}
