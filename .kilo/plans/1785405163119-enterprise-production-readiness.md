# Enterprise Production Readiness Plan

**Branch**: `fix/product-reservation-and-admin-fixes`  
**Target score**: 10/10 from 9.2/10  
**Baseline**: Single VPS + Docker Compose  
**Constraint**: No feature removals; backward-compatible improvements only

---

## 1. Redis Integration

### Problem
- `config/cache.php` defaults to `file` or `array` driver in local.
- No Redis config for session, rate limiting, or queue.
- Homepage and expensive queries hit the DB on every request.

### Impact
- Cache misses throttle response time under load.
- Sessions don't scale across multiple workers.
- Rate limiting is per-process (unreliable).

### Actions
1. `config/database.php` — Add `predis/predis` (v2) client; keep `memcached` as fallback.
2. `config/cache.php` — Set `default=redis`; add `stores.redis` (single-node) and `stores.cache_tags` using tags.
3. `config/session.php` — Set `driver=redis`, `connection=default`.
4. `config/queue.php` — Add `redis` connection with separate queues:
   - `emails` — priority 1
   - `default` — priority 5
   - `notifications` — priority 10
5. Add `config/redis.php` if not present.
6. **Graceful fallback**: wrap `Cache::remember()` in try/catch; fallback to `array` driver or short TTL when Redis throws `ConnectionException`.
7. **Cache expensive queries**:
   - `PublicCampaignController::show()` — cache campaign + relations (events, followers) for 5 min with tags `campaign:{id}`.
   - Homepage stats — cache aggregated donations and active campaigns for 2 min.
   - Admin dashboard — cache each widget for 1-5 min with tag `admin:widgets`.
8. **Cache tags**: use `Cache::tags()` only where invalidation is cheap (single tenant). Document that Redis is required for tags.

### Migration steps
- `composer require predis/predis`
- Provision single Redis instance (Docker or apt).
- Update `.env` and `config/`.
- Run `php artisan config:cache` after deploy.
- **Rollback**: revert `CACHE_DRIVER` to `file`.

---

## 2. Queue Optimization

### Problem
- Jobs lack `$tries`, `$timeout`, `$backoff`, and proper queue names.
- Failed jobs are not retried or observed.
- No queue monitoring in production.

### Impact
- Transient gateway failures poison the queue.
- Long-running jobs block workers.
- No visibility into queue health.

### Actions
1. Update every queued job class:
   - Add `public $tries = 3;`
   - Add `public $timeout = 120;`
   - Add `public $backoff = [60, 300, 900];`
2. Add `public $queue = 'emails|default|notifications';` per job by domain.
3. **ShouldQueue contracts**:
   - `CampaignApproved`, `CampaignRejected`, `CampaignUpdated` → queue `notifications`.
   - `DonationReceived` → queue `emails`.
4. **Batching**:
   - `ReleaseMaturedReserves` — use `Bus::batch()` to process settlements in batches of 50 with progress events.
   - Newsletter export — batch `Subscriber` export.
5. **Failed jobs**: confirm `php artisan queue:failed-table` is migrated and `queue:retry` workers are in Supervisor.
6. **Supervisor**:
   - Separate processes per queue: `emails:1`, `default:2`, `notifications:1`.
   - Add `--sleep=3 --timeout=180 --tries=3`.
7. **Horizon** (dev/staging):
   - `config/horizon.php` with queues: `emails`, `default`, `notifications`.
   - Production: deploy as `php artisan horizon` under Supervisor (do not deploy unless Redis is stable).

### Migration steps
- Deploy code changes to jobs.
- Restart queue workers with `php artisan queue:restart`.
- Monitor with Horizon dashboard (local/staging).

---

## 3. Database Optimization

### Problem
- No composite indexes visible in search for `donations`, `payments`, `campaigns`.
- N+1 queries likely in admin dashboards and campaign feeds.
- PostgreSQL/SQLite in use; SQLite does not support production scale.

### Impact
- Slow admin loops, timeouts under concurrency.

### Actions
1. Add composite indexes via migrations:
   - `donations (campaign_id, created_at DESC)` — feed + analytics.
   - `donations (status, created_at)` — admin list filters.
   - `payments (status, created_at DESC)` — webhook lookups.
   - `wallet_transactions (wallet_id, type, created_at DESC)` — dashboard.
   - `events (campaign_id, status, event_date)` — public eager load.
