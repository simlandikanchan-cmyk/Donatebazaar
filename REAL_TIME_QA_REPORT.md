# CAMPAIGN + DONATION END-TO-END TEST REPORT

**Date:** 2026-08-14 | **App:** DonateBaazar (Laravel 12.61.0 / PHP 8.2.12 / MariaDB, XAMPP Windows)
**Scope:** Full campaign lifecycle (register → create → approve → public page → donate → pay → verify/webhook → wallet → admin) via real-HTTP Laravel feature tests.

---

## 1. Environment

| Item | Value |
|---|---|
| App path | `C:\xampp\htdocs\fundraise` |
| Framework | Laravel 12.61.0, PHP 8.2.12 |
| APP_ENV / DB (dev) | `local` / `donatebazaar_final` |
| Test DB | `donatebazaar_test` (fresh-migrated per run, `RefreshDatabase`) |
| Mail driver (tests) | `array` (emails asserted in-memory, no real mail sent) |
| Queue (tests) | `sync` (notifications delivered immediately) |
| Payment gateway | Razorpay **test/sandbox keys** (`rzp_test_*`) — **mocked gateway, no live money moved** |

**Real test accounts (checked in dev DB, pre-existing, both active):**
- Creator: `simlandikanchan@gmail.com` — role `ngo`, id 7 (dev DB), created via HTTP `/register` in tests.
- Donor: `simlandikanchan2@gmail.com` — role `donor`, id 8 (dev DB), created via HTTP `/register` in tests.
- Admin: `admin@DonateBazaar.com` (id 6) — used for KYC/campaign approval via real HTTP login.

> Note: Dev DB accounts are pre-existing (status `active`); the tests register the exact same emails over HTTP in the isolated test DB and use them end-to-end.

---

## 2. Test Summary

| Metric | Value |
|---|---|
| Tests before (baseline) | 854 |
| Tests after | **879** |
| New tests added | **25** (`tests/Feature/RealTimeQaEndToEndTest.php`) |
| Assertions | 2695 (full suite, final run) |
| Failures before | 1 (pre-existing, `CampaignDonationEndToEndTest` signature test) |
| Failures after | **0 — all 879 tests pass** |
| Frontend build (`npm run build`) | **OK** — Vite 7.3.6, 70 modules, built in 3.96s; admin/user/public CSS + JS bundles + manifest generated. |
| Manual browser testing | **None performed** — verified via Laravel feature/integration HTTP tests only. Build verification is not equivalent to browser UI verification. |

**Final command output:**
```
php artisan test
Tests:    879 passed (2695 assertions)
Duration: 122.03s
```

---

## 3. End-to-End Flow Results (New Test File: `tests/Feature/RealTimeQaEndToEndTest.php`)

| # | Test | Result |
|---|---|---|
| 1 | Creator (`simlandikanchan@gmail.com`) registers + logs in via real HTTP (role ngo) | ✅ PASS |
| 2 | Donor (`simlandikanchan2@gmail.com`) registers + logs in via real HTTP (role donor) | ✅ PASS |
| 3 | Creator creates campaign via HTTP POST `/campaign/store` (state `pending`) | ✅ PASS |
| 4 | Campaign validation rejects invalid payload (no row created) | ✅ PASS |
| 5 | Guest cannot create campaign (redirect to login) | ✅ PASS |
| 6 | Campaign is NOT public before approval (404) | ✅ PASS |
| 7 | Admin (real HTTP login) approves KYC + campaign → state `active`, `campaign_logs` entry | ✅ PASS |
| 8 | Approval is blocked until KYC is approved | ✅ PASS |
| 9 | Non-admin cannot approve campaign | ✅ PASS |
| 10 | Public page `campaigns/{category}/{slug}` loads after approval, shows title/goal/raised/category/owner | ✅ PASS |
| 11 | Donor creates ₹100 order (amount, currency INR, campaign, donor, fee 5.00, net 95.00) | ✅ PASS |
| 12 | Payment verification completes donation; `raised_amount` +100, `platform_earnings` +5 | ✅ PASS |
| 13 | Creator wallet `reserved_balance` +95.00; `WalletTransaction` (source=donation, type=credit, status=completed) | ✅ PASS |
| 14 | Receipt email sent to donor + `DonationReceived` notification for owner | ✅ PASS |
| 15 | Webhook `payment.captured` (valid HMAC) completes pending donation | ✅ PASS |
| 16 | Webhook invalid signature rejected | ✅ PASS |
| 17 | Duplicate webhook does not double-credit wallet | ✅ PASS |
| 18 | Webhook-then-verify idempotent (no double credit) | ✅ PASS |
| 19 | Failed payment webhook marks donation failed, no credit | ✅ PASS |
| 20 | Invalid payment signature → donation marked `failed`, 400 returned | ✅ PASS |
| 21 | Invalid donation amount rejected | ✅ PASS |
| 22 | Donation against paused campaign blocked | ✅ PASS |
| 23 | Admin can view campaign + donation records | ✅ PASS |
| 24 | Admin donation list contains the test donation | ✅ PASS |
| 25 | Full single journey end-to-end (register → create → approve → donate → verify → wallet) | ✅ PASS |

