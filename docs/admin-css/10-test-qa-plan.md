# Admin CSS Refactor — Test & QA Plan

## Scope

Verify zero visual regression across the admin panel after the CSS reorganization. No view markup was changed except removing the two inline `<style>` blocks.

## Automated checks (all green, re-run on any change)

1. **Build:** `npm run build` — must succeed; admin CSS asset regenerates.
2. **Class coverage:** `final_check.ps1` — expected `TOTAL UNDEFINED NON-UTILITY: ≤38`, all in the documented skip list (Tailwind/Bootstrap utilities + `js-*` hooks).
3. **Import integrity:** all 38 `@import`s in `resources/css/admin/admin.css` resolve.
4. **Inline-style check:** `inline_check.ps1` — re-run if any `cp-*`/`gc-*` class is edited in `pages/_finance.css`.

## Manual smoke checklist (XAMPP at /fundraise)

| # | Page | URL | Verify |
|---|---|---|---|
| 1 | Login → Dashboard | `/admin` | hero, impact cards (`impact-revenue/donors/avg`), `hb-count`, charts, campaign table, search box (`search-wrap`/`search-inp`) |
| 2 | Campaigns index | `/admin/campaigns` | `stat-val`, `sv-paused` tint, `qk-*`, `date-input`, `filter-sep`, `cat-tag`, progress bars, bulk toolbar |
| 3 | Campaign show/create/edit | … | slug lock button, `btn-primary`, editor toolbar, toggles, upload boxes, stepper |
| 4 | Categories index/create/edit | `/admin/categories*` | `cp-bulk-*` toolbar, `preview-*` panel, `f-textarea`, `fa-box`, `cat-grid-item`, `btn-destructive`, delete overlay `.open` |
| 5 | Category-products / products | … | `bulk-btn btn-destructive btn-sm`, Bootstrap `row`/`col-md-*` grid still functional (documented debt), `.lightbox-close` |
| 6 | Donations index/show | `/admin/donations*` | `.k/.v`, `dn-processed`, refund modal, `sec-hdr/sec-ttl`, toast (`toast-wrap`, `toast-x`) |
| 7 | Settlements | … | `.sett-*`, filter bar |
| 8 | Wallets | … | `.w-*`, wallet stats |
| 9 | Coupons index | `/admin/coupons` | `cp-*` table styling (was inline `<style>`) — **must look identical** |
| 10 | Gift cards index | `/admin/gift-cards` | `gc-*` table styling (was inline `<style>`) — **must look identical** |
| 11 | Messages index/show | … | `msg-*`, conversation cards, filter bar, reply panel |
| 12 | Blogs index/create/edit | … | `sn/sl`, `prose-area`, `bar-track/fill`, `ready-*`, `sd-*`, `title-*`, `blog-thumb`, `author-*`, image lightbox |
| 13 | Events index/show | … | `ev-*`, `prog-wrap`, `prog-amt`, `participants-*`, `organizer-name`, `.val` |
| 14 | Jobs index/show/create/edit | … | `hero-strip`, `prev-*` preview rows, `preview-ttl-row`, danger zone, toast |
| 15 | Organizations index/show | … | `org-*`, `p-card accent-card`, `info-val accent`, documents, timeline |
| 16 | Applications (volunteer + job) | … | `filter-inp/sel`, `sec-hdr`, applicant table, `apr-pill`, `def-pill` |
| 17 | Levels / contacts / misc | … | `level-attrs`, `.attr`, `contact-row`, `def-pill`, `modal-body` |
| 18 | Responsive | resize to ≤1400/1200/960/640/480/380 | sidebar slide-in (`#sidebarOverlay`), grids collapse, table scroll |

## Regression watch-outs

- **`coupons/gift-cards`** — the two inline `<style>` removals: compare against screenshots taken before the change if available; otherwise spot-check stat cards, table headers (sticky), badges, action pills.
- **`x-button` variants** — every `btn--*` must still render correctly (canonical + legacy mappings).
- **Overlays** — `.open` show-state on modals/lightboxes across all pages.
- **Dashboard** — the old yoyo view was deleted; `/admin` must still be the hero-style dashboard (route unchanged).