2. Fix N+1 suspects:
   - `Admin\DashboardController` — eager load counts via `withCount` rather than loops.
   - `PublicCampaignController::show()` — relations already present; add `events` load (done uncommitted).
   - Campaign feeds partial — verify `followers`, `updates` are `withCount` / `limit`.
3. Replace `paginate()` with `simplePaginate()` on admin list views to reduce query cost.
4. Add `DB::listen()` in local env to log slow queries (>100ms).

### Migration steps
- Write pending indexes in new migration file.
- Run `php artisan migrate` on staging first.
- Monitor `SHOW INDEX` and query plans.

---

## 4. Automated Testing

### Problem
- 153 tests exist, but coverage is below 80%.
- Missing: full auth, payment, campaign state machine, settlements, API contracts.

### Actions
1. Add `tests/Feature/`:
   - `Auth\RegistrationTest`, `LoginTest`, `PasswordResetTest`, `EmailVerificationTest` — all present; expand input validation edge cases.
   - `PaymentFlowTest` — mock gateway, assert webhook signature, retry on failure.
   - `CampaignStateTransitionTest` — state machine coverage.
   - `SettlementFlowTest` — success, failure, retry.
   - `WalletSettlementFlowTest` — already exists; expand edge cases.
   - `AuthorizationTest` — each role can access denied routes.
   - `NotificationPreferenceTest` — already exists; expand bulk + reset.
2. Add `tests/Unit/`:
   - `Services/NotificationServiceTest`
   - `Services/ProductReservationServiceTest`
   - `Models/EventTest` (scope methods)
3. Add `tests/Pest.php` or continue PHPUnit; ensure `coverage.xml` target ≥ 80%.
4. Add `tests/Feature/FormValidation/*` — expand to cover all admin API routes.

### Migration steps
- Add `xdebug` or `PCOV` in CI.
- Fail CI run if coverage < 80%.

---

## 5. Security Audit

### Problem
- CSRF tokens present but need validation on every state-changing request.
- No evidence of `X-XSS-Protection`, `HSTS`, CSP headers.
- File upload validation missing explicit MIME + max size checks in some controllers.
- No global rate limiting on auth endpoints.

### Impact
- XSS via blog content if whitelist fails.
- Session fixation if session config not hardened.
- Password spraying if rate limiting absent.

### Actions
1. **CSRF**: confirm `VerifyCsrfToken` middleware excludes only legitimately public routes; add `content-type: application/json` exception only for API.
2. **XSS**:
   - Escape all output via Blade `{{ }}` (already default).
   - Blog rich content — whitelist tags (`p`, `strong`, `em`, `ul`, `li`, `a`, `h2`); strip `script` and `on*`.
   - Add `Content-Security-Policy` header via middleware.
3. **SQL Injection**:
   - All Eloquent queries are safe; raw queries must use bindings (audit `app/Services/Risk` if any raw).
4. **Mass Assignment**:
   - Audit `$fillable` on all 56 models; remove silent `$guarded = []`.
5. **Rate Limiting**:
   - `app/Http/Kernel.php` — add per-route throttle for login, register, and API v1.
   - Auth: 5 attempts / minute.
   - API: 60 requests / minute / IP.
6. **File Uploads**:
   - Add `mimes:jpg,jpeg,png,pdf` + `max:5120` on every `StoreBlogRequest`, `KycUploadRequest`, event cover.
   - Use `Storage::disk('public')->putFile('...', $file)` only after validation.
7. **Headers**:
   - Add `SecureHeadersMiddleware`:
     - `X-Frame-Options: DENY`
     - `X-Content-Type-Options: nosniff`
     - `Referrer-Policy: strict-origin-when-cross-origin`
     - `Permissions-Policy: geolocation=(), microphone=()`
   - In production: `Strict-Transport-Security: max-age=31536000; includeSubDomains`.
8. **Session security**:
   - `SESSION_SECURE_COOKIE=true`, `SESSION_HTTP_ONLY=true`, `SESSION_SAME_SITE=lax`.
9. **Password policy**:
   - Enforce min 8 + mixed case + number via `Password::defaults()` in `AuthServiceProvider`.
10. **Secrets management**:
    - Move `.env` to server-level env (Forge/Docker secrets); `.env` must not be in git.
