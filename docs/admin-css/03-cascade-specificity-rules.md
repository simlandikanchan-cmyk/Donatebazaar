# Admin CSS Refactor — Cascade & Specificity Rules

These rules were established during the migration and remain load-bearing. Do not reorder or restructure without re-running verification.

## 1. Page import order is the priority mechanism

The layout emits a plain `<body>` with no `page-*` class, so page files are ordered deliberately (dashboard → campaigns → … → misc). When the same selector exists in two page files, the later file wins. If you add a new page file, append it in the correct logical slot, then re-run `final_check.ps1` and a build.

## 2. Shared vs. page-specific rules

Shared component rules belong in `components/`; page-specific rules belong in `pages/`. Tail-append fixes were placed next to their closest family (e.g. `.prog-wrap` in `pages/_events.css`, `.btn-destructive` in `pages/_categories.css`). Do not move them into `components/` without checking every page that uses them.

## 3. Duplication guardrails

- `cp-bulk-*` buttons are defined in `components/_toolbar.css` — do not redefine in `pages/_categories.css` or `pages/_finance.css`.
- `.btn--*` canonical variants live in `components/_buttons.css`; legacy `btn-*` classes map to CSS custom properties there (`.btn-primary`, `.bulk-btn`, `.btn-red`, `.action-btn`, etc.).
- Breadcrumbs live in `components/_tabs.css` (comment: "Tabs & Breadcrumbs"). Do not create a separate `_breadcrumbs.css` — an earlier attempt was reverted.

## 4. No `!important`, no page-scoped hacks

The session decision stands: minimal, component-reusing styles per custom class in the correct page file.

## 5. `_responsive.css` is last

All media queries (≤1400, ≤1200, ≤960, ≤640, ≤480, ≤380) live in `layout/_responsive.css`. Page files must not add their own `@media` blocks.

## Verification commands

```
npm run build
```
Must succeed; check that `public/build/assets/admin-*.css` exists.

```
powershell -NoProfile -ExecutionPolicy Bypass -File final_check.ps1
```
Full tree scan. Expected result: 0 undefined custom classes (only documented utility/JS-hook names may appear).

## Known gaps (accepted debt, not regressions)

- Tailwind utilities in a few views (`bg-*`, `text-gray-*`, `border-*`, `rounded-*`, `shadow-*`, `items-start`, `line-clamp-2`, etc.) — no Tailwind build for admin.
- Bootstrap legacy grid: `row`, `col-md-6`, `col-md-12` (category-products index).
- JS-hook classes (`js-approve`, `js-feature`, `js-archive`, `open` on overlays) — hooks, not styling.
- FontAwesome `fa`/`fa-heart` in one dashboard card — dead with the deleted yoyo view; harmless.
