# Deployment

## Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Redis (for queue, cache, locks)

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
Set `APP_ENV=production`, `APP_DEBUG=false`, database credentials, mail/driver settings.

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
Use supervisor/systemd for production process management.

### 8. Scheduler
```bash
php artisan schedule:run --no-interaction
```
Add to crontab:
```
* * * * * cd /path/to/fundraise && php artisan schedule:run --no-interaction >> /dev/null 2>&1
```

### 9. HTTPS / Proxy
- Ensure `APP_URL` is `https://`.
- Set `TRUSTED_PROXIES` if behind load balancer.
- CSP nonce requires HTTPS in production.
- `Strict-Transport-Security` header set by `SecureHeadersMiddleware` when `force_https` is true.

## Redis
- Used for: cache, queue, distributed locks (e.g., `reconciliation_job_lock`).
- Ensure `REDIS_HOST`, `REDIS_PASSWORD` are set in `.env`.

## Vite Build
- Assets built to `public/build/`.
- Manifest (`manifest.json`) must be present for Vite asset resolution.
- HMR uses `public/hot` file (development only).

## Cache Commands
- `optimize:clear` — clears all caches.
- `config:cache` — merges config into single file.
- `route:cache` — compiles routes.
- `view:cache` — pre-compiles Blade templates.
- Do NOT run `config:cache` or `route:cache` during local development (prevents changes from being picked up).
