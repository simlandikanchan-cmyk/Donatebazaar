# Wallet Invariants

These are the rules the wallet system is built around. They exist so balances, holds, and refunds stay correct even under concurrent requests and webhook retries.

## Balance

- `Wallet::balance` = total credited minus total debited (not including holds).
- `Wallet::reserved_balance` = amount locked for pending settlements.
- `Wallet::available_balance` = `balance` - `reserved_balance`.

## Locking

- Settlement holds increment `reserved_balance`.
- `WalletService::holdForSettlement()` and `releaseHold()` are atomic within DB transactions.
- Adjustments use row-level locking (`lockForUpdate()`) on the wallet row.

## Idempotency

- Wallet transactions carry unique `reference_id` + `source_type` combinations where applicable.
- Duplicate donation verification/webhook calls never create a duplicate credit.
- The payout idempotency key (`payout_idempotency_key`) stops duplicate payout attempts.

## Refund Behavior

- **Service**: `RefundService`
- A refund creates a debit transaction on the recipient's wallet.
- The original donation is marked `refunded`.
- The wallet balance decreases immediately.
- If the wallet has insufficient funds, the refund fails gracefully (recorded but not processed).

## Manual Adjustments

- Admins can credit or debit via `WalletController::adjust()`.
- A debit requires sufficient `available_balance` and fails gracefully otherwise.
- Every adjustment is logged with `actor_id` for the audit trail.