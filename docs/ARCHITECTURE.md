# Architecture Documentation

## System Overview

DonateBazaar is a Laravel 12 application following MVC architecture with service layer abstraction, event-driven processing, and queue-based background jobs.

---

## Application Layers

### 1. Presentation Layer

**Controllers** (`app/Http/Controllers/`)
- 78 controllers organized by domain
- Admin controllers handle dashboard operations
- API controllers expose REST endpoints
- Auth controllers manage authentication flows

**Views** (`resources/views/`)
- 273 Blade templates
- Three distinct UI trees: Admin, User, Public
- Component-based reusable UI elements
- 22 email templates for transactional messaging

### 2. Service Layer

**Services** (`app/Services/`)
- 12 service classes encapsulating business logic
- Payment services handle Razorpay integration
- Notification services manage multi-channel delivery
- Wallet services implement financial operations

**Payment Services** (`app/Services/Payment/`)
| Service | Responsibility |
|---|---|
| PaymentOrderService | Create Razorpay orders |
| PaymentVerificationService | Verify payment signatures |
| PaymentWebhookService | Handle webhook events |
| RefundService | Process refund requests |
| DonationCompletionService | Complete donation lifecycle |

### 3. Domain Layer

**Models** (`app/Models/`)
- 56 Eloquent models
- Financial models with strict integrity
- Encrypted casts for sensitive data
- Soft deletes for audit trails

**Events** (`app/Events/`)
- 10 domain events for settlement lifecycle
- Event-driven architecture for loose coupling

**Listeners** (`app/Listeners/`)
- 11 event listeners
- Notification dispatch on state changes
- Auto-processing of approved settlements

### 4. Infrastructure Layer

**Jobs** (`app/Jobs/`)
- 5 queue jobs for background processing
- Settlement processing and retry logic
- Reconciliation jobs for financial integrity

**Notifications** (`app/Notifications/`)
- 16 notification classes
- Preference-driven delivery
- Multi-channel support (mail, database)

---

## Database Architecture

### Schema Statistics

| Metric | Count |
|---|---|
| Tables | 95 |
| Migrations | 244 |
| Factories | 14 |

### Table Categories

| Category | Tables | Description |
|---|---|---|
| Users & Auth | 8 | Users, roles, permissions, sessions |
| Campaigns | 10 | Campaigns, products, updates, media |
| Donations | 6 | Donations, items, payments, refunds |
| Financial | 10 | Wallets, transactions, settlements, payouts |
| KYC | 3 | Verifications, organizations, applications |
| Content | 12 | Blogs, comments, likes, reports, FAQs |
| Events | 3 | Events, registrations |
| Other | 43 | Categories, coupons, jobs, volunteers, etc. |

### Financial Tables

| Table | Purpose |
|---|---|
| wallets | User wallet balances |
| wallet_transaction | Double-entry transaction ledger |
| settlements | Settlement requests |
| settlement_items | Line items per settlement |
| settlement_state_logs | State transition audit trail |
| payout_accounts | Bank account details (encrypted) |
| payout_attempts | Payout execution records |
| refunds | Refund requests and status |

---

## Authentication & Authorization

### Multi-Guard Authentication

| Guard | Provider | Usage |
|---|---|---|
| web | users | Regular users |
| admin | admins | Admin panel |

### Authentication Methods

1. **Email/Password** — Laravel Breeze
2. **Google OAuth** — Laravel Socialite
3. **Phone OTP** — Custom OTP controller (requires SMS provider)

### Authorization

- **Middleware**: `AdminMiddleware` for admin routes
- **Policies**: Event, DonationReceipt, Blog
- **Role Check**: String-based (`$user->role === 'admin'`)

---

## Payment Architecture

### Razorpay Integration

```
┌──────────┐     ┌──────────────┐     ┌──────────┐
│  Donor   │────▶│  Donation    │────▶│ Razorpay │
│          │     │  Controller  │     │  Order   │
└──────────┘     └──────────────┘     └────┬─────┘
                                           │
                                           ▼
┌──────────┐     ┌──────────────┐     ┌──────────┐
│ Webhook  │────▶│  Payment     │────▶│ Donation │
│ Handler  │     │  Verification│     │ Complete │
└──────────┘     └──────────────┘     └──────────┘
```

### Payment Flow

1. User initiates donation
2. System creates Razorpay order
3. User completes payment on Razorpay
4. Razorpay sends webhook
5. System verifies signature
6. Donation marked complete
7. Wallet credited

### Idempotency

