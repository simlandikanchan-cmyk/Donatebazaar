# Fundraise — Full Web Application Report

**Date:** July 27, 2026
**Framework:** Laravel 12 (PHP 8.2)
**Location:** `C:\xampp\htdocs\fundraise`
**Database Engine:** SQLite (development) / MySQL (production)
**Frontend:** Vite + Tailwind CSS + Blade Templating

---

## 1. Application Overview

Fundraise is a **full-stack crowdfunding/fundraising platform** built from database schema to deployment. It supports campaign creation, donation processing (money and product types), payment integration via Razorpay, wallet management, KYC verification, risk scoring engine, settlement processing, organization/partnership management, blog system, events, job board, volunteer management, and a complete 28-section admin panel.

The application was designed using a **database-first approach** — starting with DB schema and migrations, then building the full layered architecture on top.

---

## 2. Technology Stack

| Layer | Technology |
|-------|-----------|
| Backend Framework | Laravel 12 |
| Language | PHP 8.2 |
| Database | SQLite (dev), MySQL (production) |
| ORM | Eloquent (Active Record pattern) |
| Frontend Build | Vite (Laravel Mix) |
| CSS Framework | Tailwind CSS (v3) |
| Payment Gateway | Razorpay (`razorpay/razorpay` ^2.9) |
| Cloud Storage | Cloudinary (`cloudinary-labs/cloudinary-laravel` ^3.0) |
| Image Processing | Intervention Image (`intervention/image-laravel` ^1.5) |
| Cache / Queue / Pub-Sub | Redis via `predis/predis` ^3.4 |
| OAuth Authentication | Laravel Socialite (^5.28) |
| Health Monitoring | Spatie Laravel Health (^1.40) |
| Debug / Profiling | Laravel Telescope (^5.20), Laravel Debugbar (^4.2), Laravel Query Detector |
| Dev Container | Laravel Sail (^1.41) |
| Code Quality | Laravel Pint (^1.29) |
| Testing | PHPUnit (^11.5.3) |
| Database Introspection | Doctrine DBAL (^4.4) |
| Queue Driver | Redis + database |
| Templating | Blade (server-side rendering) |

---

## 3. Codebase Metrics

### 3.1 File Counts by Directory

| Directory | File Count | Description |
|-----------|-----------|-------------|
| `database/migrations/` | 225 | Database schema migrations |
| `app/Models/` | 55 | Eloquent ORM models |
| `app/Http/Controllers/` | 40+ | Controllers (incl. 30 admin) |
| `app/Services/` | 13 top-level + subdirectories | Domain business logic |
| `app/Services/Risk/` | 10 | Risk engine components |
| `app/Http/Controllers/Admin/` | 29 | Admin panel controllers |
| `app/Http/Controllers/Auth/` | 11 | Authentication controllers |
| `app/Http/Controllers/Frontend/` | 1 | Frontend partnership controller |
| `app/Http/Controllers/User/` | 1 | User blog controller |
| `app/Http/Controllers/` (root) | 28 | Public/frontend controllers |
| `routes/admin/` | 28 | Admin route definition files |
| `routes/api/` | 9 | API route definition files |
| `routes/` (web) | 1 main entry | Web routes (14 included files) |
| `routes/api.php` | 1 main entry | API routes (9 included files) |
| `resources/views/` (Blade) | 263 | Blade templates (incl. 70 admin, 21 emails) |
| `resources/views/admin/` | 70 | Admin panel Blade views |
| `resources/views/emails/` | 21 | Email templates |
| `resources/css/` | 48+ | CSS files (10+ SCSS partials, 35+ page-specific) |
| `resources/js/` | 15 | JavaScript entry points |
| `app/Mail/` | 21 | Mailable classes |
| `app/Events/` | 10 | Domain events |
| `app/Listeners/` | 11 | Event listeners |
| `app/Jobs/` | 7 | Queueable background jobs |
| `app/Notifications/` | 12 | Notification classes |
| `app/Policies/` | 2 | Authorization policies (Blog, Event) |
| `app/Exceptions/` | 11 | Custom exception classes |
| `app/Actions/` | 2+ (Blog) | Single-responsibility action classes |
| `app/Console/Commands/` | 9 | Artisan commands |
| `app/Gateways/` | 1 | Payment gateway abstraction |
| `app/Contracts/Gateway/` | 2 | Gateway interface + PayoutResult |
| `app/Dto/` | (empty dir) | Data transfer objects |
| `app/Modules/Activity/` | 30+ | Self-contained Activity module (full MVC) |
| `app/View/Components/` | 2 | Blade component classes |
| `app/View/Composers/` | 1 | View composer |
| `app/Providers/` | 4 | Service providers |
| `app/Middleware/` | (Laravel default + custom) | HTTP middleware |
| `database/seeders/` | 5 | Database seeders |
| `database/factories/` | 4 | Model factories |
| `tests/Feature/` | 21 | Feature tests |
| `tests/Unit/` | 25 | Unit tests |
| `tests/` root | 1 | Base TestCase |
| `scripts/` | 1 | Helper scripts |

