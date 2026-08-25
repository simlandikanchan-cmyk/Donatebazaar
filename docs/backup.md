# Backup Procedures

## Daily DB dump

```bash
mysqldump -u root -p donatebazaar_final > /backups/db-$(date +\%F).sql
```

Retention: 7 days local, 30 days external.

## Redis

Enable AOF + RDB snapshots. `redis-data` volume is persisted in Docker.

## Disaster recovery

1. Restore DB dump
2. Restore Redis from RDB/AOF
3. Run `php artisan migrate`
4. Clear caches: `php artisan optimize:clear`
