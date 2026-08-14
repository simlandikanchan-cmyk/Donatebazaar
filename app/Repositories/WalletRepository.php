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

    public function getDonationIdsFromSettlements(int $organizationId): Collection
    {
        return DB::table('settlement_items')
            ->join('campaign_settlements', 'settlement_items.campaign_settlement_id', '=', 'campaign_settlements.id')
            ->where('campaign_settlements.organization_id', $organizationId)
            ->whereNotIn('campaign_settlements.status', ['paid', 'rejected', 'failed', 'cancelled'])
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
        return DB::transaction(function () use ($walletId, $amount, $referenceType, $referenceId) {
            $wallet = Wallet::where('id', $walletId)->lockForUpdate()->firstOrFail();
            $wallet->increment('balance', $amount);

            $transaction = WalletTransaction::create([
                'wallet_id' => $walletId,
                'type' => 'credit',
                'amount' => $amount,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);
            $transaction->balance_after = (float) $wallet->balance + $amount;
            $transaction->save();

            return $transaction;
        });
    }

    public function debit(int $walletId, float $amount, string $description, ?string $referenceType = null, ?string $referenceId = null): WalletTransaction
    {
        return DB::transaction(function () use ($walletId, $amount, $referenceType, $referenceId) {
            $wallet = Wallet::where('id', $walletId)->lockForUpdate()->firstOrFail();

            if ($wallet->balance < $amount) {
                throw new \RuntimeException('Insufficient wallet balance');
            }

            $wallet->decrement('balance', $amount);

            $transaction = WalletTransaction::create([
                'wallet_id' => $walletId,
                'type' => 'debit',
                'amount' => $amount,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);
            $transaction->balance_after = (float) $wallet->balance - $amount;
            $transaction->save();

            return $transaction;
        });
    }

    public function adminAdjust(int $userId, string $direction, float $amount, string $notes): WalletTransaction
    {
        return DB::transaction(function () use ($userId, $direction, $amount) {
            $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->firstOrFail();

            if ($direction === 'debit' && $wallet->balance < $amount) {
                throw new \RuntimeException('Insufficient balance');
            }

            $direction === 'credit'
                ? $wallet->increment('balance', $amount)
                : $wallet->decrement('balance', $amount);

            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => $direction,
                'amount' => $amount,
                'reference_type' => null,
                'reference_id' => null,
            ]);
            $transaction->balance_after = $direction === 'credit'
                ? (float) $wallet->balance + $amount
                : (float) $wallet->balance - $amount;
            $transaction->save();

            return $transaction;
        });
    }
}
