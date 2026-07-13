# Coupon / Discount Code for Donations

## Context
Users want to apply a coupon code on a campaign donation page so a discount is subtracted
from the amount they actually pay. Example: donate ₹10,000 with a ₹500 coupon → pay ₹9,500.

Current flow (Razorpay, INR):
`public/show.blade.php` (campaign detail, donate form) → `POST /donate/{campaign}` =
`PaymentController@redirectToPayment` (stores `amount` in session) → `GET /payment/{campaign}` =
`PaymentController@paymentPage` (creates a **Razorpay order for the full `amount`**, creates a
pending `Donation`) → Razorpay charges donor → `payment.verify` / `payment.webhook` marks
`Donation` completed → DB trigger `trg_donation_raised_amount_update` sums `donations.total_amount`
into `campaigns.raised_amount`. Platform fee = 5% of `total_amount`.

**Hard constraint:** Razorpay collects exactly `total_amount`, and fee + `raised_amount` derive from it.
A coupon must reduce the amount Razorpay charges.

## Decisions (confirmed with user)
1. **Model:** Flexible. `coupons` table with nullable `user_id` (null = public promo) and nullable
   `campaign_id` (null = any campaign). Separate `coupon_redemptions` table for per-use audit.
2. **Discount types:** Both `fixed` (e.g. ₹500) and `percent` (e.g. 10%, with `max_discount` cap).
3. **Amount recorded:** `Donation.total_amount` = amount actually paid (₹9,500). Fee = 5% of paid
   amount. `raised_amount` reflects ₹9,500. Additionally store `original_amount` (₹10,000),
   `discount_amount` (₹500), `coupon_id`, `coupon_code` for a full audit trail / receipt.
4. **Admin:** Build an Admin CRUD (list/create/edit) for coupons, add a **"Coupons" sidebar entry**
   in `layouts/admin.blade.php` following the existing Volunteers pattern, plus a `CouponSeeder`.
5. **Scope:** Coupon applies to the **one-time** donate tab only (recurring/weekly/monthly excluded
   for now — different route `recurring.store`). Product donations excluded.

## Schema (new migrations)
**`coupons`**
- `id`, `code` string unique, `user_id` nullable FK→users (null=public)
- `campaign_id` nullable FK→campaigns (null=any)
- `discount_type` enum('fixed','percent'), `discount_value` decimal(12,2)
- `min_amount` decimal(12,2) nullable, `max_discount` decimal(12,2) nullable (cap for percent)
- `usage_limit` int nullable (null=unlimited), `used_count` int default 0
- `expires_at` timestamp nullable, `is_active` bool default true
- `redeemed_at` timestamp nullable (single-use flag for assigned coupons)
- timestamps

**`coupon_redemptions`**
- `id`, `coupon_id` FK→coupons, `user_id` FK→users, `donation_id` FK→donations
- `discount_amount` decimal(12,2), `created_at` (no `updated_at` needed)

**`donations` (add columns)**
- `original_amount` decimal(12,2) nullable (intended amount; = total_amount when no coupon)
- `discount_amount` decimal(12,2) default 0
- `coupon_id` nullable FK→coupons, `coupon_code` string nullable

## Models
- `App\Models\Coupon` — fillable, casts (`discount_type` enum, `is_active` bool, `expires_at` datetime),
  relations `user()`, `campaign()`, `redemptions()`.
  Helper `computeDiscount(float $amount): float` → fixed: `min(value, amount-1)`;
  percent: `min(amount*value/100, max_discount ?? INF)`, then ensure `< amount` (min paid ₹1).
  Helper `isValidFor(?User $user, ?Campaign $campaign, float $amount): array` returning
  `[valid, message]` checking active / not expired / user match (null or own) / campaign match
  (null or same) / `amount >= min_amount` / `used_count < usage_limit` /
  per-user not already redeemed (no `coupon_redemptions` row for coupon+user) / assigned not `redeemed_at`.
- `App\Models\CouponRedemption` — fillable, relations.

## Coupon service / validation
Create `App\Services\CouponService` (or methods on `Coupon`) with
`validate(string $code, ?User $user, ?Campaign $campaign, float $amount): array`
→ `[valid, discount_amount, discounted_total, message, coupon]`.
**Never trust a client-supplied discount** — always recompute from `code` at every step.

## Integration points
1. **Route** (web): `POST /coupon/validate` → `CouponController@validate` (or `PaymentController@validateCoupon`),
   auth middleware. Returns JSON `{valid, discount_amount, discounted_total, message}` for the
   campaign-page "Apply" button.
