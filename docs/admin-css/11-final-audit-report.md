# Admin CSS Refactor — Final Audit Report (Phase 14)

## 1. Scope & methodology

Phase 8 (`08-verification-report.md`) proved zero undefined custom classes across the whole admin tree. This audit is stricter: it verifies **per-bundle coverage** — every class used by a page must be defined in the exact CSS that page loads. The admin layout loads exactly `entries/core.css` plus the page's single `@stack('page_css')` entry, verified by crawling.

**Audit tooling** (`audit2/`):

- `crawl.ps1` — logs in via the admin account (POST `/login` → 302), crawls 74 admin URLs, records HTTP status and every `<link rel="stylesheet">`/`@vite` CSS URL per page.
- `coverage.ps1` — downloads each page's CSS bundles, concatenates them, and reports every `class="…"` token whose `.class` selector is absent from the concatenated CSS.

## 2. Crawl results

Every reachable page returns **200** and loads **exactly 1 core bundle + 1 page entry** — no missing CSS links, no duplicate bundles.

- `/admin` root → 404 (no route registered; navigation is sidebar-driven — not an issue).
- `/admin/faqs/1/edit` → 404 (no FAQ rows seeded — not an issue).
- `/admin/fundraiser-levels/1` → **500 pre-existing PHP error** (controller/view bug, unrelated to CSS — see §6).
- `categories/1` and `category-products/1` are gone (routes removed — 404 by design).
- `/admin/campaign/99/quick`, exports → 200, no CSS (JSON/CSV/partial endpoints — partial is injected into the campaigns page context).

## 3. Gaps found and fixed

Coverage pass 1 flagged genuinely unstyled or wrong-bundle classes. All were fixed by adding the missing rules to the correct bundle, compiled and verified present in `public/build/assets/*.css`:

| File | Added |
|---|---|
| `components/_action-buttons.css` | `.act-bell.is-on` (green active state) |
| `components/_badges.css` | `.pill-active/.pill-completed`, `.pill-cancelled`, `.pill-expired`, `.b-redeemed .badge-dot`, `.pri-high`, `.pri-medium`, `.pri-low` |
| `components/_cards.css` | `.ic-green`, `.ic-yellow` |
| `components/_modals.css` | `.modal-del`, `.qk-kyc-not-submitted` |
| `components/_toolbar.css` | `.filter-clear` |
| `components/_campaign-cards.css` | `.prog-pct--active` |
| `utilities/_colors.css` | `.green` |
| `pages/_events.css` | `.status-banner.sb-active/-pending/-draft/-cancelled/-expired/-completed` (scoped under `.status-banner` to avoid clash with the sidebar-block `.sb-draft`), `.sp-dot`, `.upload-zone/.upload-icon`, full `.cat-*` category picker set + `.no-campaigns` |
| `pages/_jobs.css` | `.field-row`, `.field-hint`, `.char-counter`, `.prev-desc`, `.submit-info`, `.submit-btns`, `.modal-overlay/-desc/-btns` |
| `pages/_misc.css` | `.editor-toolbar/-content/-footer/-divider`, `.meta-row` + `.meta-lbl/.meta-val`, `.sb-custom`, `.ap-yes/.ap-no` |
| `pages/_blogs.css` | `.f-pos/.f-handle/.f-info/.f-name/.f-meta/.f-btn/.f-remove` (carousel), `.field-label/.field-input/.field-hint`, `.toggle-desc`, `.desc-count`, `.char-count/.char-counter`, `.cover-wrap`, `.fill-blue/-yellow/-green/-pink` |
| `pages/_campaigns.css` | `.char-count`, `.cover-preview-wrap` |
| `pages/_applications.css` | `.hero-title/.hero-meta/.hero-meta-item`, `.timeline` + `.tl-*` set (was only in jobs bundle) |
| `pages/_finance.css` | `.chart-card/-hdr/-ttl/-sub` (was only in dashboard bundle) |
| `pages/_organizations.css` | `.form-lbl` (partnerships forms) |
| `pages/_messages.css` | `.msg-hero-relative`, `.msg-meta-card`/`.msg-actions-card` sidebar-body spacing, `.reply-open/-send/-cancel` |

Nothing was duplicated into a second bundle unless its consumer page loads only that bundle (field-error/field-select/field-textarea stay core-only; `.qk-*` stays core as the quick-view partial renders inside the campaigns page context).

## 4. Final coverage pass — remaining flags

Coverage pass 2 flags on every page are **false positives**, all explained:

| Class | Why it's fine |
|---|---|
| `sidebar-overlay` (all pages) | Blade uses `id="sidebarOverlay"`; styled via ID selector `#sidebarOverlay` in `layout/_sidebar.css:15` (z-index 399). String check for `.sidebar-overlay` cannot see an ID rule. |
| `lbl` (events index) | Covered by `.ev-card-stat span:first-child` (events.css) — class is a semantic marker. |
| `f-up`, `f-down` (blogs/carousel) | Pure arrow markers on buttons fully styled by `.f-btn` (blogs.css). |
| `js-feature`, `js-archive` (blogs) | JS hooks — documented skip category since Phase 8. |
| `fa-*` (categories, 404 pages) | FontAwesome CDN (loaded globally). |
| Tailwind utilities (`text-gray-*`, `bg-*`, `flex-*`, `dark:*`, …) on 404/error/`blogs/pending`/`contacts` pages | Those pages render the public layout or no admin layout; the admin layout loads no Tailwind, so these were never admin-styled — pre-existing, out of scope. |
| `qk-*`, `c-*`, `badge`, `prog-bar`, … (quick view partial URL) | Partial endpoint; rendered inside the campaigns page context which loads core + campaigns bundle. |
| `hasCode`, `hasNext`, `page.value`, … (fundraiser-levels/1) | Class name fragments extracted from the 500 error page's stack trace — not real CSS classes. |

## 5. Score

Per the Phase 8 rubric (whole-tree: **10/10**), the stricter per-bundle audit now yields:

- **Loading integrity: 10/10** — 74/74 URLs correct status, 1 core + 1 page bundle everywhere, 0 missing/404 CSS links.
- **Per-bundle coverage: 9.9/10** — 0 genuinely undefined classes; every remaining flag is an explained false positive (ID-selector styling, JS hooks, external frameworks, or error-page artifacts).
- **Ownership: 10/10** — no cross-bundle imports; each page bundle is self-sufficient; shared styles live only in core.

**Final: 9.9/10.**

## 6. Pre-existing non-CSS issues (out of scope, reported)

1. `GET /admin/fundraiser-levels/1` → **500** — PHP error in the show route (present before this refactor; not CSS-related). Recommend investigating `FundraiserLevelController@show` / its view.
2. `contacts` and `blogs/pending` pages contain Tailwind classes rendered **without any Tailwind build** — they were always unstyled; either remove the classes or add Tailwind to those bundles (follow-up decision, not a regression).
3. `faqs/1/edit` 404 — no FAQ rows seeded; page itself is fine once data exists.

## Re-run procedure

```
npm run build
powershell -NoProfile -ExecutionPolicy Bypass -File audit2\coverage.ps1
```

Expected: no `== 200 ==` page flags other than the false-positive list in §4; 404/500 pages flag only external/error artifacts.
