# Donation Receipt System

The receipt system starts at payment verification and ends with a securely downloadable PDF. It covers the financial side effects of a completed donation, email delivery, PDF generation, and the download flow.

## Receipt Lifecycle

```text
Donation Initiation
        ↓
PaymentOrderService::initiateDonation()
  - Validates amount (configurable min/max)
  - Validates coupon server-side
  - Creates Donation record with:
      total_amount, original_amount, discount_amount
      platform_fee, net_amount
      receipt_number (unique, 12 random chars)
      order_id, payment_status = 'pending'
  - Creates Razorpay order
        ↓
Razorpay Payment (browser)
        ↓
Browser Verification: POST /payment/verify
  PaymentVerificationService::verifyPayment()
  - Validates signature
  - Verifies payment details (amount, order, currency, status)
  - Rate limits + distributed lock
  - Delegates to DonationCompletionService::complete()
        ↓
Webhook: POST /payment/webhook
  PaymentWebhookService::handleWebhook()
  - Verifies HMAC signature
  - Routes payment.captured → DonationCompletionService::complete()
        ↓
DonationCompletionService::complete()
  Inside DB transaction with row locks:
    1. Marks donation completed + sets paid_at
    2. Increments campaign.platform_earnings
    3. Decrements product stock (if product donation)
    4. Consumes product reservations
    5. Redeems coupon
    6. Credits owner wallet (idempotent)
    7. Logs completion
  After transaction commits:
    8. Queues DonationReceiptMail
        ↓
Receipt Email (queued)
  DonationReceiptMail
  - Uses DonationReceiptService::data() for all values
  - Includes signed download URL (24h TTL, configurable)
        ↓
Receipt PDF Download
  GET /donation-receipt/{donation}/download
  Protected by:
    - 'signed' middleware (Laravel temporary signed URL)
    - DonationReceiptService::isReceiptAvailable()
      (completed + not refunded + not soft-deleted)
    - DonationReceiptService::isAuthorized()
      (owner or admin)
  Generates PDF via Dompdf (isRemoteEnabled=false)
```

The completion path is the critical part: all financial side effects happen inside a single DB transaction with row locks, and only after it commits is the receipt email queued. The browser verification and the webhook converge on the same `DonationCompletionService::complete()` method, which is what keeps the two paths behaving identically.

## Configuration

Operational values live in `config/services.php` under `donation`:

| Key | Env Variable | Default | Purpose |
|-----|-------------|---------|---------|
| `platform_fee_percent` | `DONATION_PLATFORM_FEE_PERCENT` | `5.0` | Platform fee percentage |
| `receipt_url_ttl_hours` | `DONATION_RECEIPT_URL_TTL_HOURS` | `24` | Signed URL expiration |
| `min_amount` | `DONATION_MIN_AMOUNT` | `1` | Minimum donation (INR) |
| `max_amount` | `DONATION_MAX_AMOUNT` | `500000` | Maximum donation (INR) |
| `currency` | `DONATION_CURRENCY` | `INR` | Currency code |

## Key Components

### DonationReceiptService

Single source of truth for receipt data. It never recalculates financial values — amounts are stored at donation creation and read back verbatim.

- `data(Donation, withUrls)` → array of receipt fields
- `receiptDownloadUrl(Donation)` → signed URL
- `receiptFileName(Donation)` → sanitized filename
- `isReceiptAvailable(Donation)` → completed + not refunded + not deleted
- `isAuthorized(Donation, User?)` → owner or admin

### DonationCompletionService

Extracted from `PaymentVerificationService` and `PaymentWebhookService` so both entry points share one atomic completion routine with all financial side effects.

### Receipt Authorization

- **Signed URL path** (`/donation-receipt/{id}/download`): guest access works only with a valid signed URL plus the availability check.
- **History path** (`/donations/{id}/receipt`): authenticated owner or admin only.

## Security

- Signed URLs expire automatically (Laravel `temporarySignedRoute`).
- Dompdf runs with `isRemoteEnabled=false`, which prevents SSRF through the PDF renderer.
- Blade escaping prevents XSS in PDF and email.
- Webhook payloads are verified with HMAC-SHA256.
- Razorpay signature, amount, currency, and status are all verified.
- Distributed locks prevent duplicate completion.
- Soft-deleted donations can never have their receipt downloaded.

## Financial Integrity

- Amounts are calculated server-side when the donation is created.
- Stored values are used everywhere — email, PDF, and controllers never recompute fees.
- Wallet credit is idempotent (checks for an existing transaction).
- Coupon redemption is idempotent (checks for an existing redemption).
- `receipt_number` is unique at the DB level.
- `payment_id` is unique at the DB level.

## Email Queueing

Receipt emails are queued via `ShouldQueue` with retry/backoff:
- `tries = 3`
- `timeout = 60`
- `backoff = [60, 300, 900]`

## Database Constraints

- `donations.receipt_number` — UNIQUE
- `donations.payment_id` — UNIQUE
- `donations.deleted_at` — soft deletes enabled

## Logging

- Payment/receipt events → `storage/logs/payments.log`
- Donation completion events → `storage/logs/donations.log`
- Structured arrays with sensitive data redacted.

## Testing

- Receipt tests: `tests/Feature/DonationReceiptTest.php`
- E2E tests: `tests/Feature/RealTimeQaEndToEndTest.php`
- Payment flow: `tests/Feature/PaymentFlowTest.php`
- Gateway: `tests/Unit/Gateway/RazorpayGatewayTest.php`