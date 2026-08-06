# Deployment Guide

## Zero-downtime deploy script

```bash
php artisan down --render="errors::503" --status=503
git pull origin <branch>
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan queue:restart
php artisan up
```

## Rollback

1. Restore previous release symlink
2. Run `php artisan migrate:rollback` on failure
3. Run `php artisan queue:restart`

## Environment validation

```bash
php artisan env
```

Assert:
- `APP_DEBUG=false`
- `APP_KEY` is set
- Redis reachable

## Secrets

Never commit `.env`. Use server-level env manager or Docker secrets.
