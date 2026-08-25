# Settlement Flow

## Donation → Hold → Settlement → Payout Attempt → Gateway → Completion

### 1. Donation Hold
- After donation completes, funds are held in the organization's wallet.
- `WalletService::holdForSettlement()` creates a hold transaction.
- `reserved_balance` on wallet increases.

### 2. Settlement
- **Model**: `CampaignSettlement`
- Created when admin approves a payout request.
- Status flow: `pending_approval` → `approved` → `processing` → `paid` / `failed` / `cancelled`.
- `SettlementService::approve()` transitions state and creates `PayoutAttempt`.

### 3. Payout Attempt
- **Model**: `PayoutAttempt`
- Created on settlement approval.
- Contains `gateway_reference`, `trace_id`, `correlation_id`.
- `RetrySettlementJob` retries failed attempts with backoff.

### 4. Gateway
- **Gateway**: `RazorpayGateway::createPayout()`
- Called by `ProcessSettlementPayout` job.
- On success: settlement → `paid`, donations → `settled`.
- On failure: `RetrySettlementJob` scheduled with exponential backoff.

### 5. Completion
- **Paid**: Donations marked `settled`, wallet hold released/credited.
- **Failed**: Funds returned to wallet via `restoreSettlementFunds()`.
- **Cancelled**: Funds returned to wallet.

### Reconciliation
- **Job**: `ReconciliationJob`
- Runs with distributed lock (`Cache::lock('reconciliation_job_lock', 300)`).
- `ReconciliationService::reconcile()` checks stuck `processing` settlements against gateway.
- State transitions are atomic with `lockForUpdate()`.
