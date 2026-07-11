# Admin Dashboard Typography Review & Fix Plan

## Verdict
The admin dashboard typography is **not** perfect. Foundation is good (DM Sans + DM Mono tokens, antialiasing, responsive scaling), but there are real UI/UX defects: (1) font weights that are requested in CSS but never loaded, so they silently fall back; (2) DM Mono (monospace) misused for large display headings, hurting polish/readability; (3) tiny low-contrast labels; (4) a dead font load and minor CSS sloppiness.

**Agreed direction:** Reserve DM Mono for data/numbers/labels only; use DM Sans for all display/UI headings.

## Scope
- **Phase 1 (core/dashboard):** `resources/css/admin.css` + font `<link>` in `resources/views/layouts/admin.blade.php`. This fixes the dashboard and every page that uses the shared base classes (`.hero`, `.stat`, `.sec-hdr`, `.chart-*`, `.sp-*`, `.modal-*`, sidebar, topbar, table).
- **Phase 2 (page-level):** Audit inline `<style>` blocks in `resources/views/admin/**/*.blade.php` that reuse `var(--mono)` for non-data titles, and convert the display ones to `var(--font)`.

---

## Phase 1 — Shared base (dashboard + global)

### 1. Fix the Google Fonts import
File: `resources/views/layouts/admin.blade.php:9`
Current loads `Syne` (never used) and DM Sans up to 700 / DM Mono up to 500.
Replace with:
```
https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&family=DM+Mono:wght@300;400;500;600;700&display=swap
```
- Drops unused `Syne` (saves bandwidth).
- Adds **DM Sans 800** (used by `.s-logo-name`, `.stat-val`, `.hero-name`).
- Adds **DM Mono 600/700** (used by many data labels/badges).

### 2. Switch display headings from mono → sans in `admin.css`
Change `font-family:var(--mono)` → `font-family:var(--font)` on:
- `:63` `.s-logo-name`
- `:106` `.tb-left h1`
- `:139` `.dd-name`
- `:202` `.sec-ttl`
- `:275` `.hero-name` (keep the gradient `background-clip:text`; from `-.03em` keep)
- `:309` `.chart-ttl`
- `:318` `.sp-ttl`
- `:355` `.modal-ttl`
- `:387` `#noResults strong`

Keep `var(--mono)` (correct use) on: `.badge`, `.s-chip`, `.stat-lbl`, `.stat-val`, `.s-section`, `.hero-tag`, `.s-logo-tag`, `.s-admin-role`, `.reason-lbl`, `.prog-raised/.prog-goal/.prog-pct`, `.chart-sub`, `.leg-item`, `.ftab .cnt`, `.p-table th`, `.qk-stats b`, `.hero-badge`, `.t-av`, `.dd-email`.

(No weight changes needed in CSS now that the weights are loaded.)

### 3. Minor CSS hygiene in `admin.css`
- `:11` `--a:#2563eb ;` → remove trailing space → `--a:#2563eb;`
- `:39` body: add `text-rendering:optimizeLegibility;-moz-osx-font-smoothing:grayscale;` after `-webkit-font-smoothing:antialiased;`

### 4. Accessibility — tiny low-contrast labels
- `:78` `.s-section` (9px, `--text3` #9096b4 ≈ 3:1) → `font-size:10px;color:var(--text2);`
- `:194` `.stat-lbl` (10px, `--text3`) → `color:var(--text2);`
- Other 9–10px `--text3` mono labels (e.g. `.status-section-label`, `.bank-field-lbl` in page styles) are flagged in Phase 2 for the same legibility review.

---

## Phase 2 — Page-level inline styles
Many admin blade files embed `<style>` that reuse `var(--mono)` for display titles. Apply the same rule: **mono only for data/numbers/labels**.

Grep target: `resources/views/admin/**/*.blade.php` for `font-family:var(--mono)` (and `var(--mono)`).

Convert these display/title selectors to `var(--font)`:
- `categories/index.blade.php` — `.card-head-title`, `.modal h3`
- `categories/edit.blade.php` & `create.blade.php` — `.modal h3`, `.card-head-title`, `.prev-name` (category name preview), `.fundraiser-name`-style names
- `campaign/show.blade.php` — `.card-title`, `.cover-title`, `.modal-title`, `.event-title`, `.fundraiser-name`, `.update-item-title`, `.crumbs` (breadcrumb); **keep mono** on `.prog-raised`, `.mini-stat-val`, `.fund-raised`, `.fund-ring-pct`, `.bank-field-val`, serial/slug pills (these are data)
- Repeat the same audit for every other admin blade (`events/*`, `blogs/*`, `gift-cards/*`, `applications/*`, `organizations/*`, `job_posts/*`, `partnerships/*`, `messages/*`, `dashboard`).

Rule of thumb for the implementer: if the text is a **heading, title, name, or sentence**, use `var(--font)`; if it is a **number, code, slug, count, status, or uppercase micro-label**, `var(--mono)` is fine.

---

## Validation
1. `npm run build` (Vite) — confirm no CSS/build errors.
2. Load `/admin/dashboard` in light + dark:
   - Headings (hero name, page title, section titles, modal titles, sidebar logo) render in **DM Sans** (proportional, not fixed-width).
   - Numbers/stat values/badges/counts render in **DM Mono**.
   - Bold weights (800 headings, 700 labels) actually render bold (not faux-clamped).
3. Verify in DevTools Network that `DM+Sans` …`800` and `DM+Mono` …`700` are requested (no 400-only fallback).
4. Check the removed `Syne` request is gone.
5. Legibility spot-check of `.s-section` / `.stat-lbl` at 10px in `--text2` (AA-ish contrast).
6. Optional: run Lighthouse/axe on the dashboard to confirm no new contrast regressions.

## Risks / Notes
- Phase 2 is broad; converting page styles is mechanical but should be done in one pass per file to keep visual consistency. Avoid half-converting a page.
- Do not remove `Syne` from any *other* layout (public site may use it) — only the admin `<link>`.
- Keep `-webkit-text-fill-color:transparent` gradient on `.hero-name` intact when switching its font-family.