### 3.2 Totals

- **Total PHP files in `app/`:** 275
- **Total migration files:** 225
- **Total model files:** 55
- **Total controllers:** ~40 (30 admin + 11 auth + 1 frontend + 1 user + ~7 others)
- **Total Blade templates:** 263 (70 admin + 21 email + ~172 public/frontend)
- **Total CSS files:** 48+ (page-specific + component partials)
- **Total JS entry points:** 15
- **Total routes:** 28 admin + 9 API + web routes across 14 files
- **Total mail classes:** 21
- **Total notification classes:** 12
- **Total event classes:** 10
- **Total listener classes:** 11
- **Total queue jobs:** 7
- **Total console commands:** 9
- **Total service classes:** 13+ (with subdirectories)
- **Total test files:** 46 (21 feature + 25 unit)
- **Total custom exception classes:** 11

---

## 4. Architecture & Design Patterns

### 4.1 Clean Architecture Layers

```
HTTP Layer          → Controllers (Admin, Auth, Frontend, User, API)
                    → Request classes (in Modules)
                    → Form requests

Action Layer        → Single-responsibility actions (e.g., RecordBlogViewAction, ToggleBlogLikeAction)

Service Layer       → Domain logic organized by bounded context:
                      - WalletService
                      - SettlementService
                      - SettlementStateMachine
                      - BlogService / BlogModerationService
                      - FundraiserLevelService
                      - CouponService
                      - ProductReservationService
                      - VolunteerApplicationService
                      - NotificationService / LaravelNotificationService

Risk Subsystem      → RiskEngine, RiskRuleRegistry, ScoreCalculator, VerdictResolver
                      - Rules: AmlScreenRule, KycVerifiedRule, LargePayoutAmountRule
                      - Context: RiskContext
                      - Config: RiskConfig

Reconciliation      → ReconciliationService, ReconciliationResult

Resilience          → CircuitBreaker

Gateway Layer       → GatewayInterface contract
                      → RazorpayGateway implementation
                      → PayoutResult value object

DTO Layer           → App\Dto\ (directory exists, populated for typed contracts)

Model Layer         → Eloquent Models (55 models with relationships, scopes, accessors)

Event/Listener Layer → Decoupled domain events (10 events) with corresponding listeners (11 listeners)

Job Layer           → Queueable jobs (7 jobs + 1 test job + retry policy)

Notification Layer  → 12 notification classes (email, database, etc.)

Mail Layer          → 21 Mailable classes (transactional + notification emails)

Policy Layer        → 2 policies (BlogPolicy, EventPolicy)
```

### 4.2 Key Architectural Patterns Used

| Pattern | Implementation |
|---------|---------------|
| **State Machine** | `Campaign.campaign_state` (6 states) and `CampaignSettlement.status` (11 states) |
| **Repository Pattern** | `app/Modules/Activity/Repositories/` |
| **Service Layer** | Dedicated service classes per domain |
| **Gateway Pattern** | `GatewayInterface` with `RazorpayGateway` implementation |
| **DTO Pattern** | `app/Dto/` directory, `PayoutResult` readonly DTO |
| **Action Classes** | Single-responsibility actions in `app/Actions/` |
| **Event Sourcing (Audit)** | `CampaignLog`, `SettlementStateLog`, `RiskRuleLog`, `RiskScore` |
| **Polymorphic Relationships** | `followers` table (follower/following), `Wallet` owner (morphOne) |
| **Multi-tenancy (partial)** | `user_id` and `organization_id` scoping |
| **Role-based Access** | `users.role` column with admin/fundraiser/donor roles |
| **Queue Processing** | Redis-backed queues with `queue:listen` in dev |
| **Caching** | Redis via `predis/predis` for cache locks and general caching |
| **Module System** | `app/Modules/Activity/` with full MVC structure |

