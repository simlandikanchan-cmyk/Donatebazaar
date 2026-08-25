# Donation Receipt System

## Overview

The receipt system generates, delivers, and secures donation receipts for completed payments. It spans payment verification, financial side effects, email delivery, PDF generation, and secure download.

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

## Configuration

All operational values are in `config/services.php` under `donation`:

| Key | Env Variable | Default | Purpose |
|-----|-------------|---------|---------|
| `platform_fee_percent` | `DONATION_PLATFORM_FEE_PERCENT` | `5.0` | Platform fee percentage |
| `receipt_url_ttl_hours` | `DONATION_RECEIPT_URL_TTL_HOURS` | `24` | Signed URL expiration |
| `min_amount` | `DONATION_MIN_AMOUNT` | `1` | Minimum donation (INR) |
| `max_amount` | `DONATION_MAX_AMOUNT` | `500000` | Maximum donation (INR) |
| `currency` | `DONATION_CURRENCY` | `INR` | Currency code |

## Key Components

### DonationReceiptService

Single source of truth for receipt data. Never recalculates financial values.

- `data(Donation, withUrls)` → array of receipt fields
- `receiptDownloadUrl(Donation)` → signed URL
- `receiptFileName(Donation)` → sanitized filename
- `isReceiptAvailable(Donation)` → completed + not refunded + not deleted
- `isAuthorized(Donation, User?)` → owner or admin

### DonationCompletionService

Extracted from PaymentVerificationService and PaymentWebhookService. Handles all financial side effects atomically.

### Receipt Authorization

- **Signed URL path** (`/donation-receipt/{id}/download`): guest access with valid signed URL + availability check
- **History path** (`/donations/{id}/receipt`): authenticated owner or admin only

## Security

- Signed URLs expire automatically (Laravel `temporarySignedRoute`)
- Dompdf `isRemoteEnabled=false` prevents SSRF
- Blade escaping prevents XSS in PDF/email
- Webhook HMAC-SHA256 verification
- Razorpay signature + amount + currency + status verification
- Distributed locks prevent duplicate completion
- Soft-deleted donations cannot have receipts downloaded

## Financial Integrity

- All amounts calculated server-side at donation creation
- Stored values used everywhere (no recalculation in email/PDF/controller)
- Wallet credit idempotent (checks existing transaction)
- Coupon redemption idempotent (checks existing redemption)
- Unique receipt_number enforced at DB level
- Unique payment_id enforced at DB level

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
- Structured arrays with sensitive data redaction

## Testing

Receipt tests: `tests/Feature/DonationReceiptTest.php`
E2E tests: `tests/Feature/RealTimeQaEndToEndTest.php`
Payment flow: `tests/Feature/PaymentFlowTest.php`
Gateway: `tests/Unit/Gateway/RazorpayGatewayTest.php`
