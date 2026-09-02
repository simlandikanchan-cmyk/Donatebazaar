# Settlement Flow

Settlements move funds from a held donation balance to a real payout: **Donation → Hold → Settlement → Payout Attempt → Gateway → Completion**.

### 1. Donation Hold

After a donation completes, the money is held in the organization's wallet. `WalletService::holdForSettlement()` creates a hold transaction and `reserved_balance` on the wallet increases.

### 2. Settlement

- **Model**: `CampaignSettlement`

A settlement is created when an admin approves a payout request. Status follows `pending_approval` → `approved` → `processing` → `paid` / `failed` / `cancelled`. `SettlementService::approve()` performs the state transition and creates a `PayoutAttempt`.

### 3. Payout Attempt

- **Model**: `PayoutAttempt`

Created on settlement approval. It carries `gateway_reference`, `trace_id`, and `correlation_id`. `RetrySettlementJob` retries failed attempts with backoff.

### 4. Gateway

- **Gateway**: `RazorpayGateway::createPayout()`
- Called by the `ProcessSettlementPayout` job.

On success the settlement moves to `paid` and the donations to `settled`. On failure, `RetrySettlementJob` is scheduled with exponential backoff.

### 5. Completion

- **Paid**: Donations are marked `settled`, and the wallet hold is released/credited.
- **Failed**: Funds return to the wallet via `restoreSettlementFunds()`.
- **Cancelled**: Funds return to the wallet.

### Reconciliation

- **Job**: `ReconciliationJob`

The job runs under a distributed lock (`Cache::lock('reconciliation_job_lock', 300)`). `ReconciliationService::reconcile()` picks up settlements stuck in `processing` and cross-checks them against the gateway. State transitions stay atomic thanks to `lockForUpdate()`.