2. **`public/show.blade.php`** (campaign detail, one-time tab): add a coupon input + "Apply" button
   near `amtOnce` (form at ~line 1610). JS calls `/coupon/validate`, shows the discounted total,
   and includes `coupon_code` in the submitted form.
3. **`PaymentController@redirectToPayment`**: accept `coupon_code`; if present, re-validate
   server-side; on success store in session:
   `donation_amount` = discounted total, `donation_original_amount` = entered amount,
   `donation_discount`, `donation_coupon_code`, `donation_coupon_id`. Validate discounted amount
   against MIN(1)/MAX(500000). Keep existing rate-limit / campaign-state checks.
4. **`PaymentController@paymentPage`**: re-validate the coupon from session (recompute discount,
   do **not** trust stored discount value). Create Razorpay order for the discounted `amount`.
   Persist `Donation` with `total_amount` = paid, `original_amount`, `discount_amount`,
   `coupon_id`, `coupon_code`. If coupon re-validation fails here, fall back to `original_amount`
   (no discount) rather than blocking the donation.
5. **`PaymentController@verify` AND `handlePaymentCaptured` (webhook)**: on successful payment,
   inside the existing DB transaction + distributed lock, **redeem** the coupon:
   - lock the `coupon` row (`lockForUpdate`)
   - re-validate (expiry / usage / per-user)
   - increment `used_count`; if assigned single-use set `redeemed_at`
   - insert a `coupon_redemptions` row (coupon_id, user_id, donation_id, discount_amount)
   - guard: skip if a redemption already exists for this `donation_id` (idempotent)
   On payment failure, **do not** redeem (coupon stays usable).
6. **`payment/index.blade.php`**: show discount summary (Original / Coupon / Paid) when
   `discount_amount > 0`; Razorpay button already uses `$amount` (now discounted) — correct.
7. **`DonationReceiptMail`**: show discount line when `discount_amount > 0`.

## Admin CRUD (coupons)
- `routes/admin/coupons.php` — mirror `routes/admin/volunteers.php`: `index`, `create`, `store`,
  `edit`, `update`, `destroy` (deactivate via `is_active=false` rather than hard delete).
- `App\Http\Controllers\Admin\CouponController` — follow `VolunteerController` patterns;
  validate `code` (unique), `discount_type`, `discount_value`, optional `user_id`/`campaign_id`,
  `min_amount`, `max_discount`, `usage_limit`, `expires_at`.
- Views `resources/views/admin/coupons/{index,create,edit}.blade.php` — reuse admin styling
  (hero, sec-ttl, tables, forms). Each index blade sets `@section('sidebar_coupons','active')`.
- **Sidebar** (`resources/views/layouts/admin.blade.php`, after the Volunteers block ~line 118):
  add
  ```blade
  <div class="s-section">Coupons</div>
  <a href="{{ route('admin.coupons.index') }}" class="s-link @yield('sidebar_coupons')">All Coupons</a>
  ```
- `database/seeders/CouponSeeder` — insert one user-assigned fixed ₹500 coupon and one public
  percent coupon, for manual testing.

## Edge cases / security
- Discount ≥ amount → reject at apply + at `redirectToPayment` (min paid ₹1).
- Coupon expired / over limit / wrong user between apply and pay → re-checked at `paymentPage`;
  fall back to original amount (no discount).
- Replay / double-redeem → coupon row lock + unique redemption row + idempotency on `donation_id`.
- Client tampering → discount always recomputed from `code` server-side (3 checkpoints).
- Razorpay only ever collects `total_amount` (paid), so money stays consistent end-to-end.

## Validation / testing
- `php artisan migrate` + `db:seed --class=CouponSeeder`.
- Manual: log in as the seeded coupon user → open a campaign → enter ₹10,000 → Apply ₹500 code →
  see ₹9,500 → pay in Razorpay test mode → confirm `donations` row has original=10000,
  discount=500, total=9500, coupon_id/code set; `raised_amount` += 9500; fee = 5% of 9500;
  coupon `used_count` incremented + `redeemed_at` set; `coupon_redemptions` row created;
  reusing the same code is rejected.
- Feature test for `CouponService`: expired, wrong user, over usage_limit, percent cap,
  discount≥amount, public vs assigned.
- No Vite rebuild needed (coupon UI is inline Blade/JS).

## Out of scope (future task)
- Recurring (weekly/monthly) coupon support.
- Product-donation coupons.
- Automatic coupon generation / bulk issuance, analytics dashboard.
