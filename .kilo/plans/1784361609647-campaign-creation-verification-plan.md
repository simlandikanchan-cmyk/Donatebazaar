# Plan: Verify Campaign Creation Flow (end-to-end)

> Scope: a verification/QA plan for the campaign-creation flow now that the P0/P1
> fixes are in. **No source changes** — this plan is for *checking* the flow once the
> app is runnable (DB migrated, secrets re-supplied, `php artisan test` green).
> Keep in plan mode; do not edit source files.

## Context (current state of the code, confirmed by reading)
- Route: `POST /campaign/store` → `CampaignController::store` (auth-only). `routes/web/campaigns.php:16`.
- `store()` order: auth guard → `FundraiserLevelService::canCreateCampaign()` → `CREATE_RULES` validation
  → nested upload validation → "at least one update" check → `Campaign::create` → `storeCampaignUpdates`
  → `storeCampaignProducts` → cache clear → `CampaignCreatedMail` → redirect to `kyc.upload.form`.
- Fixes already applied:
  - `CREATE_RULES`/`UPDATE_RULES` now: `description => required|string|max:20000`,
    `goal_amount => required|numeric|min:1|max:500000` (`CampaignController.php:28-50`).
  - Nested uploads validated: `products.*.image` (image, jpg/jpeg/png/webp, 2MB) and
    `updates.*.document` (pdf/jpg/jpeg/png, 5MB) (`CampaignController.php:96-99`).
  - `.env` secrets blanked (Razorpay/Google/Gmail/OpenAI/Anthropic) — **must be re-supplied** before run.
- Known constraint: command execution (php/artisan/composer) is blocked in this env.
  This plan is written so the user (or an implementation-capable agent) runs it locally.

## Pre-conditions before checking ("once all is working")
1. `composer install` done; `.env` has valid RAZORPAY_KEY/SECRET, GOOGLE_*, MAIL_*, and a DB connection.
2. `php artisan migrate --force` (or `migrate:fresh --seed` if a seeder assigns fundraiser levels).
3. `php artisan test` passes — note existing broken stubs will fail:
   - `tests/Feature/DonationFlowTest.php:14` hits `/donate` (no such route).
   - `tests/Feature/CampaignTest.php:16` hits `/campaigns` (auth-only → 302, not 200).
   - `tests/Feature/ProfileTest.php:30-43` asserts email change but `ProfileUpdateRequest` removed email.
   These must be fixed or skipped before a green run. (See plan section "Fix test stubs".)
4. A test user exists with a **fundraiser level assigned** (`FundraiserLevelService::resolveLevel`
   falls back to `FundraiserLevel::first()` if none — `FundraiserLevelService.php:127` — so creation
   still proceeds even without an explicit assigned level, but goal cap = that level's cap.)
5. At least one active `Category` exists (route `create()` loads `Category::where('is_active',1)`).

## Verification steps (do these once runnable)
Run against a local server (`php artisan serve`) in a browser or via feature tests.

### A. Happy path
- Login as a normal user, GET `/campaign/create` → 200, form renders with categories + maxGoal.
- POST `/campaign/store` with: valid title, description, `goal_amount` ≤ level cap, valid `category_id`,
  valid `cover_image` (jpg/png), `start_date` ≤ `end_date`, one update (title+body).
- Expect: redirect to `/kyc/upload/{id}` with success; `Campaign` row created with
  `campaign_state = pending`, `raised_amount = 0`, `user_id = auth()->id()`, valid `slug`.

### B. Validation rules (each should redisplay form with input + errors)
- `description` empty → required; `description` > 20000 chars → max error.
- `goal_amount` = 0 / negative → `min:1`; `goal_amount` = 500001 → `max:500000`.
- `goal_amount` with comma `1,000,000` → comma stripped, then caught by `max` (confirms str_replace+max interplay).
- missing/invalid `category_id` → `exists` error.
- `cover_image` wrong type (e.g. gif/txt) → image/mimes error; oversized → max:2048.
- `end_date` < `start_date` → `after_or_equal` error.
- `video_url` non-URL → `url` error.
- No update row (no title+body) → custom "add at least one update" error.

### C. File-upload hardening (P0 fix #4)
- `products[0][image]` = `evil.svg` or `shell.php` → rejected by `mimes`/`image` validation (422/form error, NOT stored).
- `products[0][image]` = valid 3MB png → rejected by `max:2048`.
- `updates[0][document]` = `script.html` → rejected by `mimes` (pdf/jpg/jpeg/png only).
- Confirm no file appears under `storage/app/public/campaign-products` for rejected cases.

### D. Authorization / business rules
- Guest POST `/campaign/store` → redirect to login (auth guard). (`CampaignController.php:75`)
- Fundraiser-level gate: set goal above the user's `max_goal_amount` OR exceed
  `max_active_campaigns` → `back()` with `goal_amount`/count error, NO row created. (`FundraiserLevelService.php:38-67`)
- Confirm `slug` uniqueness: create two campaigns with same title → second gets `-1` suffix
  (`generateSlug`, `CampaignController.php:~494`).

### E. Side effects
- `CampaignCreatedMail` sent to `$campaign->user` (check `MAIL_MAILER=log` → `storage/logs`
  for the rendered email, no `{{ $undefined }}` leakage).
- `Cache::forget('active_campaign_categories')` fired (no error).
- Cover image stored as `images/<slug>-<time>.webp` via Intervention (`uploadCoverImage`, `CampaignController.php:477`).

### F. Regression check on earlier fixes
- IDOR: as User B, GET `/campaign/{A-id}` → 403 (`CampaignController.php:151`).
- Blog XSS: create a blog with `<script>`/`<img onerror>` in content → stored as plain text,
  public blog page renders it escaped. (BlogService `sanitizeContent`.)

## Fix test stubs (required so `php artisan test` is green — implement separately)
- `DonationFlowTest`: hit a real route (e.g. `GET /all-campaigns` or an authed `/campaigns`) — but
  `/campaigns` is auth-only; wrap in `actingAs`. Or test the payment page route shape instead.
- `CampaignTest`: wrap `GET /campaigns` in `actingAs($user)` and assert 200.
- `ProfileTest`: stop asserting email changes (remove email from the patch payload) — matches current `ProfileUpdateRequest`.

## Rollout / validation
- Run: `php artisan test` (expect green after stub fixes).
- Run: manual happy-path + B/C/D in browser against `php artisan serve`.
- Check `storage/logs/laravel.log` for no stack traces / no plaintext secrets after the run.

## Open questions / risks
- Is there a seeder that assigns `FundraiserLevel` + `KycVerification`? If not, manual setup needed.
- `resolveLevel` fallback `FundraiserLevel::first()` may pick an unintended level cap — confirm intent.
- `CampaignCreatedMail` is sent synchronously inside `store()`; a mail failure is caught? (It is NOT
  wrapped in try/catch here, unlike PaymentController's receipt mail — a mail exception would 500 the
  creation. Recommend wrapping in try/catch as a follow-up, but out of scope for this check.)
