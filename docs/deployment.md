# Deployment

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Redis (queue, cache, locks)

## Steps

### 1. Code

```bash
git clone <repo>
cd fundraise
git checkout <release-branch>
```

### 2. PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Node Dependencies & Build

```bash
npm ci
npm run build
```

### 4. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Then set `APP_ENV=production`, `APP_DEBUG=false`, database credentials, and the mail/driver settings.

### 5. Database

```bash
php artisan migrate --force
php artisan db:seed --force  # if applicable
```

### 6. Laravel Cache

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Queue Workers

```bash
php artisan queue:work --daemon --sleep=3 --tries=3 --max-time=3600
```

Manage workers with supervisor or systemd in production — don't rely on a bare terminal session.

### 8. Scheduler

```bash
php artisan schedule:run --no-interaction
```

Add this to the crontab:

```
* * * * * cd /path/to/fundraise && php artisan schedule:run --no-interaction >> /dev/null 2>&1
```

### 9. HTTPS / Proxy

- Ensure `APP_URL` is `https://`.
- Set `TRUSTED_PROXIES` if running behind a load balancer.
- The CSP nonce requires HTTPS in production.
- `Strict-Transport-Security` is set by `SecureHeadersMiddleware` when `force_https` is true.

## Redis

Redis backs the cache, the queue, and distributed locks (e.g., `reconciliation_job_lock`). Make sure `REDIS_HOST` and `REDIS_PASSWORD` are set in `.env`.

## Vite Build

- Assets build to `public/build/`.
- The manifest (`manifest.json`) must be present for Vite asset resolution.
- HMR writes a `public/hot` file — development only.

## Cache Commands

- `optimize:clear` — clears all caches.
- `config:cache` — merges config into a single file.
- `route:cache` — compiles routes.
- `view:cache` — pre-compiles Blade templates.

Do NOT run `config:cache` or `route:cache` during local development; cached config/routes will silently ignore your edits until cleared.