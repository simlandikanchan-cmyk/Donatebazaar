# DonateBazaar — Enterprise Production Readiness

Crowdfunding platform built on Laravel 12 with Redis, queues, separated logging, and production-hardened security.

## Prerequisites

- PHP 8.4+
- Composer 2
- MySQL 8.0 / PostgreSQL
- Redis 7+
- Supervisor (for queue workers)
- Node.js 18+ (for asset builds)

## Quick Start

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer install --no-dev --optimize-autoloader
```

## Queue Workers

Supervisor configs are in `supervisor/queue-worker.conf`:

- `emails` queue — 1 process
- `default` queue — 2 processes
- `notifications` queue — 1 process

Restart workers after deploy:

```bash
php artisan queue:restart
```

## Health Check

```
GET /api/v1/health
```

Returns JSON with status of cache, db, queue, and redis.

## Architecture

- **Routes**: Entry points in `routes/web.php` and `routes/api.php` require individual files from subdirectories.
- **Repositories**: Singleton pattern bound in `AppServiceProvider`.
- **Gateways**: Payment abstraction in `app/Gateways/`.
- **Notifications**: Preference-driven via `HasNotificationPreferences` trait.
- **Logging**: Domain-specific channels in `storage/logs/` with sensitive data redaction.

## Testing

```bash
vendor/bin/pest --coverage
```

## CI/CD

GitHub Actions workflow runs lint, tests, and static analysis on every PR.

## Documentation

- `docs/deployment.md` — Zero-downtime deploy script
- `docs/redis.md` — Redis setup and sentinel notes
- `docs/backup.md` — DB dump and Redis backup procedures

## License

MIT
