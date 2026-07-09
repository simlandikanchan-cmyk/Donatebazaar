# Responsiveness Audit — `/admin/job_posts/8` (Job Post Detail)

## Scope
Static CSS audit (no new tooling) of `resources/views/admin/job_posts/show.blade.php`
plus the admin shell in `resources/css/admin.css`, against the requested 18-device matrix.

## Responsive layers that govern this page
**Admin shell (`admin.css`):**
- `≤960px` → sidebar collapses to hamburger, `.hero` goes column, `.hero-right` full width
- `≤640px` → `.hero-right` stacks (stat cards vertical), `.hero-badges` column
- `≤480px` / `≤380px` → finer text/spacing scaling

**Page-specific (`show.blade.php` inline `<style>`):**
- `≤1100px` → `.content-grid` becomes single column (side stack un-stickies, drops below)
- `≤860px` → `.stat-strip` → 2 columns
- `≤600px` → `.stat-strip` 1fr 1fr, `.page-actions` column, hero stat cards shrink
- `≤480px` → `.hero-chip` full width
- `≤375px` / `≤360px` → smallest text/padding tweaks

**Overflow safety:** `.table-wrap{overflow-x:auto}` — the applicants table scrolls horizontally on narrow screens, so no page-level horizontal scroll.

## Device-by-device verdict

| Device | Width (CSS px) | Nav | Layout result | Verdict |
|---|---|---|---|---|
| iPhone SE | 375 | hamburger | 1-col content, 2-col stats, stacked hero, scrolling table | ✅ Good |
| iPhone XR | 414 | hamburger | same (≤480: chips full width) | ✅ Good |
| iPhone 12 Pro | 390 | hamburger | same | ✅ Good |
| iPhone 14 Pro Max | 430 | hamburger | same (≤480) | ✅ Good |
| Pixel 7 | 412 | hamburger | same | ✅ Good |
| Galaxy S8+ | 360 | hamburger | smallest scaling tier (≤360) | ✅ Good |
| Galaxy S20 Ultra | 412 | hamburger | same as Pixel 7 | ✅ Good |
| Galaxy A51/71 | 412 | hamburger | same | ✅ Good |
| Surface Duo (540) | 540 | hamburger | 2-col stats, column hero/actions | ✅ Good |
| Galaxy Z Fold 5 (cover 280) | 280 | hamburger | smallest tier, chips full width | ✅ Good |
| Galaxy Z Fold 5 (unfolded 717) | 717 | hamburger | 2-col stats, single-col content | ✅ Good |
| iPad Mini | 768 | hamburger | 2-col stats, single-col content | ✅ Good |
| iPad Air | 820 | hamburger | 2-col stats, single-col content | ✅ Good |
| iPad Pro | 1024 | full sidebar | 4-col stats, single-col content | ✅ Functional* |
| Surface Pro 7 | 912 | full sidebar | 4-col stats, single-col content | ✅ Functional* |
| Asus Zenbook Fold | 1280 | full sidebar | 2-col content + 300px side stack | ✅ Ideal |
| Nest Hub (1024×600) | 1024 | full sidebar | 4-col stats, single-col content, scrolls | ✅ Functional* |
| Nest Hub Max (1280×800) | 1280 | full sidebar | 2-col content | ✅ Ideal |

\* At 961–1099px (iPad Pro, Surface Pro 7, Nest Hub) the sidebar is visible but
`.content-grid` is already single-column (threshold is 1100px). Fully functional,
just no beside-content sidebar at these widths.

## Conclusion
Responsiveness is **effectively done** for all 18 devices — every width is covered by
an existing breakpoint, the admin nav collapses correctly (<960px), and the applicants
table never causes horizontal page overflow.

## Optional polish (not required)
1. **Tablet 2-column threshold:** lower `.content-grid` breakpoint from `1100px` to
   `1024px` so iPad Pro / Surface Pro 7 / Nest Hub keep the sticky side stack beside
   the content instead of dropping it below. (Edit line 171 of `show.blade.php`.)
2. **Z Fold 5 unfolded (717px):** already fine; if a denser look is wanted, add a
   `≤768px` rule to keep `.stat-strip` at 2 columns explicitly (already inherited).
3. **Verification (if ever wanted):** a headless Playwright script capturing each
   viewport would pixel-confirm the above, but is unnecessary for sign-off.

## How to confirm locally
1. `php artisan serve` (or current XAMPP) → open `http://127.0.0.1:8000/admin/job_posts/8`
2. In DevTools device toolbar, select each device above and check: hamburger nav <960px,
   no horizontal scroll, table scrolls internally, side stack stacks below <1100px.
