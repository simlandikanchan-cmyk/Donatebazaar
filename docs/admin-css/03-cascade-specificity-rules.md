# Admin CSS Refactor — Cascade & Specificity Rules

## Load-bearing rules (do not reorder without re-verification)

1. **Pages import order is the priority mechanism.** The layout emits a plain `<body>` (no `body.page-*` class), so page files are ordered deliberately (dashboard → campaigns → … → misc). When the same selector exists in two page files, the later file wins. Keep new page files appended in the correct logical slot, and re-run `final_check.ps1` + a build.

2. **Shared/component rules live in `components/`, page-specific in `pages/`.** Tail-append fixes were placed next to their closest family (e.g. `.prog-wrap` in `pages/_events.css`, `.btn-destructive` in `pages/_categories.css`). Do not move them into `components/` without checking every usage page.

3. **Duplication guardrails** (established during migration):
   - `cp-bulk-*` buttons are defined in `components/_toolbar.css` — do NOT redefine in `pages/_categories.css` or `pages/_finance.css`.
   - `.btn--*` canonical variants live in `components/_buttons.css`; legacy `btn-*` classes are mapped to CSS custom properties there (`.btn-primary`, `.bulk-btn`, `.btn-red`, `.action-btn`, …).
   - Breadcrumbs are in `components/_tabs.css` (comment: "Tabs & Breadcrumbs"). Do not create a separate `_breadcrumbs.css` (attempted and reverted).

4. **No `!important`, no page-scoped hacks.** The earlier session decision stands: minimal component-reusing styles per custom class in the correct page file.

5. **`layout/_responsive.css` is last.** All media queries (≤1400, ≤1200, ≤960, ≤640, ≤480, ≤380) are in this single file; page files must not add their own `@media` blocks.

## Verification commands

- `npm run build` — must succeed; check `public/build/assets/admin-*.css` exists.
- `powershell -NoProfile -ExecutionPolicy Bypass -File C:\Users\stdlocal\AppData\Local\Temp\opencode\final_check.ps1` — full tree scan; expected result: 0 undefined custom classes (only documented utility/JS-hook names may appear).

## Known gaps (accepted debt, not regressions)

- Tailwind utilities in a few views (`bg-*`, `text-gray-*`, `border-*`, `rounded-*`, `shadow-*`, `items-start`, `line-clamp-2`, …) — no Tailwind build for admin.
- Bootstrap legacy grid: `row`, `col-md-6`, `col-md-12` (category-products index).
- JS-hook classes (`js-approve`, `js-feature`, `js-archive`, `open` on overlays) — hooks, not styling.
- FontAwesome `fa`/`fa-heart` in one dashboard card — dead with the deleted yoyo view; harmless.
