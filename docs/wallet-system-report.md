# Wallet & Settlement System — Technical Report

**Project:** fundraise (Laravel crowdfunding platform)
**Currency:** INR
**Audience:** Engineering / Product team
**Status:** Live code review as of this report

---

## 1. Executive Summary

The platform uses a reserve-based wallet with admin-approved settlements (payouts).
Money from donations flows into a fundraiser's wallet, sits in a hold/reserve
period, matures into an available balance, and can then be withdrawn via a
settlement request that an admin must approve before any bank/UPI payout.

All balance mutations are funneled through a single service (`WalletService`) using
DB transactions, row-level locks, and idempotency guards for safety under
concurrency and payment-gateway webhook retries.

> **Important:** The final bank/UPI payout is currently a **placeholder**
> (`initiatePayout()` logs and returns a fake reference). No real money is
> transferred yet, even though settlements are marked `paid`. Gateway integration
> (e.g. Razorpay Payouts) is pending. See Section 9.

---

## 2. Core Concepts

### 2.1 Wallet (`wallets`, `app/Models/Wallet.php`)
Polymorphic (`owner_type` / `owner_id`) — can belong to a `User` or `Organization`.
In practice donation credits and settlements resolve to the **campaign creator's
User wallet**. Three balance buckets:

| Field | Meaning |
|---|---|
| `reserved_balance` | Freshly-credited donation funds still in the hold window (not withdrawable) |
| `balance` | Available/matured funds (withdrawable) |
| `pending_settlement_balance` | Funds locked in a payout request awaiting admin approval |
| `currency` | INR |

**Available (withdrawable) balance** = `balance − pending_settlement_balance`
(computed accessor).

### 2.2 Wallet Transactions (`wallet_transactions`, `app/Models/WalletTransaction.php`)
Immutable ledger. Each credit/debit stores a `balance_after` snapshot.
- **Sources:** `donation`, `refund`, `settlement`, `gift_card`, `coupon`, `adjustment`
- **Types:** `credit`, `debit`
- **Status:** `pending`, `completed`, `failed`

### 2.3 Settlement (`campaign_settlements`, `app/Models/CampaignSettlement.php`)
A payout request. Holds `gross_amount`, `platform_fee`, `net_amount`, `status`, and
audit fields (`approved_by/at`, `rejected_by/at`, `rejection_reason`,
`gateway_reference`, `paid_at`).
**Statuses:** `pending`, `pending_approval`, `approved`, `paid`, `rejected`, `failed`.

### 2.4 Settlement Items (`settlement_items`, `app/Models/SettlementItem.php`)
Line items linking each specific `Donation` to a settlement (amount per donation).
Used to "lock" donations so they cannot be settled twice.

### 2.5 Payout Account (`payout_accounts`, `app/Models/PayoutAccount.php`)
Bank/UPI destination attached to an Organization, with an `is_verified` flag.

---

## 3. Money Lifecycle

```
Donation paid ──► reserved_balance (hold, default 7 days)
                        │
        wallet:release-reserves (daily) / on payout request
                        ▼
                    balance (available)
                        │
        user requests payout (settlement)
                        ▼
          pending_settlement_balance (locked)
                        │
            ┌───────────┴────────────┐
      admin approves            admin rejects
            ▼                        ▼
   debited → payout           returned to balance
   status = paid              status = rejected
```

---

## 4. User Side

### 4.1 Earning (automatic credit)
On successful payment verification (`PaymentController`), when a donation becomes
`completed`, `WalletService::credit()` adds `net_amount` to **`reserved_balance`**
(source `donation`). Idempotent against webhook retries.

### 4.2 Reserve maturation
- Default hold = **7 days** (`WalletService::DEFAULT_HOLD_DAYS`).
- `releaseMaturedReserves()` moves matured funds `reserved_balance → balance`,
  stamps `released_at`, and records an `adjustment` credit.
- Runs via scheduled command `wallet:release-reserves` (daily).

### 4.3 Wallet dashboard (`GET /user/dashboard/wallet`, `WalletController@index`)
Shows balances, transaction ledger, eligible donations for payout, pending
settlements, and saved payout accounts.

### 4.4 Save payout account (`POST .../wallet/payout-account`)
Validates account holder + (bank details OR UPI). Auto-creates a personal
`individual` Organization if the user has none (settlements are org-scoped).

### 4.5 Request payout (`POST .../wallet/request-payout` → `requestSettlement()`)
1. Validates donations (completed, not refunded).
2. Rejects donations already locked in a pending/approved settlement.
3. Releases any matured reserves among the selected donations.
4. Verifies `balance ≥ total` (else `InsufficientWalletBalanceException`).
5. Moves `total` from `balance → pending_settlement_balance` (locked, **not debited**).
6. Creates `CampaignSettlement` (`pending_approval`) + `SettlementItem` rows.

---

## 5. Admin Side

All routes behind `['auth','admin']` middleware.

### 5.1 Settlements (`Admin/SettlementController`, `routes/admin/settlements.php`)

| Route | Action |
|---|---|
| `GET admin/settlements` | list (pending_approval first, filterable) |
| `GET admin/settlements/{id}` | detail + payout account + scrutiny flags |
| `POST .../approve` | approve |
| `POST .../reject` | reject (reason required) |

**Scrutiny flags** (computed in `show()`):
- High value (net ≥ ₹100,000)
- Unverified payout account on file
- Organization KYC not verified
- Refund count in the last 30 days

