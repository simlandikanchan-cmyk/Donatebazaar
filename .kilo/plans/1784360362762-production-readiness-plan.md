# Production Readiness Assessment — `fundraise` (Laravel 12)

## Verdict
**Not ready for production.** The application code (models, migrations, routes, tests, admin/Auth/Blog/Payment flows) is substantial and reasonably structured, but the project leaks live credentials, runs in `local` config, has conflicting/insecure environment settings, and ships no deployment or secret-management story. Below is the prioritized remediation plan.

## Critical (must fix before any deploy)

### 1. Secret leakage — local `.env` contains real credentials
The working `.env` (root) holds live secrets and must NOT exist with these values:
- `RAZORPAY_KEY` / `RAZORPAY_SECRET` (test keys, still real)
- `MAIL_PASSWORD=ummiexfnxfsbqens` (Gmail app password)
- `GOOGLE_CLIENT_SECRET=GOCSPX-...`
- `OPENAI_API_KEY=sk-proj-...`, `ANTHROPIC_API_KEY=sk-ant-...`
- `APP_KEY` (session/encryption key)

Also present in root: `backup_before_trigger_migration.sql` (DB dump, gitignored via `*.sql` but still on disk).

Actions:
- Rotate/revoke every exposed secret now (Razorpay, Gmail, Google OAuth, OpenAI, Anthropic). Treat them as compromised.
- Replace `.env` with values from `.env.production.example` on the real server only; never commit real `.env`.
- Confirm `.gitignore` already excludes `.env` (it does) — but remove the real secrets from the local file so it is safe to copy.
- Delete `backup_before_trigger_migration.sql` and root `routes.json` / `routes_full.txt` / `filtered_routes.txt` debug artifacts from the working tree (they are not gitignored and expose internal structure).

### 2. `APP_ENV=local` and `APP_DEBUG=false` mismatch (`.env:1-4`)
- Set `APP_ENV=production` and `APP_DEBUG=false` on the server (`.env.production.example` already does this — ensure it is the source of truth).
- Keep `APP_DEBUG=false` to avoid stack traces leaking in prod.

## High (fix before launch)

### 3. Conflicting / duplicate environment variables (`.env`)
The file sets the same key to two values in places:
- `QUEUE_CONNECTION=sync` (line 41) then `QUEUE_CONNECTION=database` (line 77) — last wins, but ambiguous. Pick one (database) and remove the duplicate.
- `MAIL_MAILER=log` (line 53) then `MAIL_MAILER=smtp` (line 96) with real creds later — consolidate.
- `CACHE_DRIVER=file` (line 76) plus `CACHE_STORE=file` — `CACHE_DRIVER` is the old key; use `CACHE_STORE` only.
- `REDIS_*` defined twice (lines 48-51 and 80-82).
- `SESSION_SECURE_COOKIE=true` (line 35) while `APP_URL=http://127.0.0.1` — over HTTPS this is fine, but the app URL and cookie must match the real domain.
- `TELESCOPE_ENABLED=true` (line 85) — **disable in production** (`TELESCOPE_ENABLED=false`); it exposes request/SQL data. `DEBUGBAR_ENABLED=false` is fine.
- `LOG_STACK=singleF` (line 20) is a typo — should be `single`.

### 4. No built frontend assets (`public/build` absent)
- `package.json` defines `build` = `vite build`. Run `npm install && npm run build` and ensure `public/build` ships. The `.gitignore` excludes `public/build`, so it must be built at deploy time (CI or deploy script), not committed.

### 5. No deployment / runtime configuration
Missing for a real deploy:
- Web server config (nginx/Apache `DocumentRoot` → `public/`, `index.php` rewrite, HTTPS/TLS termination).
- Process manager for queue worker (since `QUEUE_CONNECTION=database`, run `php artisan queue:work` via Supervisor/systemd).
- Scheduler for `php artisan schedule:run` (commands like `ExpireCampaigns`, `SendKycReminders` exist in `app/Console`).
- Post-deploy steps: `php artisan config:cache`, `route:cache`, `view:cache`, `migrate --force`. (`.env.production.example` documents these — turn them into a deploy script/CI job.)
- `.env.production.example` still has `SESSION_DRIVER=file` and `CACHE_STORE` unset — for multi-instance use `redis` or `database`; consolidate with `.env`.

## Medium (hardening)

### 6. Secrets in example files
- `RAZORPAY_KEY=rzp_test_...` is in `.env.production.example` — replace with placeholder `your-razorpay-key`. Same for any other concrete values.
- Ensure `.env.production.example` references `RAZORPAY_SECRET`, `GOOGLE_CLIENT_SECRET`, mail password as placeholders only.

### 7. Tests exist but scope is limited
- 13 Feature tests cover auth, donation, refund webhook, campaign, admin — good baseline. No CI workflow runs them. Add a GitHub Actions (or equivalent) pipeline: `composer install`, `npm ci`, `php artisan test`, plus lint (`pint`) and `composer audit`.
- `tests/Feature/ExampleTest.php` and `tests/Unit/ExampleTest.php` are skeletons — remove or replace.

### 8. Dependency / security hygiene
- Run `composer audit` and `npm audit` before deploy; Laravel 12 + PHP 8.2 are current, but verify no known CVEs in pinned versions.
- `barryvdh/laravel-debugbar` and `beyondcode/laravel-query-detector` are dev-only (good) — ensure they are NOT auto-discovered in production (`dont-discover` is empty in `composer.json`; verify they don't load when `APP_ENV=production` / debug off).

## Validation plan
1. On a staging host: copy `.env.production.example` → `.env`, fill placeholders with **rotated** secrets, `php artisan key:generate`.
2. `php artisan config:cache && route:cache && view:cache`.
3. `php artisan migrate --force`, run seeders as needed.
4. `npm ci && npm run build`; confirm `public/build` exists.
5. Start web server + `queue:work` + `schedule:run`; hit `/up` or health endpoint (spatie/laravel-health is installed).
6. Run `php artisan test` in CI; confirm green.
7. Verify `/telescope` is inaccessible (403/404) and no debugbar in responses.

## Open questions for the user
- What is the target deployment target (shared hosting / VPS / Docker / Forge / Laravel Cloud)? This determines the deploy script and server config to author.
- Should `FILESYSTEM_DISK` move to `s3`/`cloudinary` (Cloudinary SDK is already required) for user uploads in production?
- Is `QUEUE_CONNECTION=database` acceptable, or should we provision Redis for cache/queue/sessions?
