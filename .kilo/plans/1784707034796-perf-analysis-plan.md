# Performance Analysis & Optimization Plan — Laravel Fundraise App

## 1. Current Performance Config

| Setting | Value | Notes |
|---------|-------|-------|
| `APP_ENV` | `local` | OK for dev, but `APP_DEBUG=true` adds overhead |
| `APP_DEBUG` | `true` | Expensive; stack traces rendered on every error |
| `CACHE_DRIVER` | `file` | Slowest driver; Redis configured but unused |
| `SESSION_DRIVER` | `file` | File-based session I/O per request |
| `QUEUE_CONNECTION` | `sync` (line 41) | `.env` line 85 also sets `database` but first key wins; jobs run inline |
| `DEBUGBAR_ENABLED` | `false` | Good; package present but inactive |
| `TELESCOPE_ENABLED` | `true` | **Heavy** — Telescope logs every query/request |
| `config/route/view caches` | **Not built** | `bootstrap/cache` only has `services.php` + `packages.php` |
| `php.ini` opcache | **Disabled** | `;zend_extension=opcache` (line 964) |
| `php.ini` memory_limit | `512M` | Adequate |
| `php.ini` max_execution_time | `300s` | High |
| Xdebug | **Not loaded** | No `zend_extension=xdebug` entry; no xdebug DLL in use |

## 2. Laravel Debugbar

- **Present**: `barryvdh/laravel-debugbar` is in `composer.json` `require-dev`.
- **Registered**: Auto-discovered via Composer (`dont-discover` is empty).
- **Status**: Disabled in `.env` (`DEBUGBAR_ENABLED=false`). Keep disabled for perf testing.

## 3. Critical Codebase Issues

### 3.1 N+1 Query Problems

| Location | Severity | Detail |
|----------|----------|--------|
| `resources/views/public/show.blade.php:502-504` | **HIGH** | `User::where(...)->first()` inside `@foreach` over last 10 donations → up to **10 extra queries per page load** |
| `app/Http/Controllers/Admin/CampaignController.php:326-328` | **MEDIUM** | `KycVerification::where(...)->exists()` inside bulk-approve/reject loop (N campaigns × 1 query) |
| `app/Http/Controllers/DonationHistoryController.php:25` | **LOW** | `Campaign::where('user_id', $user->id)->get()` loads all user campaigns eagerly; combined count queries could be merged |

### 3.2 Queries Executed in Views (should be in controllers)

| Location | Detail |
|----------|--------|
| `resources/views/public/show.blade.php:23-24` | `$campaign->moneyRaised()` + `$productRaised()` execute DB queries in view |
| `resources/views/public/show.blade.php:30-37` | `$campaign->products()->...get()` runs a query in the view |
| `resources/views/public/show.blade.php:45` | `$campaign->updates()->...get()` runs a query in the view |

### 3.3 Missing Indexes

| Table | Column(s) | Impact |
|-------|-----------|--------|
| `campaign_products` | `approval_status`, `source`, `reserved_quantity`, `remaining_quantity` | Admin product listing filters + sort; reservation stock checks |
| `donations` | `order_id` | Payment verify + webhook lookups by Razorpay order_id |
| `product_reservations` | `donation_id`, `session_id`, `idempotency_key` | Reservation cleanup, consumption, dedup |
| `campaigns` | `slug`, `end_date`, `is_featured`, `is_urgent` | Public show, listing, featured/urgent filters |
| `coupons` | `code` | `CouponService::validate()` looks up by code |
| `wallets` | `(owner_type, owner_id)` | `WalletService::getOrCreateWallet()` scans by polymorphic owner |

### 3.4 Sync Work That Should Be Queued

| Location | Detail |
|----------|--------|
| `PaymentController.php:199-200` | `Mail::to(...)->send(new DonationReceiptMail(...))` in `sendReceiptEmail()` — called synchronously after payment verify and webhook capture |
| `PaymentController.php:1327-1328` | `Mail::to(...)->send(new DonationRefundMail(...))` in `handleRefundProcessed()` |
| `CampaignController.php:139` | `Mail::to(...)->send(new CampaignCreatedMail(...))` on campaign store |
| `Admin/CampaignController.php:340, 390` | `Mail::to(...)->send(new CampaignStatusMail(...))` in bulk approve/reject loops |

### 3.5 Other Issues

- **vite.config.js:70-85** — Unresolved Git merge conflict (`<<<<<<< HEAD` / `=======` / `>>>>>>> origin/master`). Breaks `npm run dev` / `npm run build`.
- **.env line 71** — `AWS_ACCESS_KEY_ID=CACHE_DRIVER` (likely corruption/overwrite).
- **.env line 85** — `QUEUE_CONNECTION=database` overridden by earlier `sync` on line 41.
- **.env lines 69 & 76** — Duplicate `VITE_APP_NAME`.
- **TELESCOPE_ENABLED=true** — Significant overhead for every request.

## 4. Route/Page Request Profile