**Approve** (`approveSettlement()`): locks wallet, verifies
`pending_settlement_balance ≥ net_amount`, **debits** the amount, records a
`settlement` debit, calls `initiatePayout()`, sets status `paid` + audit fields,
sends Approved + Paid notifications.
> Note: flow jumps straight to `paid`; the `approved` intermediate status exists in
> the enum but is unused.

**Reject** (`rejectSettlement()`): requires a reason, returns funds
`pending_settlement_balance → balance` (nothing was ever debited), sets status
`rejected` + audit fields, sends Rejected notification.

### 5.2 Wallets (`Admin/WalletController`, `routes/admin/wallets.php`)

| Route | Action |
|---|---|
| `GET admin/wallets` | list all wallets (search by owner) |
| `GET admin/wallets/{id}` | full ledger + manual adjust form |
| `POST .../adjust` | manual credit/debit (source `adjustment`, required reason) |

### 5.3 Refunds (admin-triggered debit)
On refund (`PaymentController` webhook / `Admin/DonationController`),
`WalletService::debit()` runs with source `refund`, pulling from `reserved_balance`
first if the hold is still active, otherwise from `balance`. Failures are logged,
not fatal.

---

## 6. Concurrency, Safety & Integrity
- **Row locks:** every mutation uses `Wallet::lockForUpdate()` inside `DB::transaction`.
- **Idempotency:** `findExisting()` prevents double credit/debit for the same
  `(wallet, reference, source)` — safe against gateway webhook retries.
- **Cache lock** (`wallet_release_{id}`) guards reserve release.
- **Double-settle protection:** donations locked in `settlement_items` for
  pending/approved settlements are excluded from eligibility.
- **Ledger snapshots:** `balance_after` stored on each transaction for audit.

---

## 7. Key Files

| Category | Files |
|---|---|
| Service (engine) | `app/Services/WalletService.php`; `app/Services/SettlementService.php` (legacy, unwired) |
| Models | `Wallet`, `WalletTransaction`, `CampaignSettlement`, `SettlementItem`, `PayoutAccount` (+ settlement fields on `Donation`, `Organization`) |
| User controllers | `WalletController.php`; credit/debit in `PaymentController.php` |
| Admin controllers | `Admin/SettlementController.php`, `Admin/WalletController.php`, refund in `Admin/DonationController.php`, stats in `Admin/DashboardController.php` |
| Routes | `routes/web/dashboard.php`, `routes/admin/settlements.php`, `routes/admin/wallets.php`, `routes/console.php` |
| Views | `wallet/dashboard.blade.php`, `admin/wallets/{index,show}.blade.php`, `admin/settlements/{index,show}.blade.php` |
| Commands | `ReleaseWalletReserves.php` (`wallet:release-reserves`), `FixWalletCredits.php` (`wallet:fix-credits`) |
| Notifications | `SettlementApprovedNotification`, `SettlementPaidNotification`, `SettlementRejectedNotification` |
| Exception | `InsufficientWalletBalanceException` |

---

## 8. Recent UI/UX & Safety-Rail Changes (Admin Settlement Screen)

`resources/views/admin/settlements/show.blade.php` was hardened (all 5 tasks complete):
1. Approve button relabeled "Approve & Pay" → **"Approve Settlement"**; added a
   "Simulated payout — gateway integration pending" note on the Gateway Reference card.
2. Added JS `confirm()` guards on Approve/Reject; approve wording escalates when the
   settlement has scrutiny flags.
3. Rejection reason changed to a textarea; Reject button disabled until non-empty
   (trim-guarded, matching the server-side check in `WalletService::rejectSettlement()`).
4. Verified the refund-count scrutiny flag — it was already present and renders
   correctly in the "Needs extra scrutiny" box; schema/relations confirmed. No code
   change required.
5. Made settlement items clickable — donation ID links to `admin.donations.show`;
   campaign name links to `admin.campaign.show` (guarded for missing campaign).

No changes were made to `WalletService::approveSettlement()` / `rejectSettlement()`
transaction logic — this remained a UI/UX + safety-rail pass only.

---

## 9. Known Gaps / TODOs
1. **Payout is still a placeholder.** `initiatePayout()` returns a fake reference; no
   real transfer occurs, and settlements are still marked `paid` on approval. The
   admin UI now discloses this (Section 8, Task 1), but the underlying gateway
   integration itself has not changed. **Highest-priority follow-up.**
2. **Two settlement approaches exist.** Active flow uses `WalletService`
   (request → approve). Legacy `SettlementService` (`settleCampaign`, etc.) is not
   wired to current routes — candidate for removal.
3. **Payout account verification is manual** — no self-serve flow; approval only
   *flags* an unverified account (visible in the scrutiny box) rather than blocking it.
4. **`approved` status is skipped** — approve goes directly to `paid`.
5. **Hold days not per-org** — `wallet_hold_days` exists on Organization but the
   service uses the hard-coded 7-day constant.
6. **No automated tests for the Section 8 UI changes** (confirm guards, textarea
   validation, conditional campaign link) — currently manually verified only.

---

## 10. Recommendations (priority order)
1. Integrate a real payout gateway; only mark `paid` on gateway success, add a
   `processing`/failure path.
2. Block approval when no **verified** payout account exists.
3. Remove or consolidate the legacy `SettlementService`.
4. Honor per-org `wallet_hold_days`.
5. Add automated tests around credit/debit idempotency, reserve maturation, the
   settlement request → approve/reject balance transitions, and the new Section 8 UI
   safety rails (confirm guards, rejection validation, conditional links).