**25/25 PASS, 685 assertions.**

---

## 4. Issues Found & Resolved

### 4.1 Pre-existing failing test — FIXED (test-infrastructure)
- `tests/Feature/CampaignDonationEndToEndTest.php::test_invalid_payment_signature_returns_error` previously expected HTTP 400 but received 200.
- Root cause (two parts):
  1. `PaymentVerificationService` is bound as a **singleton**; it was already resolved with the success-gateway mock, so the failure mock swap had no effect → verification "succeeded" (200).
  2. The mock threw a generic `RuntimeException`; the real app only maps `SignatureVerificationError` to 400 (generic `\Throwable` → 500). The test did not exercise the real failure path.
- Fix: rebind the service instance with the throwing gateway and throw `SignatureVerificationError` (the exception the real service handles → 400 + donation `failed`). Now passes and genuinely asserts the failure path.

### 4.2 Suite isolation quirk (pre-existing, not modified)
- In full-suite runs, other test files leak factory-created `campaigns`/`users` into the shared test DB (RefreshDatabase transactions get committed by tests that exercise `forceDelete`/cascade paths). My two absolute `assertDatabaseCount('campaigns', 0)` assertions were rewritten to assert the **business outcome** (no campaign with the test title exists) so they are immune to pre-existing leakage. Suite is otherwise unaffected.

### 4.3 Confirmed product bug — `campaigns.approved_at` never set
- Schema column exists (migration `2026_02_18_095852`), but neither `Campaign::approve()` nor `CampaignWorkflowService` ever writes `approved_at`. A test asserts `assertNull` with an explanatory comment. **Recommendation: set `approved_at = now()` inside the approval flow.**

### 4.4 Configuration notes (not code bugs)
- `services.razorpay.webhook_secret` is **not set** in `.env` → webhook endpoint returns 500 "misconfigured" in real env. Tests set it explicitly via `Config::set` (expected test behavior). **Recommendation: set the webhook secret in `.env` before enabling live webhooks.**
- `.env` contains duplicate keys (`MAIL_MAILER` log+smtp, `QUEUE_CONNECTION` sync+database); last value wins.

---

## 5. How It Was Verified (Methodology)

- **No manual browser testing.** All flows were exercised as real HTTP requests through the full application stack: real routes → middleware (auth, throttle, account status) → controllers → requests (validation) → services → models → DB → queue (sync) → mail (array driver) → notifications.
- Exact accounts and exact payloads used (creator/donor emails above, ₹100.00 donation).
- Payment/Razorpay: sandbox keys + mocked gateway + locally computed HMAC webhook signatures — consistent with the repo's existing test infrastructure. No live money was involved.
- Full suite run: `php artisan test` → 879 passed / 0 failed (2695 assertions, 122s).
- Frontend: `npm run build` → success; verified all Vite entries (public, admin `core`/dashboard/finance…, user) emitted CSS/JS + manifest.

---

## 6. Verdict

**🟡 VERIFIED WITH ISSUES (APP LIKELY TO WORK)**

The complete campaign → donation → payment → wallet → admin flow works end-to-end (25/25 new tests + full suite 879/0). The verified-with-issues status reflects three non-blocking points: (1) `approved_at` is never written by the approval flow, (2) webhook secret missing from `.env` (must be configured before production webhooks), (3) verification used mocked/sandbox payment infrastructure rather than a live Razorpay transaction or a browser.

## 7. Commands to Reproduce

```bash
php artisan test                                      # 879 passed, 0 failed (~122s)
php artisan test --filter=RealTimeQaEndToEndTest      # 25 passed (685 assertions)
npm run build                                         # Vite production build, OK
```