| Route/Page | Key Controller → Service → Queries | Queries (#) | N+1 Risk | Missing Index Risk | Sync Work to Queue | Notes |
|------------|-----------------------------------|-------------|----------|-------------------|-------------------|-------|
| `/all-campaigns` (listing) | `CampaignController::publicCampaigns` → Cache::remember categories → Campaign::withCount(donations) | ~3-5 | Low | Search `LIKE '%...%'` on `title`/`description` | No | Categories cached 1h. No fulltext index. |
| `/campaigns/{category}/{slug}` (show) | `PublicCampaignController::show` → eager loads category, user, 10 donations, events → **BUT VIEW runs 3 extra queries + donor-loop N+1** | Controller: 4 + View: ~14+ | **YES** (donor loop) | `campaigns.slug`, `campaigns.end_date` | No | Worst offender. Extra view queries + per-donor User lookup. |
| `/campaign/create` → `/campaign/store` | `CampaignController::store` → image processing + multiple CampaignProduct creates | ~10+ | Low | — | **YES** (CampaignCreatedMail sync) | Image processing is inline but unavoidable for upload. |
| `/admin/campaign-products` | `Admin\CampaignProductController::index` → eager loads campaign, user, category, approver + counts query | ~3-4 | Low | `approval_status`, `source`, leading-wildcard LIKE | No | Leading wildcard search is slow; TODO already in code. |
| `/donate/redirect` → `/payment/{id}` | `PaymentController::redirectToPayment` → `ProductReservationService::reserve` (pessimistic locks) → `paymentPage` creates Donation + items | ~6-10 | Low | `campaign_products.reserved_quantity` | No | Stock reservation logic is solid. |
| `/payment/verify` + Razorpay webhook | `PaymentController::verify` / `handlePaymentCaptured` → DB transaction + locks + stock decrement + wallet credit | ~8-12 | Low | `donations.order_id` | **YES** (receipt email sync) | Webhook email deferred until after commit (good), but still syncsend. |

## 5. Prioritized Concrete Fixes

### P0 — Fix immediately (biggest perf wins)

1. **Eliminate N+1 in public campaign show page**
   - **File**: `app/Http/Controllers/PublicCampaignController.php:13-28`
   - **Change**: Add `'products' => fn($q) => $q->where('is_active', 1)->where('approval_status', 'approved')->with('categoryProduct')->withCount([...])`, `'updates'`, and either eager-load donations with `->with('user:id,name,email')` or pre-load users in controller and pass a `userMap` to view.
   - **File**: `resources/views/public/show.blade.php:502-504`
   - **Change**: Replace per-donation `User::where(...)->first()` with a lookup against a pre-loaded map or remove avatar lookup if not critical.

2. **Move all outgoing emails to queue**
   - **File**: `app/Http/Controllers/PaymentController.php:199-200`
   - **Change**: `Mail::to(...)->send(...)` → `Mail::to(...)->queue(...)` in `sendReceiptEmail()`.
   - **File**: `app/Http/Controllers/PaymentController.php:1327-1328`
   - **Change**: `Mail::to(...)->send(...)` → `Mail::to(...)->queue(...)` in `handleRefundProcessed()`.
   - **File**: `app/Http/Controllers/CampaignController.php:139`
   - **Change**: `Mail::to(...)->send(...)` → `Mail::to(...)->queue(...)`.
   - **File**: `app/Http/Controllers/Admin/CampaignController.php:326, 340, 389, 390`
   - **Change**: Eager-load `user` on `Campaign::find($id)` in bulk loops; switch `Mail::send` → `Mail::queue`.

3. **Resolve Vite merge conflict**
   - **File**: `vite.config.js:70-85`
   - **Change**: Remove `<<<<<<< HEAD`, `=======`, `>>>>>>> origin/master` and keep a single valid input array.

### P1 — High value, low risk

4. **Add missing database indexes**
   - **Migration**: Add index on `campaign_products(approval_status)`, `campaign_products(source)`, `campaign_products(reserved_quantity)`, `campaign_products(remaining_quantity)`.
   - **Migration**: Add index on `donations(order_id)`.
   - **Migration**: Add index on `product_reservations(donation_id)`, `product_reservations(session_id)`.
   - **Migration**: Add index on `campaigns(slug)`, `campaigns(end_date)`.
   - **Migration**: Add unique index on `coupons(code)` (currently only queried, no uniqueness guarantee).
   - **Migration**: Add index on `wallets(owner_type, owner_id)` and enforce uniqueness.

5. **Enable OPcache**
   - **File**: `C:\xampp\php\php.ini`
   - **Line 964**: Change `;zend_extension=opcache` → `zend_extension=opcache`
   - **Lines 1802-1900**: Uncomment and set:
     ```
     opcache.enable=1
     opcache.memory_consumption=256
     opcache.interned_strings_buffer=16
     opcache.max_accelerated_files=20000
     opcache.validate_timestamps=0
     opcache.revalidate_freq=0
     ```
     (Set `validate_timestamps=1` only if you restart Apache after code changes.)

6. **Disable Telescope for local perf testing**
   - **File**: `.env:93`
   - **Change**: `TELESCOPE_ENABLED=false`

### P2 — Cleanup config

7. **Fix .env anomalies**
   - **File**: `.env:71` — Remove or correct `AWS_ACCESS_KEY_ID=CACHE_DRIVER`.
   - **File**: `.env:85` — Remove duplicate `QUEUE_CONNECTION=database` (or move desired value to top).
   - **File**: `.env:69,76` — Remove duplicate `VITE_APP_NAME` lines.
   - **File**: `.env:93` — Set `DEBUGBAR_ENABLED=false` (already set).

8. **Build caches**
   - Run `php artisan config:cache` (optimizes config loading).
   - Run `php artisan route:cache` (optimizes route registration).
   - Do **not** run `view:cache` in local dev.

9. **Queue connection alignment**
   - **File**: `.env:41,85`
   - **Change**: Set `QUEUE_CONNECTION=database` (or `redis`) and ensure `php artisan queue:work --daemon` or supervisor is running if queues are actually used. Currently `sync` defeats the purpose of any queued job.