### 4.3 State Machines

#### Campaign State Machine (6 states)
`pending → active → paused → completed`
`pending → rejected → (resubmit → pending)`
`active → expired`
`pending → cancelled`
`paused → (resume → active) | (expire → expired)`

#### Settlement State Machine (11 states)
```
requested → risk_evaluation → auto_approved → processing → paid
                                 → manual_review → approved → processing → paid
request → risk_evaluation → rejected
request → cancelled
processing → failed → retry_pending → processing
processing → cancelled
failed → rejected
```
Every transition is audited via `SettlementStateLog`.

---

## 5. Feature Modules

### 5.1 Campaign Management
- Create campaigns with title, description, goal amount, cover image, video URL, location, dates
- 6-state lifecycle: Pending → Active → Paused → Completed / Expired / Rejected
- Fundraiser level requirements (campaigns can require a minimum fundraiser level)
- Level override for admin-privileged campaigns
- KYC pre-check before activation
- Campaign logging (`CampaignLog`) for all state changes
- Progress calculation (percentage of goal reached)
- Money vs. product donation breakdown
- Follower/follow system (polymorphic)
- Featured and urgent campaign flags
- Celebrity endorsements (many-to-many with pivot data)
- Campaign products with reservations
- Campaign updates (author updates for backers)

### 5.2 Donation & Payment Processing
- Two donation types: `money` and `product`
- Razorpay payment gateway integration
- Payment verification endpoint (`POST /api/payment/verify`)
- Wallet system with reserved balance (7-day hold for maturation)
- Wallet transactions with balance tracking
- Auto-release of matured reserves via `ReleaseWalletReserves` command
- Refund processing with reserved balance debit
- Donation receipt and refund email templates
- `DonationItem` model for line-item detail (product donations)

### 5.3 Payment Gateway
- `GatewayInterface` contract defining: `initiatePayout()`, `getPayoutStatus()`, `validateWebhook()`, `parseWebhook()`
- `RazorpayGateway` implementing the contract
- `PayoutResult` immutable value object (success/failure with metadata)
- Dedicated exception hierarchy for gateway errors:
  - `DuplicateRequestException`
  - `GatewayException`
  - `TimeoutException`
  - `TemporaryFailureException`
  - `PermanentFailureException`
  - `InvalidSignatureException`

### 5.4 Risk Engine
- Configuration-driven risk rules (`RiskConfig` with `aml_version`, thresholds)
- Three active risk rule implementations:
  - `AmlScreenRule` — Anti-Money Laundering screening
  - `KycVerifiedRule` — KYC status verification
  - `LargePayoutAmountRule` — Large amount threshold check
- `ScoreCalculator` aggregates rule results into a numeric score
- `VerdictResolver` maps scores to verdicts (auto_approved / manual_review / rejected)
- Compliance rules (AML) trigger automatic manual review
- Full audit trail: `RiskScore` per evaluation, `RiskRuleLog` per rule result
- Real-time denormalization on `CampaignSettlement` (risk_score, risk_verdict, risk_version, evaluated_at)
- `RiskServiceProvider` for Laravel service container binding

### 5.5 Settlement & Reconciliation
- Multi-stage settlement workflow: requested → risk evaluation → approval → processing → paid/failed
- `SettlementStateMachine` — centralized, transactional, audited state transitions
- `SettlementService` for orchestrating settlement business logic
- `ReconciliationService` with `ReconciliationResult` for financial reconciliation
- `ProcessSettlementJob`, `ProcessSettlementPayout`, `RetrySettlementJob` for async processing
- `RetryPolicy` for job retry logic
- Settlement status change notifications (11 notification types)
- Settlement events (10 event types) with corresponding listeners

