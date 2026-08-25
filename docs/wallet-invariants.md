# Wallet Invariants

## Balance
- `Wallet::balance` = total credited minus total debited (not including holds).
- `Wallet::reserved_balance` = amount locked for pending settlements.
- `Wallet::available_balance` = `balance` - `reserved_balance`.

## Locking
- Settlement holds use `reserved_balance` increment.
- `WalletService::holdForSettlement()` and `releaseHold()` are atomic within DB transactions.
- Row-level locking via `lockForUpdate()` on wallet during adjustments.

## Idempotency
- Wallet transactions have unique `reference_id` + `source_type` combinations where applicable.
- Duplicate donation verification/webhook does not create duplicate credit transactions.
- Payout idempotency key (`payout_idempotency_key`) prevents duplicate payout attempts.

## Refund Behavior
- **Service**: `RefundService`
- Refund creates a debit transaction on the recipient's wallet.
- Original donation marked `refunded`.
- Wallet balance decreases immediately.
- If wallet has insufficient funds, refund fails gracefully (recorded but not processed).

## Manual Adjustments
- Admin can credit/debit via `WalletController::adjust()`.
- Debit requires sufficient `available_balance` — fails gracefully otherwise.
- All adjustments are logged with `actor_id` for audit trail.
