# Changelog

All notable changes to DonateBazaar will be documented in this file.

---

## [Unreleased]

### Added
- Product reservation system with expiry handling
- Enhanced risk engine with configurable rules
- Improved settlement retry logic with exponential backoff

---

## [1.0.0] - 2026-08-30

### Added
- **Campaign System**
  - Campaign creation, editing, and management
  - Campaign status workflow (draft, pending, active, successful, failed, cancelled)
  - Campaign products for product-based fundraising
  - Campaign updates and media uploads
  - Campaign analytics dashboard

- **Donation System**
  - Razorpay payment integration
  - Payment verification with signature validation
  - Webhook handling for async payment confirmation
  - Donation receipt generation (PDF)
  - Recurring donation support
  - Refund processing

- **Wallet System**
  - Double-entry accounting ledger
  - Wallet balance management
  - Reserved balance for settlements
  - Transaction history

- **Settlement System**
  - Settlement request workflow
  - State machine (pending, approved, processing, paid, failed, rejected)
  - Auto-approval for eligible settlements
  - Retry mechanism for failed payouts
  - Settlement reconciliation

- **KYC System**
  - Document upload and verification
  - Admin review workflow
  - Encrypted storage for sensitive data
  - Organization application processing

- **Admin Dashboard**
  - 23 management modules
  - User management
  - Campaign moderation
  - Financial monitoring
  - Settlement management
  - KYC verification management
  - Content management (blogs, FAQs, legal pages)

- **User Dashboard**
  - Personal analytics
  - Campaign management
  - Donation history
  - Wallet overview
  - Profile management
  - Notification preferences

- **Public Portal**
  - Homepage with campaign showcase
  - Campaign listing and detail pages
  - Donation flow
  - Authentication (email, Google OAuth, OTP)
  - Blog and Events
  - Gift Cards
  - Volunteer portal

- **Payment Integration**
  - Razorpay order creation
  - Payment verification
  - Webhook signature verification
  - Refund processing
  - Idempotency handling

- **Notification System**
  - 16 notification types
  - Multi-channel delivery (mail, database)
  - User preference management
  - Event-driven notifications

- **Security**
  - CSP with nonce
  - Security headers (HSTS, X-Frame-Options)
  - Rate limiting (financial, gift-card, webhooks)
  - Encrypted Eloquent casts for sensitive fields
  - Sensitive data redaction in logs

- **Infrastructure**
  - Docker containerization
  - Redis for cache, sessions, and queues
  - Queue workers with supervisor
  - Scheduled tasks for automation
  - Health check endpoint

- **Testing**
  - 30 unit tests
  - 58 feature tests
  - 2 Playwright E2E tests
  - Form validation tests
  - Security hardening tests

---

## [0.5.0] - 2026-06-15

### Added
- Initial campaign and donation functionality
- Basic admin dashboard
- User authentication with Laravel Breeze
- Razorpay payment integration
- Wallet system foundation

---

## [0.1.0] - 2026-04-01

### Added
- Project initialization
- Database schema design
- Core models and migrations
- Basic routing structure

---

## Versioning

This project follows [Semantic Versioning](https://semver.org/):

- **MAJOR** — Incompatible API changes
- **MINOR** — Added functionality (backward compatible)
- **PATCH** — Bug fixes (backward compatible)