### 5.6 Wallet & Financial Ledger
- MorphOne wallet (can own any model type)
- Reserved balance (7-day hold) vs. available balance
- Double-entry style transaction recording
- Cache locks for concurrent access prevention
- `lockForUpdate()` for row-level database locking
- `WalletTransaction` with source, reference type/id, balance_after tracking
- Sources: donation, refund, adjustment

### 5.7 User & Authentication
- Multi-role user system (`users.role`)
- Laravel Breeze for authentication scaffolding
- Google OAuth via Laravel Socialite (`GoogleController`)
- Phone-based OTP authentication:
  - OTP with hash (`otp_hash`), expiry (`otp_expires_at`), attempt counter (`otp_attempts`)
  - Phone verification status tracking (`phone_verified_at`)
  - `PhoneVerification` model
- Email verification (implements `MustVerifyEmail`)
- Password reset flow
- KYC verification workflow (`KycVerification` model with `STATUS_APPROVED` constant)
- Last login tracking (`last_login_at`)
- Avatar and cover image (with Cloudinary integration)
- Fundraiser level assignment (Starter → upgraded tiers, max goal amounts)

### 5.8 Organization & Partnership
- Organization registration with KYC/application workflow
- Partnership management with organization association
- Organization application approval/rejection flow
- Payout accounts (verified, masked account numbers, IFSC codes)
- Organization dashboard and management

### 5.9 Blog System
- Public blog listing and individual post pages
- Blog comments with `BlogComment` model
- Blog likes with `BlogLike` model
- Blog reports with `BlogReport` model
- Blog view tracking (`RecordBlogViewAction`, `BlogView` model)
- Blog moderation via `BlogModerationService`
- Blog service (`BlogService`) for business logic
- Admin blog controller with full CRUD (20,191 bytes)
- Blog status change emails (`BlogStatusMail`)
- Blog creation notification emails (`BlogCreatedMail`)
- SEO-friendly slugs

### 5.10 Events Management
- Event creation with date/time, location, capacity
- Event registration system (`EventRegistration` model)
- Event published notification emails (`EventPublishedMail`)
- Event registration confirmation emails (`EventRegistrationMail`)
- Admin event CRUD with full management

### 5.11 Volunteer Management
- Volunteer application workflow (`VolunteerApplication`)
- Volunteer assignments (`VolunteerAssignment`)
- Volunteer dashboard (`VolunteerDashboardController`)
- Volunteer admin management (`VolunteerAdminController`)
- Volunteer city data endpoint

### 5.12 Job Board
- Job posting system (`JobPost` model)
- Job applications (`JobPostApplication`)
- Job application status emails
- Admin job post management

### 5.13 Gift Cards & Coupons
- Gift card creation and distribution
- Coupon system with code generation, discount types, and expiration
- Coupon redemption tracking (`CouponRedemption`)
- User-specific single-use coupons
- Coupon validation and redemption workflow

### 5.14 Contact & Communication
- Contact form with `Contact` and `ContactMessage` models
- Contact messaging system for admin
- Chatbot integration (`ChatbotController`)

### 5.15 Newsletter & Subscriber
- Subscriber management (`Subscriber` model)
- Welcome email for new subscribers (`NewsletterWelcome`)
- Newsletter unsubscribe handling (`newsletter-unsubscribed.css`)

### 5.16 KYC & Compliance
- KYC verification with status tracking (`KycVerification`)
- KYC reminders via scheduled command (`SendKycReminders`)
- KYC request and submission notification emails
- Campaign-level KYC pre-checks before activation
- Multiple KYC-related controllers (`KycController`, `KycUploadController`, `CampaignKycController`)

### 5.17 Fundraiser Level System
- Tiered fundraiser levels (Starter → upgraded)
- Level history tracking (`FundraiserLevelHistory`)
- User-fundraiser level pivot (`UserFundraiserLevel`)
- Level-based campaign goal limits
- Upgrade request workflow
- Admin management of fundraiser levels

