# Backup Procedures

A working backup plan matters more here than in most apps because wallet balances and donation records are money. These procedures cover the daily database dump, Redis persistence, and disaster recovery.

## Daily DB dump

```bash
mysqldump -u root -p donatebazaar_final > /backups/db-$(date +\%F).sql
```

Retention: 7 days locally, 30 days externally.

## Redis

Keep AOF plus RDB snapshots enabled. The `redis-data` volume is persisted in Docker, so the data itself survives container recreation.

## Disaster recovery

1. Restore the DB dump.
2. Restore Redis from the RDB/AOF backups.
3. Run `php artisan migrate`.
4. Clear caches: `php artisan optimize:clear`.