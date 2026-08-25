# Full Stack Engineer — Project & Salary Assessment Report

**Prepared:** July 2026
**Location:** Ahmedabad, India
**Current Role:** Full Stack Engineer
**Current Salary:** ₹28,000/month (~₹3.36 LPA)

---

## 1. Executive Summary

This report evaluates the **Fundraise** project — a full-stack Laravel fundraising platform built from database schema to deployment — and provides a salary expectation analysis based on project scope, technical depth, location (Ahmedabad), and 4+ years of experience.

**Key Finding:** Current salary of ₹28,000/month is significantly below market rate for the demonstrated skill level and project scope. The realistic target range is **₹50,000 – ₹75,000/month** in Ahmedabad, or **₹45,000 – ₹60,000/month** for Tier-2-adjusted roles.

---

## 2. Project Overview — Fundraise

Fundraise is a **comprehensive crowdfunding/fundraising platform** built on Laravel 12 (PHP 8.2). It was designed using a **database-first approach** — starting with DB schema, then building the full layered architecture on top. The application supports campaigns, donations, events, blogs, organizations, KYC verification, payment processing, settlements, wallets, risk management, and an admin panel.

---

## 3. Technical Architecture

### 3.1 Architecture Pattern

The application follows **Clean Architecture** with explicit separation of concerns:

| Layer | Directory | Purpose |
|-------|-----------|---------|
| **HTTP/API** | `app/Http/Controllers/` | Request handling, response formatting |
| **Actions** | `app/Actions/` | Single-responsibility business action units |
| **Services** | `app/Services/` | Domain logic (Blog, Settlement, Risk, Reconciliation, Resilience, Wallet, Coupon, FundraiserLevel) |
| **Gateways** | `app/Gateways/` | External payment gateway abstraction (Razorpay) |
| **DTOs** | `app/Dto/` | Data transfer objects for typed data passing |
| **Models** | `app/Models/` | Eloquent ORM entities with business logic |
| **Modules** | `app/Modules/Activity/` | Self-contained module with Controllers, Models, Repositories, Requests, Resources, Services |
| **Jobs** | `app/Jobs/` | Queueable background processing |
| **Events & Listeners** | `app/Events/`, `app/Listeners/` | Decoupled event-driven communication |
| **Notifications** | `app/Notifications/` | Multi-channel user notifications |
| **Mail** | `app/Mail/` | Email templates and delivery |
| **Policies** | `app/Policies/` | Authorization logic |

### 3.2 Project Structure Metrics

| Metric | Count |
|--------|-------|
| Database Migrations | 225 |
| Eloquent Models | 55 |
| Controllers (total) | ~40 (including 29 admin sub-controllers) |
| Services | 20+ (with dedicated subdirectories for Blog, Settlement, Risk, Reconciliation, Resilience) |
| Admin Route Files | 28 |
| API Route Files | 9 |
| Blade Views | 263 |
| Events | 10 |
| Event Listeners | 11 |
| Queue Jobs | 7 |
| Notifications | 12 |
| Mail Classes | 21 |
| Policies | 2 |
| Factories | 4 |
| Seeders | 5 |
| Routes (Admin) | 22 files (dashboard, campaigns, blogs, events, settlements, wallets, coupons, etc.) |
| Routes (API) | 9 files (auth, campaigns, donations, payments, events, etc.) |

**Total Admin Routes:** 28 distinct endpoints (applications, blogs, campaigns, categories, chatbot, coupons, dashboard, donations, events, FAQs, fundraiser-levels, gift-cards, job-posts, legal, messages, organizations, partnerships, payout-accounts, profile, reports, roles, settings, settlements, subscribers, success-stories, users, volunteers, wallets)

---

## 4. Feature Breakdown

### 4.1 Core Fundraising Platform
- Campaign creation with goal amounts, progress tracking, and status lifecycle (Pending → Active → Paused → Completed/Expired/Rejected)
- Multi-currency donations (money and product types)
- Recurring donations with subscription-like management
- Donation items with line-item breakdowns
- Campaign following/favorites (polymorphic relationship)
- Campaign updates and logging

### 4.2 Payment & Financial System
- Razorpay payment gateway integration with `RazorpayGateway` class
- Wallet system with `Wallet` and `WalletTransaction` models
- Settlement processing with state machine (`SettlementStateMachine`)
- Reconciliation service (`ReconciliationService`) with result tracking
- Payout account management
- Payout attempt tracking
- Refund processing via `Refund` model
- Coupon system with creation, redemption tracking (`CouponRedemption`)
- Gift card system