11. **Validation**:
    - Every form request already wired; ensure API routes use FormRequest classes too.

---

## 6. Performance Optimization

### Problem
- Large unoptimized Blade templates.
- No route or config cache in production.
- Assets not versioned.

### Impact
- Slow first response, large payloads.

### Actions
1. **Routes**: `php artisan route:cache` in CI/CD.
2. **Config**: `php artisan config:cache`.
3. **Views**: `php artisan view:cache`.
4. **Composer**: `composer install --no-dev --optimize-autoloader`.
5. **Blade**: break `dashboard.blade.php` (>64KB) and `welcome.blade.php` (>82KB) into partials/components.
6. **Images**:
   - Accept only optimized uploads via frontend resize (intervention/image or spatie/laravel-image-optimizer).
   - Lazy-load event and campaign cover images (done in CSS).
7. **Pagination**: use cursor pagination for infinite-feed APIs if added.
8. **Response time**: add `Cache-Control: public, max-age=300` on campaign pages.

---

## 7. Deployment Hardening

### Problem
- No documented zero-downtime deployment.

### Actions
1. Deploy script order:
   - `php artisan down --render="errors::503" --status=503`
   - `git pull origin <branch>`
   - `composer install --no-dev --optimize-autoloader`
   - `php artisan migrate --force`
   - `php artisan config:cache`
   - `php artisan route:cache`
   - `php artisan view:cache`
   - `php artisan event:cache`
   - `php artisan queue:restart`
   - `php artisan up`
2. **Health check**: `GET /api/v1/health` returning JSON `{status, cache, db, queue, redis}`.
3. **Rollback**:
   - Keep previous release symlink.
   - `php artisan migrate:rollback` on failure or restore symlink.
4. **Environment validation**:
   - `php artisan env` or custom `CheckEnvironmentCommand` to assert `APP_DEBUG=false`, `APP_KEY` set, Redis reachable.

---

## 8. Logging

### Problem
- Single `storage/logs/laravel.log` for everything.

### Actions
1. `config/logging.php` — Add channels:
   - `stack` (default): `daily` + `papertrail` optional.
   - `payments`: `daily`, path `logs/payments.log`.
   - `donations`: `daily`, path `logs/donations.log`.
   - `wallet`: `daily`, path `logs/wallet.log`.
   - `settlement`: `daily`, path `logs/settlements.log`.
   - `auth`: `daily`, path `logs/auth.log`.
   - `security`: `daily`, path `logs/security.log`.
   - `queue`: `daily`, path `logs/queue.log`.
2. **Sensitive data**: add `App\Exceptions\SensitiveDataRedactor` implementing `Monolog\Processor\ProcessorInterface`; scrub email, password, card numbers.
3. Daily rotation: 7 days local; stream to external in production.

---

## 9. Monitoring

### Problem
- No monitoring stack.

### Actions
1. **Development**: install `laravel/telescope` (dev-only via `--dev`).
2. **Production**:
   - `laravel/horizon` for queue monitoring (Redis required).
   - Redis `INFO` + `slowlog` via Monolog or separate exporter.
3. **Health endpoint**:
   - `GET /api/v1/health` checks DB, Redis, Queue, Storage.
4. **Slow queries**: `DB::listen()` logging queries > 100ms to `logs/slow-queries.log`.
5. **Server metrics**: Deploy `prometheus-node-exporter` + `cAdvisor`; connect to Grafana.

---

## 10. Error Handling

### Problem
- No explicit production error boundaries.

### Actions
1. `app/Exceptions/Handler.php`:
   - Render user-friendly JSON for API; respected error pages for web.
   - Do not expose `APP_DEBUG` stack traces in production (already default behavior; enforce in `APP_ENV=production`).
2. Add `render()` for `HttpException`, `ModelNotFoundException` → 404 JSON.
3. Add `render()` for `AuthorizationException` → 403 JSON.
4. Log full exception context (request id, user id, url).

---

## 11. Environment Configuration

### Current state
- `.env` present; unclear if `APP_KEY` is set.
- `CACHE_DRIVER`, `QUEUE_CONNECTION` likely `file` locally.