### 5.18 Admin Panel (28 Route Sections)
| Section | Description |
|---------|-------------|
| Dashboard | Analytics overview with chart data |
| Organizations | Organization management and approval |
| Campaigns | Full campaign CRUD with state management |
| Categories | Category and subcategory management |
| Blogs | Blog CRUD and moderation |
| Events | Event management |
| Partnerships | Partnership management |
| Messages | Contact/inbox messaging system |
| Applications | Organization application review |
| Job Posts | Job posting management |
| Gift Cards | Gift card management |
| Wallets | Wallet balance and transaction oversight |
| Settlements | Settlement processing and monitoring |
| Payout Accounts | Payout account verification and management |
| Chatbot | Chatbot configuration |
| Profile | Admin profile management |
| Volunteers | Volunteer management |
| Coupons | Coupon creation and management |
| FAQs | FAQ CRUD |
| Legal Pages | Legal page management |
| Subscribers | Subscriber list management |
| Fundraiser Levels | Tier and level configuration |
| Success Stories | Success story management |
| Donations | Donation oversight and processing |
| Users | User management (stub present) |
| Reports | Analytics reports (stub present) |
| Settings | System settings (stub present) |
| Roles | Role management (stub present) |

### 5.19 API Layer
| Endpoint | Description |
|----------|-------------|
| `GET/POST /api/auth` | Authentication endpoints |
| `GET/POST /api/campaigns` | Campaign listing and creation |
| `GET/POST /api/donations` | Donation processing |
| `GET/POST /api/events` | Event listing and registration |
| `GET/POST /api/users` | User profile and management |
| `GET/POST /api/notifications` | Notification management |
| `GET /api/payments` | Payment processing endpoints |
| `GET /api/states` | Indian states lookup data |
| `GET /api/cities` | Cities lookup data by state |

### 5.20 Frontend Assets
- **15 JavaScript entry points:** about, admin, auth, campaigns, campaigns-show, chatbot, contact, footer, home, navbar, user, volunteer-city, and more
- **48+ CSS files:** Page-specific CSS for every major page, plus component partials (`_components.css`, `_core.css`, `_dashboard.css`, `_animations.css`)
- **263 Blade templates:** Server-side rendered views across 25+ directories
- **Tailwind CSS** with custom configuration (`tailwind.config.js`)
- **Vite** build pipeline (`vite.config.js`)
- **PostCSS** configuration (`postcss.config.js`)

### 5.21 Console Commands (Artisan)
| Command | Purpose |
|---------|---------|
| `ConvertImagesToWebp` | Batch image format conversion |
| `ExpireCampaigns` | Auto-expire ended campaigns |
| `FixWalletCredits` | Wallet credit reconciliation/fix |
| `GenerateUUIDs` | Batch UUID generation |
| `MigrateBlogMetrics` | Blog metrics migration |
| `PruneExpiredReservations` | Cleanup expired product reservations |
| `ReleaseWalletReserves` | Release matured wallet holds |
| `SendKycReminders` | Automated KYC reminder emails |

### 5.22 Email Templates (21 Mailables)
Blog and content: BlogCreated, BlogStatus, CampaignCreated, CampaignProductStatus, CampaignStatus
Financial: DonationReceipt, DonationRefund
Events: EventPublished, EventRegistration
Jobs: JobPostApplicationReceived, JobPostApplicationStatus
KYC: KycReminder
Newsletters: NewsletterWelcome
Organizations: OrganizationApplicationStatus, OrganizationApplicationSubmitted
Partnerships: PartnershipStatusUpdated, PartnershipSubmitted
Volunteers: VolunteerApplicationReceived, VolunteerApplicationStatusChanged
Auth: WelcomeMail, WelcomeGoogleMail

---

## 6. Database Schema (Inferred from 225 Migrations)