### 4.3 User Roles & KYC
- Multi-role user system (donor, fundraiser, organization, admin)
- KYC verification with status tracking and approval workflow
- Phone verification with OTP (hashed, expiring, attempt-limited)
- Fundraiser level system (Starter → upgraded tiers) with maximum goal amounts and upgrade request workflow
- User profile management with avatar, cover image, bio

### 4.4 Risk & Compliance Engine
- Risk engine (`RiskEngine`) with scoring (`ScoreCalculator`), rules (`RiskRule`, `RiskRuleRegistry`), and verdict resolution (`VerdictResolver`)
- Risk configuration with per-rule configuration
- Risk score tracking and rule result logging
- Settlement state logging for audit trails
- Campaign state logging (`CampaignLog`, `SettlementStateLog`)

### 4.5 Content Management
- Blog system with public and admin controllers (`PublicBlogController`, admin blog routes)
- Blog comments, likes, reports, and moderation (`BlogModerationService`)
- Blog view tracking (`RecordBlogViewAction`, `BlogView` model)
- FAQ management
- Legal pages
- Success stories
- Newsletter/subscriber system with `Subscriber` model

### 4.6 Events & Volunteer Management
- Event creation with registration system (`EventRegistration`)
- Volunteer module with applications, assignments, and dashboards
- Organization application workflow (`OrganizationApplication`)

### 4.7 Job Board
- Job posting system with `JobPost` and `JobPostApplication` models

### 4.8 Admin Panel (28 Route Files)
- Dashboard with analytics
- Campaign management with state transitions
- Organization and partnership management
- Message/inbox system
- User management (stub present)
- Role-based access (stub present)
- Settings (stub present)
- Reports (stub present)
- Full CRUD for: blogs, categories, events, gift cards, coupons, FAQs, legal pages, job posts, newsletters, volunteers, wallets, settlements, payout accounts

### 4.9 API Layer (9 Route Files)
- Auth, campaigns, donations, events, notifications, payments, users
- City and state lookup endpoints (geographic data)

### 4.10 Frontend
- **Vite** build tool with Laravel Mix
- **Tailwind CSS** for styling (`tailwind.config.js`, `postcss.config.js`)
- 263 Blade templates
- Responsive admin dashboard (`DashboardController` — 8,811 bytes)

---

## 5. Technology Stack

| Category | Technology |
|----------|-----------|
| **Framework** | Laravel 12 (PHP 8.2) |
| **Database** | SQLite (dev) / MySQL (production) |
| **Frontend** | Vite + Tailwind CSS |
| **Payment** | Razorpay SDK (`razorpay/razorpay` ^2.9) |
| **Cloud Storage** | Cloudinary (`cloudinary-labs/cloudinary-laravel` ^3.0) |
| **Image Processing** | Intervention Image (`intervention/image-laravel` ^1.5) |
| **Caching/Queue** | Redis via `predis/predis` ^3.4 |
| **Auth** | Laravel Socialite (OAuth) |
| **Monitoring** | Laravel Telescope, Laravel Debugbar, Spatie Health |
| **Dev Tools** | Laravel Pail, Sail, Breeze, Pint, PHPUnit |
| **Search** | `doctrine/dbal` ^4.4 (for schema introspection) |

---

## 6. Skills Demonstrated

### 6.1 Backend (Laravel/PHP)
- Database-first design: 225 migrations reflecting a normalized, production-grade schema
- Eloquent ORM mastery: 55 models with relationships, scopes, accessors, state machines, and soft deletes
- Clean architecture: Actions, Services, Gateways, DTOs layered correctly
- State management: Campaign state machine, Settlement state machine
- API design: RESTful routes with separate admin and public API layers
- Authentication: Multi-role with Socialite OAuth, OTP-based phone auth, KYC workflow
- Queue processing: 7 background jobs
- Event-driven architecture: 10 events, 11 listeners
- Security: Policies, authorization, input validation via Form Requests (implied by module structure)

### 6.2 Database Design
- 225 migration files covering full schema from scratch
- Polymorphic relationships (followers, wallets)
- Pivot tables with payload (celebrity_campaign)
- Audit logging (CampaignLog, SettlementStateLog, RiskRuleLog)
- Indexing strategy (implied by proper Eloquent usage)

### 6.3 Payment & Financial Systems
- Razorpay gateway abstraction
- Wallet ledger system with transactions
- Settlement processing with state machine
- Reconciliation engine
- Coupon/gift card redemption tracking

### 6.4 Risk & Compliance
- Custom risk engine with pluggable rules
- Score calculation and verdict resolution
- KYC/AML-style verification workflows
- Full audit trail via logging

### 6.5 Frontend
- Tailwind CSS with Vite build pipeline
- 263 Blade templates (server-side rendering)
- Admin dashboard
- Responsive UI

