# Payment Flow

## Order → Payment → Verification/Webhook → Donation → Wallet

### 1. Order Creation
- **Controller**: `PaymentController::createOrder()`
- **Service**: `PaymentOrderService`
- Creates a `donations` record with `status = pending`.
- Calls gateway (`RazorpayGateway::createOrder()`) to get `gateway_order_id`.
- Stores `payment_gateway`, `gateway_order_id`, `currency`, `amount` on donation.

### 2. Payment
- User completes payment via Razorpay checkout (client-side `payment.js`).
- On success, user is redirected to `/payment/verify`.

### 3. Verification / Webhook
- **Controller**: `PaymentVerificationService::verify()`
- **Webhook**: `PaymentWebhookService::handle()`
- Both paths:
  - Validate signature / payload.
  - Check `payment_id` uniqueness (`donations.payment_id` unique index) — idempotent.
  - Transition donation to `completed` or `failed`.
  - On completion: credit owner wallet, trigger events, send receipt email.

### 4. Donation
- **Model**: `Donation`
- Final state after successful verification/webhook.
- Linked to `wallet_transactions` (credit to campaign owner).
- Linked to `payout_attempts` (for settlement tracking).

### 5. Wallet
- **Service**: `WalletService`
- **Model**: `WalletTransaction`
- On donation completion: `credit()` called on owner's wallet.
- Transaction records `type = donation_received`, `reference_id = donation.id`.

### Key Invariants
- `payment_id` is unique across donations.
- Duplicate verification/webhook calls do not double-credit (idempotency check).
- Failed payments do not credit wallet.