- Payment verification uses idempotency keys
- Prevents duplicate processing
- Webhook events tracked for deduplication

---

## Wallet System

### Double-Entry Accounting

Every financial transaction creates two entries:

| Entry | Debit | Credit |
|---|---|---|
| Donor wallet | No | Yes (reserved) |
| Campaign wallet | Yes | No |

### Transaction Types

| Type | Description |
|---|---|
| credit | Funds added |
| debit | Funds withdrawn |
| reserve | Funds held for settlement |
| release | Reserved funds released |

### Balance Calculation

```
available_balance = total_credits - total_debits - reserved_balance
```

---

## Settlement State Machine

```
┌──────────┐
│  PENDING │◀──────────────────────────────┐
└────┬─────┘                               │
     │                                     │
     ▼                                     │
┌──────────┐     ┌──────────┐             │
│ APPROVED │────▶│ PROCESSING│             │
└──────────┘     └────┬─────┘             │
                      │                   │
              ┌───────┴───────┐           │
              ▼               ▼           │
        ┌──────────┐    ┌──────────┐      │
        │   PAID   │    │  FAILED  │──────┘
        └──────────┘    └──────────┘  (retry)
```

### States

| State | Description |
|---|---|
| pending | Awaiting admin review |
| auto_approved | Automatically approved by system |
| approved | Manually approved by admin |
| processing | Payout in progress |
| paid | Funds transferred |
| failed | Payout failed (retryable) |
| rejected | Admin rejected |
| cancelled | Cancelled by user |

---

## Queue Architecture

### Queues

| Queue | Processes | Purpose |
|---|---|---|
| emails | 1 | Transactional emails |
| default | 2 | General background jobs |
| notifications | 1 | Notification delivery |

### Jobs

| Job | Purpose |
|---|---|
| ProcessSettlementJob | Execute payout |
| RetrySettlementJob | Retry failed payout |
| ReconciliationJob | Financial reconciliation |
| SendCampaignProductStatusJob | Product status updates |

### Scheduled Tasks

| Task | Frequency | Purpose |
|---|---|---|
| campaigns:expire | Daily | Expire ended campaigns |
| campaigns:send-ending-soon | Daily | Notify ending campaigns |
| campaigns:send-kyc-reminders | Daily | KYC reminder emails |
| product-reservations:prune-expired | 5 minutes | Clean expired reservations |
| wallet:release-reserves | Daily | Release held funds |

---

## Security Architecture

### Layers

1. **Transport** — HTTPS enforced in production
2. **Application** — CSRF, XSS, SQL injection protection
3. **Authentication** — Multi-factor (password + OTP)
4. **Authorization** — Role-based access control
5. **Data** — Encryption at rest for sensitive fields

### Security Headers

```
Content-Security-Policy: ...; nonce-xxxxxxxx
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
Strict-Transport-Security: max-age=31536000
Referrer-Policy: strict-origin-when-cross-origin
```

### Rate Limiters

| Limiter | Rate | Applied To |
|---|---|---|
| financial | 10/min | Refunds, settlements |
| gift-card | 10/min | Gift card operations |
| webhooks | 120/min | Razorpay webhooks |

---

## Caching Strategy

### Cache Drivers

| Usage | Driver |
|---|---|
| Sessions | Redis |
| Cache | Redis |
| Queue | Redis |

### Cache Invalidation

- Campaign changes → Clear dashboard stats
- Donation created → Clear dashboard stats
- Manual cache clear via `artisan cache:clear`

---

## File Storage

### Disks

| Disk | Usage |
|---|---|
| public | Campaign images, KYC documents |
| local | Temporary files |

### Cloudinary (Optional)

- Configured but optional
- Used for image optimization
- WebP conversion support

---

## Testing Strategy

### Test Types

| Type | Count | Purpose |
|---|---|---|
| Unit | 30 | Isolated class testing |
| Feature | 58 | Integration testing |
| Browser | 2 | E2E user flows |

### Test Organization

```
tests/
├── Unit/
│   ├── Auth/           # Authentication tests
│   ├── FormValidation/ # Form request tests
│   ├── Risk/           # Risk engine tests
│   ├── Settlement/     # Settlement tests
│   └── ...
├── Feature/
│   ├── Auth/           # Auth flow tests
│   ├── Admin/          # Admin feature tests
│   ├── Campaign/       # Campaign tests
│   └── ...
└── browser/
    ├── comprehensive-verification.spec.ts
    └── real-browser-financial-e2e.spec.ts
```