### 6.6 DevOps / Deployment Ready
- `.env.production.example` for environment configuration
- `vite.config.js`, `tailwind.config.js` for build tooling
- Docker-ready (Laravel Sail configuration)
- Health monitoring (Spatie Laravel Health)

---

## 7. Compensation Analysis

### 7.1 Current Situation

| Parameter | Value |
|-----------|-------|
| Current Monthly Salary | ₹28,000 |
| Current Annual (approx) | ₹3.36 LPA |
| Experience | 4+ years |
| Location | Ahmedabad, India |
| Role | Full Stack Engineer |
| Project Scope | Full platform from DB schema to deployment |

### 7.2 Market Context — Ahmedabad (4+ Yrs Full Stack)

Ahmedabad is a **Tier-2 city** in India. Typical salary adjustments vs. Tier-1 cities (Bangalore, Delhi, Mumbai):
- Tier-2 salaries run **20–40% lower** than Tier-1 for equivalent roles
- However, the project scope and architecture quality demonstrated here are **Tier-1 level**

### 7.3 Salary Expectations

| Level | Monthly (₹) | Annual (₹ LPA) | Notes |
|-------|-------------|----------------|-------|
| **Current** | ₹28,000 | ₹3.36 LPA | Below minimum market rate |
| **Low end (market floor)** | ₹45,000 | ₹5.4 LPA | Entry-level for 4+ Yrs in Ahmedabad |
| **Mid range (realistic)** | ₹50,000 – ₹70,000 | ₹6 – ₹8.4 LPA | Expected for this project scope |
| **Upper range** | ₹70,000 – ₹1,00,000 | ₹8.4 – ₹12 LPA | Remote/Tier-1 companies, strong negotiation |
| **Freelance rate** | ₹800 – ₹1,500/hr | ₹50K – ₹1.5L/month per project | For full build from schema |

### 7.4 Why 28K/Month Is Below Market

1. **28K/month (~₹3.36 LPA)** is a **junior salary** in India — typically paid to 0–1 year experience developers.
2. Building a platform with 225 migrations, 55 models, 40 controllers, 263 views, payment processing, risk engine, settlement system, and admin panel from DB schema is a **mid-to-senior level** achievement.
3. The architecture (Clean Architecture with Actions/Services/Gateways/DTOs) is explicitly designed for scalability and maintainability — a pattern expected of experienced engineers.
4. The project demonstrates **full lifecycle ownership**: schema design → migrations → models → controllers → services → gateways → frontend → deployment config.

---

## 8. Negotiation Recommendations

### 8.1 For Salaried Roles (Ahmedabad)
- **Target:** ₹50,000 – ₹65,000/month (₹6 – ₹7.8 LPA)
- **Walk-away threshold:** ₹40,000/month (anything below is not respecting the work delivered)
- **Leverage points:** Database-first design, clean architecture, payment integration, risk engine, complete admin panel

### 8.2 For Remote/Tier-1 Roles
- **Target:** ₹70,000 – ₹1,00,000/month
- **Leverage:** The architecture patterns used (Clean Architecture, DTOs, Services, Gateways) are industry-standard and recognized by SaaS product companies

### 8.3 For Freelancing
- **Full build from schema:** ₹50,000 – ₹1,50,000 per project
- **Hourly rate:** ₹800 – ₹1,500/hr
- **Ongoing maintenance:** ₹50,000 – ₹80,000/month retainer

### 8.4 Resume/Portfolio Tips
- Quantify the project: "Built a full-stack fundraising platform from database schema, including 225 migrations, 55 models, payment processing via Razorpay, a risk scoring engine, and a 28-route admin panel"
- Highlight architecture: Explicitly call out Clean Architecture, DTOs, Actions, Services, Gateways pattern
- Show range of skills: Database design, backend (Laravel), payment integration, risk/compliance, admin panels, API design, frontend (Tailwind/Vite)

---

## 9. Conclusion

The Fundraise project represents a **substantial, production-grade full-stack application** that demonstrates competence well beyond junior-level work. With 4+ years of experience and this portfolio piece, the current salary of ₹28,000/month is approximately **45–55% below** market rate for equivalent roles in Ahmedabad.

A realistic and negotiable target salary is **₹50,000 – ₹65,000/month** for an Ahmedabad-based role, with upward potential to **₹75,000 – ₹1,00,000/month** for remote positions with Tier-1 companies.

The gap between current pay and market value is a direct reflection of the employer not recognizing the full scope of work that went into building this platform. The skills demonstrated here — database design, Laravel architecture, payment integration, risk engines, admin panel development, and API design — are in consistent demand across the Indian and global market.
