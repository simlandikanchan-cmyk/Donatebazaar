# DonateBazaar

> Enterprise-grade crowdfunding and fundraising platform built with Laravel 12

[![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![MySQL 8](https://img.shields.io/badge/MySQL-8-4479A1?style=flat&logo=mysql)](https://mysql.com)
[![Redis 7](https://img.shields.io/badge/Redis-7-DC382D?style=flat&logo=redis)](https://redis.io)

---

## Overview

DonateBazaar is a production-ready crowdfunding platform that enables individuals and organizations to create campaigns, collect donations, and manage fundraisers. The platform features a complete financial architecture with wallet management, settlement processing, KYC verification, and Razorpay payment integration.

### Key Features

- **Campaign Management** — Create, manage, and track fundraising campaigns
- **Payment Processing** — Razorpay integration with webhook verification
- **Wallet System** — Double-entry accounting with transaction ledger
- **Settlement Engine** — Automated payout workflow with state machine
- **KYC Verification** — Document verification with encrypted storage
- **Admin Dashboard** — 23 management modules with full CRUD
- **Multi-Auth System** — User, Admin, Google OAuth, and OTP authentication
- **Role-Based Access Control** — Spatie Permissions for granular access control
- **Notification System** — Preference-driven multi-channel notifications
- **Risk Engine** — Configurable fraud detection rules
- **Docker Deployment** — Production-ready container orchestration
- **RESTful API** — API resources for campaigns, donations, wallet, settlements

---

## Table of Contents

- [Architecture](#architecture)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database](#database)
- [Queue Workers](#queue-workers)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [Deployment](#deployment)
- [Modules](#modules)
- [Security](#security)
- [License](#license)

---

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        DonateBazaar                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐              │
│  │   Public     │  │    User     │  │    Admin    │              │
│  │   Portal     │  │  Dashboard  │  │  Dashboard  │              │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘              │
│         │                │                │                      │
│  ┌──────┴────────────────┴────────────────┴──────┐              │
│  │                  Laravel 12                     │              │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐          │              │
│  │  │Controllers│ │Services │ │  Jobs   │          │              │
│  │  └─────────┘ └─────────┘ └─────────┘          │              │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐          │              │
│  │  │  Models  │ │Policies │ │Events/  │          │              │
│  │  │         │ │         │ │Listeners│          │              │
│  │  └─────────┘ └─────────┘ └─────────┘          │              │
│  └───────────────────────┬────────────────────────┘              │
│                          │                                       │
│  ┌───────────────────────┴────────────────────────┐              │
│  │                 Infrastructure                 │              │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐          │              │
│  │  │  MySQL   │ │  Redis  │ │ Razorpay│          │              │
│  │  └─────────┘ └─────────┘ └─────────┘          │              │
│  └───────────────────────────────────────────────┘              │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Technology Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel 12, PHP 8.2+ |
| **Database** | MySQL 8.0 |
| **Cache/Queue** | Redis 7 |
| **Frontend** | Blade, Tailwind CSS v4, Vanilla JS |
| **Payments** | Razorpay |
| **Storage** | Local / Cloudinary (configured) |
| **Testing** | PHPUnit, Playwright |
| **DevOps** | Docker, Supervisor |

### Project Structure

```
app/
├── Console/Commands/     # 11 Artisan commands
├── Events/               # 10 domain events
├── Exceptions/           # 12 custom exceptions
├── Gateways/             # Payment gateway abstraction
├── Http/
│   ├── Controllers/      # 78 controllers (Admin, API, Auth, User)
│   ├── Middleware/       # 6 custom middleware
│   └── Requests/         # 26 form request validators
├── Jobs/                 # 5 queue jobs
├── Listeners/            # 11 event listeners
├── Models/               # 56 Eloquent models
├── Notifications/        # 16 notification classes
├── Policies/             # 3 authorization policies
├── Services/             # 12 service classes
│   └── Payment/          # 5 payment-specific services
└── Traits/               # 1 reusable trait

routes/
├── web/                  # 16 user-facing route files
├── admin/                # 28 admin route files
└── api/                  # 10 API route files

resources/
├── views/                # 273 Blade templates
│   ├── admin/            # 77 admin views
│   ├── user/             # User dashboard views
│   ├── public/           # Public portal views
│   ├── layouts/          # 8 layout templates
│   ├── components/       # 21 reusable components
│   └── emails/           # 22 email templates
├── css/                  # 232 CSS files
└── js/                   # 141 JS files

database/
├── migrations/           # 244 migrations (95 tables)
└── factories/            # 14 model factories

tests/
├── Unit/                 # 30 unit tests
├── Feature/              # 58 feature tests
└── browser/              # 2 Playwright E2E tests
```

---

## Requirements

| Component | Minimum Version |
|---|---|
| PHP | 8.2+ |
| Composer | 2.0+ |
| MySQL | 8.0+ |
| Redis | 7.0+ |
| Node.js | 18.0+ |
| NPM | 9.0+ |

### PHP Extensions

```
bcmath, ctype, curl, dom, fileinfo, gd, json, mbstring,
openssl, pdo, pdo_mysql, redis, tokenizer, xml, zip
```

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-org/donatebazaar.git
cd donatebazaar
```

### 2. Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure:

```env
APP_NAME=DonateBazaar
APP_ENV=production
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=donatebazaar
DB_USERNAME=root
DB_PASSWORD=your_password

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

RAZORPAY_KEY=rzp_test_xxxxxxxx
RAZORPAY_SECRET=xxxxxxxx
RAZORPAY_WEBHOOK_SECRET=xxxxxxxx
```

### 5. Database Setup

```bash
php artisan migrate --force
```

### 6. Build Frontend Assets

```bash
npm run build
```

### 7. Configure Queue Workers

Copy supervisor config:

```bash
cp supervisor/queue-worker.conf /etc/supervisor/conf.d/
supervisorctl reread
supervisorctl update
supervisorctl start all
```

### 8. Configure Cron Job

Add to crontab:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### 9. Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 10. Verify Installation

```bash
php artisan serve
```

Visit `http://localhost:8000` — you should see the homepage.

---

## Configuration

### Environment Variables

| Variable | Description | Required |
|---|---|---|
| `APP_KEY` | Laravel encryption key | Yes |
| `DB_*` | Database connection | Yes |
| `REDIS_*` | Redis connection | Yes |
| `RAZORPAY_KEY` | Razorpay API key | Yes |
| `RAZORPAY_SECRET` | Razorpay API secret | Yes |
| `RAZORPAY_WEBHOOK_SECRET` | Webhook signature secret | Yes |
| `GOOGLE_CLIENT_ID` | Google OAuth ID | No |
| `GOOGLE_CLIENT_SECRET` | Google OAuth secret | No |
| `CLOUDINARY_URL` | Cloudinary URL | No |

### Redis Configuration

The application uses Redis for:
- **Session storage** — `SESSION_DRIVER=redis`
- **Cache** — `CACHE_DRIVER=redis`
- **Queue** — `QUEUE_CONNECTION=redis`

---

## Database

### Schema Overview

- **95 database tables** across all modules
- **244 migration files** documenting schema evolution
- Financial tables with strict integrity constraints
- Encrypted fields for sensitive KYC/bank data
- Soft deletes for audit trails

### Key Tables

| Category | Tables |
|---|---|
| **Users** | users, user_fundraiser_levels, phone_verifications |
| **Campaigns** | campaigns, campaign_products, campaign_updates, campaign_settlements, campaign_logs |
| **Donations** | donations, donation_items, donation_payments, refunds |
| **Financial** | wallets, wallet_transactions, settlements, settlement_items, settlement_state_logs, payout_accounts, payout_attempts |
| **KYC** | kyc_verifications, organization_applications |
| **Content** | blogs, blog_comments, blog_likes, blog_reports, faqs, legal_pages |
| **Events** | events, event_registrations |
| **Other** | categories, coupons, gift_cards, jobs, volunteers, partnerships |

---

## Queue Workers

The application processes background jobs through three queues:

| Queue | Processes | Purpose |
|---|---|---|
| `emails` | 1 | Transactional email delivery |
| `default` | 2 | General background processing |
| `notifications` | 1 | Push/database notifications |

### Supervisor Configuration

Config file: `supervisor/queue-worker.conf`

```ini
[program:fundraise-queue-emails]
command=php artisan queue:work redis --queue=emails --sleep=3 --tries=3
numprocs=1

[program:fundraise-queue-default]
command=php artisan queue:work redis --queue=default --sleep=3 --tries=3
numprocs=2

[program:fundraise-queue-notifications]
command=php artisan queue:work redis --queue=notifications --sleep=3 --tries=3
numprocs=1
```

Restart workers after deployment:

```bash
php artisan queue:restart
```

---

## API Endpoints

### Health Check

```
GET /api/v1/health
```

Returns system status (cache, database, queue, redis).

### Payments

```
POST /api/v1/payment/verify
```

Verify Razorpay payment.

### Locations

```
GET /api/v1/states/{country}
GET /api/v1/cities/{state}
```

Indian states and cities lookup.

### Notifications (Authenticated)

```
GET    /api/v1/notification-types
GET    /api/v1/notification-preferences
POST   /api/v1/notification-preferences
PUT    /api/v1/notification-preferences/{type}/{channel}
DELETE /api/v1/notification-preferences/{type}/{channel}
POST   /api/v1/notification-preferences/reset-all
```

---

## Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

### Run with Coverage

```bash
php artisan test --coverage
```

### Browser Tests (Playwright)

```bash
npx playwright install
npx playwright test
```

### Test Structure

| Type | Count | Location |
|---|---|---|
| Unit Tests | 30 | `tests/Unit/` |
| Feature Tests | 58 | `tests/Feature/` |
| Browser Tests | 2 | `tests/browser/` |

---

## Deployment

### Docker Deployment

```bash
docker-compose up -d
```

Services:
- **nginx** — Web server (ports 80/443)
- **php** — PHP 8.2-FPM
- **mysql** — MySQL 8.0 (port 3307)
- **redis** — Redis 7 (port 6380)
- **queue-worker** — Background job processor
- **scheduler** — Cron-like task scheduler

### Production Checklist

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` generated
- [ ] Database migrated
- [ ] Redis configured
- [ ] Queue workers running
- [ ] Cron job configured
- [ ] SSL certificate installed
- [ ] `config:cache` and `route:cache` executed
- [ ] Storage permissions set

---

## Modules

### Admin Dashboard (23 Modules)

| Module | Description |
|---|---|
| Dashboard | Analytics overview, activity feed |
| Campaigns | CRUD, approval workflow, product management |
| Categories | Category CRUD, category products |
| Donations | View, export, refund processing |
| Settlements | Payout approval, retry, state management |
| Wallets | Balance view, adjustment, transaction history |
| KYC | Verification review, approve/reject |
| Users | Organization management, profiles |
| Blogs | Content moderation, carousel, analytics |
| Events | CRUD, registration management |
| Volunteers | Applications, assignments |
| Jobs | Job postings, applications |
| Gift Cards | Code generation, tracking |
| Coupons | Discount code management |
| FAQs | Frequently asked questions CRUD |
| Legal | Terms, privacy policy management |
| Partnerships | B2B partnership inquiries |
| Messages | Contact form inbox |
| Subscribers | Newsletter subscriber list |
| Success Stories | Curated success content |
| Payout Accounts | Bank account management |
| Fundraiser Levels | Level configuration |
| Applications | Organization applications |

### User Dashboard (11 Modules)

| Module | Description |
|---|---|
| Dashboard | Personal analytics, activity |
| Campaigns | Create, edit, manage campaigns |
| Donations | History, receipts, recurring |
| Wallet | Balance, transactions, gift cards |
| Profile | Settings, fundraiser level |
| KYC | Document upload, verification status |
| Notifications | Preferences, history |
| Saved Campaigns | Bookmarked campaigns |
| Blogs | Create, edit personal blogs |
| Events | Create and manage events |
| Recurring | Recurring donation management |

### Public Portal (20+ Pages)

- Homepage with campaign showcase
- Campaign listing and detail pages
- Donation flow
- Authentication (login, register, OAuth, OTP)
- About, Contact, FAQ
- Blog and Events
- Gift Cards
- Volunteer portal
- Job postings
- Partnership inquiry
- Legal pages

---

## Security

### Implemented Measures

- **Authentication** — Laravel Breeze with Google OAuth + OTP
- **Authorization** — Middleware-based role checks
- **CSRF Protection** — Laravel's built-in token verification
- **Rate Limiting** — 3 named limiters (webhooks, financial, gift-card)
- **Encryption** — AES-256-CBC with encrypted Eloquent casts for sensitive fields
- **Security Headers** — CSP with nonce, HSTS, X-Frame-Options DENY
- **Sensitive Data Redaction** — Custom Monolog processor
- **Signed URLs** — Temporary signed routes for receipt downloads
- **Input Validation** — 26 Form Request classes

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Support

For issues and feature requests, please use the [GitHub issue tracker](https://github.com/your-org/donatebazaar/issues).