### Core Entities
- **users** — Authentication, roles, KYC, wallet owner, fundraiser level
- **campaigns** — Fundraising campaigns with state machine, progress tracking
- **campaigns_logs** — Audit log for campaign state changes
- **campaign_products** — Products associated with campaigns
- **campaign_product_requests** — Product request submissions
- **campaign_settlements** — Settlement processing with state machine
- **campaign_settlement_state_logs** — Settlement audit trail
- **campaign_updates** — Author updates for backers
- **categories** — Content categorization
- **category_products** — Category-product mapping
- **celebrities** — Celebrity endorsements
- **celebrity_campaign** — Pivot table with role/message/pivot data
- **contacts** — Contact form submissions
- **contact_messages** — Message threading
- **coupons** — Coupon code generation
- **coupon_redemptions** — Coupon usage tracking
- **donations** — Money and product donations
- **donation_items** — Line-item detail for product donations
- **events** — Event management with dates, locations, capacity
- **event_registrations** — Event attendance tracking
- **faqs** — FAQ management
- **fundraiser_levels** — Tiered level definitions
- **fundraiser_level_history** — Level change history
- **gift_cards** — Gift card system
- **job_posts** — Job listing management
- **job_post_applications** — Application tracking
- **kyc_verifications** — KYC document and status tracking
- **legal_pages** — Legal page content
- **organizations** — Organization accounts
- **organization_applications** — Organization approval workflow
- **partnerships** — Partnership management
- **payout_accounts** — Bank account details (masked) with IFSC
- **payout_attempts** — Payout attempt tracking
- **phone_verifications** — OTP and phone verification
- **product_reservations** — Product reservation system
- **recurring_donations** — Subscription-like recurring donations
- **refunds** — Refund processing
- **risk_configs** — Risk engine configuration
- **risk_rules** — Individual risk rule definitions
- **risk_rule_logs** — Risk evaluation audit trail
- **risk_scores** — Risk evaluation results
- **settlement_items** — Individual settlement line items
- **settlement_state_logs** — Settlement audit trail
- **subscribers** — Newsletter subscribers
- **tags** — Tagging system
- **volunteers** — Volunteer profiles
- **volunteer_applications** — Volunteer application workflow
- **volunteer_assignments** — Volunteer task assignments
- **wallets** — User/owner wallets with balance + reserved_balance
- **wallet_transactions** — Double-entry ledger

### Relationship Tables
- **followers** — Polymorphic follower/following system
- **user_fundraiser_levels** — Pivot for user → fundraiser level

### Key Columns in Core Tables
- `campaigns`: goal_amount, raised_amount, campaign_state, required_level_id, level_override_by, kyc_reminded_at
- `donations`: payment_status, donation_type, payment_method, is_refunded, paid_at, released_at, total_amount
- `wallets`: balance, reserved_balance, currency
- `users`: role, otp_hash, otp_expires_at, otp_attempts, phone_verified_at, last_login_at
- `campaign_settlements`: status, risk_score, risk_verdict, risk_version, gateway_status, correlation_id, trace_id

---

## 7. Security & Error Handling

### 7.1 Custom Exception Hierarchy
| Exception | Purpose |
|-----------|---------|
| `GatewayException` | Payment gateway errors |
| `DuplicateRequestException` | Idempotency enforcement |
| `DuplicateReservationException` | Reservation conflict prevention |
| `InvalidSignatureException` | Webhook signature validation failure |
| `TimeoutException` | Gateway timeout handling |
| `TemporaryFailureException` | Temporary/transient gateway failures |
| `PermanentFailureException` | Permanent gateway failures |
| `InsufficientWalletBalanceException` | Wallet debit validation |
| `InsufficientStockException` | Product reservation stock check |
| `InvalidSettlementTransitionException` | State machine guard |

### 7.2 Security Measures
- OTP hashing with expiry and attempt limiting
- Phone verification with separate verification status tracking
- Password hashing (`password` → `hashed` cast)
- Webhook signature validation (HMAC-SHA256)
- `hash_equals()` for timing-safe comparison
- Request idempotency via `DuplicateRequestException`
- Wallet balance checks before debit operations
- Row-level locking (`lockForUpdate()`) for financial operations
- Cache locks for concurrent wallet release operations
- KYC pre-checks before campaign activation
- AML screening in risk engine

### 7.3 Error Handling
- Custom `Handler.php` (2,681 bytes) for global exception handling
- Structured error responses via dedicated exception classes
- Gateway error classification (temporary vs. permanent)

---

## 8. Frontend Architecture

