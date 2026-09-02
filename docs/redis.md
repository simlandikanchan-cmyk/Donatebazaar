# Redis Setup

Redis handles sessions, cache, and the queue. The setup below covers a single node; see the Sentinel note at the end for when you outgrow it.

## Single-node

```bash
docker run -d --name redis --restart unless-stopped -p 6379:6379 redis:7-alpine
```

## Laravel config

- `config/database.php` — Redis client set to `predis`
- `config/cache.php` — default store `redis`, `cache_tags` store for tagged caches
- `config/session.php` — driver `redis`
- `config/queue.php` — connection `redis`

## Sentinel (future)

When traffic exceeds 10k requests/day, move to Redis Sentinel for high availability.