### Actions
1. Enforce defaults in `.env.example`:
   - `APP_ENV=production` (override per deploy)
   - `APP_DEBUG=false`
   - `CACHE_DRIVER=redis`
   - `QUEUE_CONNECTION=redis`
   - `SESSION_DRIVER=redis`
   - `SESSION_SECURE_COOKIE=true`
   - `SESSION_HTTP_ONLY=true`
   - `SESSION_SAME_SITE=lax`
   - `REDIS_HOST=127.0.0.1`
   - `REDIS_PASSWORD=null`
   - `REDIS_PORT=6379`
2. Deploy secrets via `docker secret` or server env manager, NOT `.env`.
3. `FILESYSTEM_DISK=public` for uploads; keep `storage/app/private` for sensitive files.

---

## 12. Code Quality

### Actions
1. **PSR-12**: add `laravel/pint` config and run in CI.
2. **Static analysis**: add `phpstan` level 5.
3. **SOLID / Repository**: do NOT rewrite unless a concrete duplication issue; add `Repository` only if data source changes.
4. **Service layer**: already present; continue using it in controllers.
5. **Dependency injection**: type-hint all controller and service dependencies; avoid facades in services.

---

## 13. API Optimization

### Actions
1. Pagination: all index API routes must return `Illuminate\Pagination\LengthAwarePaginator` with `links` for `api/v1/`.
2. Resource responses: add `App\Http\Resources\CampaignResource`, `DonationResource`, `EventResource` for `appends`.
3. Rate limiting: `ThrottleRequests` middleware with per-IP and per-user keys.
4. Consistent JSON: success `{data, meta}`, error `{message, errors, code}`.
5. HTTP status codes: 201 created, 204 no content, 422 validation.

---

## 14. Storage

### Current state
- Public storage symlinked.

### Actions
1. Ensure `storage:link` exists.
2. Keep `storage/app/public` for uploads; never serve from `app/`.
3. Validate upload size: `max:10240` KB for images and docs.
4. Cleanup: scheduled command to delete unused `storage/app/public/` orphaned files via `orphaned` detector.
5. S3 backup: optional; if used, `FILESYSTEM_DISK=s3` only for backups, not daily uploads.

---

## 15. CI/CD Recommendations

### Actions
1. **GitHub Actions**:
   - `phpunit` on every PR.
   - `pint --test`.
   - `phpstan analyse`.
   - `composer validate`.
2. **Pipeline**:
   - Lint → Test → Build image → Deploy via SSH or Forge deploy hook.
3. **Branch strategy**: `main` = production; `fix/*` = release candidates.

---

## 16. Documentation

### Actions
1. `README.md` — add:
   - Production prerequisites (PHP 8.4+, Composer 2, Redis, Supervisor).
   - `.env.example` with comments.
   - Queue worker setup commands.
   - Horizon install steps.
2. `docs/deployment.md`:
   - Step-by-step deploy script.
   - Zero-downtime instructions.
   - Rollback procedure.
3. `docs/backup.md`:
   - Daily DB dump schedule.
   - Redis RDB/AOF retention.
   - Disaster recovery runbook.
4. `docs/redis.md`:
   - Single-node setup.
   - Sentinel notes for future migration.

---

## Execution Order

| Phase | Focus | Estimated effort |
|------|-------|----------------|
| 1 | Redis + Queue + Logging + Monitoring configs | Low — config changes |
| 2 | Database indexes + N+1 fix | Low — migrations |
| 3 | Security headers, rate limits, validation hardening | Medium — middleware + requests |
| 4 | Tests expansion (target 80%) | High |
| 5 | CI/CD implementation | Medium |
| 6 | Documentation + deployment scripts | Medium |

## Validation Steps

- `php artisan optimize` succeeds without errors.
- `php artisan config:cache && route:cache && view:cache` all green.
- Redis connection: `php artisan tinker` → `Cache::put('test', 1, 60)`.
- Queue: `php artisan queue:work --once` processes jobs without timeout.
- Tests: `vendor/bin/pest --coverage` ≥ 80%.
- Security: `php artisan security:check` or manual header audit with `curl -I`.
- Logging: trigger one payment event, verify `storage/logs/payments.log`.
- Health: `curl http://localhost/api/v1/health` returns 200 with JSON.

## Out of Scope

- Multi-tenant / Redis Cluster (revisit after traffic > 10k/day).
- Kubernetes deployment.
- CDN hardening (keep Apache/Nginx as-is).