### 8.1 Asset Pipeline
```
resources/
  css/
    _core.css              — Base styles, CSS custom properties
    _components.css         — Reusable component classes
    _components_continued.css — Extended component styles
    _dashboard.css          — Dashboard-specific styles
    _dashboard_activity.css  — Activity feed styles
    _dashboard_events.css    — Event calendar styles
    _dashboard_onboarding.css— Onboarding flow styles
    _wallet.css              — Wallet UI styles
    _animations.css          — CSS animation definitions
    page-specific.css files — 35+ individual page stylesheets
  js/
    app.js                   — Application entry point
    admin.js                 — Admin panel interactivity
    home.js                  — Homepage (20.5 KB)
    campaigns.js             — Campaign listing (13.1 KB)
    campaigns-show.js        — Campaign detail page
    navbar.js                — Navigation component (16.6 KB)
    footer.js                — Footer component
    auth.js                  — Authentication flows
    chatbot.js               — Chatbot interaction (18.7 KB)
    user.js                  — User dashboard
    volunteer-city.js        — Volunteer city selector
    about.js                 — About page (12.8 KB)
    contact.js               — Contact form
  views/
    263 Blade templates      — Server-side rendered HTML
```

### 8.2 Key CSS Architecture
- **Separation of concerns:** Partials prefixed with `_` for components
- **Page-specific stylesheets** for every major page
- **Dashboard module split** into dedicated sub-files (activity, events, onboarding)
- **Wallet UI** has its own dedicated stylesheet
- **Animations** centralized in `_animations.css`
- **Core primitives** in `_core.css` (CSS custom properties, base styles)
- **Component library** in `_components.css` and `_components_continued.css`

---

## 9. Testing Infrastructure

### 9.1 Test Structure
- **Feature Tests:** 21 files — HTTP integration tests, API endpoint tests
- **Unit Tests:** 25 files — Model tests, service tests, isolated logic tests
- **Base TestCase:** `tests/TestCase.php`

### 9.2 Testing Tools
- PHPUnit (v11.5.3) through Laravel's testing helpers
- `assertGuest()`, `assertAuthenticated()`, `assertSee()` pattern
- `withoutMiddleware()` for testing bypass
- Database transactions for test isolation (`RefreshDatabase` trait implied)

---

## 10. Developer Experience & Tooling

| Tool | Purpose |
|------|---------|
| Laravel Pint | Code style enforcement |
| Laravel Telescope | Debug assistant for requests, queries, jobs |
| Laravel Debugbar | Development debug toolbar + query detection |
| Laravel Pail | Streamlined log watching (replaces `php artisan tail`) |
| Laravel Sail | Docker development environment |
| Laravel Breeze | Authentication scaffolding |
| Faker | Test data generation |
| Mockery | Mocking library for tests |
| Nunomaduro Collision | Improved error output |
| Pest Plugin | Alternative test runner support |
| HTTP Discovery | Composer plugin for PSR discovery |

---

## 11. Summary of Developer Contributions

The developer who built this application has demonstrated:

1. **Database Design:** 225 migrations covering a complex normalized schema with polymorphic relationships, pivot tables with payload, audit logging columns, and state machine columns
2. **Backend Architecture:** Clean architecture with Actions → Services → Models layering; DTOs for typed contracts; Gateway pattern for external integrations
3. **Payment Systems:** Full Razorpay integration with payout flow, webhook validation, gateway abstraction, and retry mechanism
4. **Financial Systems:** Wallet with reserved balance/hold/release ledger; settlement state machine with 11 states and full audit trail; reconciliation service
5. **Risk & Compliance:** Pluggable risk engine with configurable rules, score calculation, verdict resolution, and AML screening
6. **Frontend Development:** 263 Blade templates, 48+ CSS files, 15 JS entry points, Tailwind CSS, Vite build pipeline
7. **Communication:** 21 mail classes, 12 notification classes, 10 events, 11 listeners — full notification pipeline
8. **DevOps Readiness:** Environment configuration, Docker/Sail, health monitoring, queue processing, scheduled commands
9. **Testing:** 46 test files (21 feature + 25 unit) covering the application
10. **Admin Panel:** 28 route sections with full CRUD for all major entities, organized into a complete admin dashboard

This represents **independent, end-to-end delivery** of a production-grade web application from database schema through to frontend and deployment configuration.
