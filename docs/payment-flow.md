# Payment Flow

Donations move through a defined pipeline so payment status, wallet credits, and receipts stay consistent: **Order → Payment → Verification/Webhook → Donation → Wallet**.

### 1. Order Creation

- **Controller**: `PaymentController::createOrder()`
- **Service**: `PaymentOrderService`

A `donations` record is created with `status = pending`, and the gateway (`RazorpayGateway::createOrder()`) returns a `gateway_order_id`. The payment gateway, order ID, currency, and amount are stored on the donation.

### 2. Payment

The user pays through Razorpay checkout (client-side `payment.js`). On success, the browser is redirected to `/payment/verify`.

### 3. Verification / Webhook

- **Controller**: `PaymentVerificationService::verify()`
- **Webhook**: `PaymentWebhookService::handle()`

Both paths do the same thing:

- Validate the signature / payload.
- Check `payment_id` uniqueness (backed by the unique index on `donations.payment_id`) — this makes the flow idempotent.
- Move the donation to `completed` or `failed`.
- On completion: credit the owner's wallet, fire events, and queue the receipt email.

### 4. Donation

- **Model**: `Donation`

After successful verification or webhook processing, the donation is the source of truth for the transaction. It links to `wallet_transactions` (the credit to the campaign owner) and to `payout_attempts` (used later during settlement).

### 5. Wallet

- **Service**: `WalletService`
- **Model**: `WalletTransaction`

On donation completion, `credit()` is called on the owner's wallet. The transaction is recorded with `type = donation_received` and `reference_id = donation.id`.

### Key Invariants

- `payment_id` is unique across donations.
- Duplicate verification/webhook calls never credit the wallet twice (idempotency check).
- Failed payments never credit the wallet.