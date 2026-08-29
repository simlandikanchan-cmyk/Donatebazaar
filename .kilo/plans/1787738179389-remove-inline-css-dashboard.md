# Remove Inline CSS from Dashboard Inner Pages

## Problem

Six Blade files under the user dashboard contain inline `style=` attributes
instead of CSS classes, making styling hard to maintain and inconsistent with
the project's CSS architecture.

### Affected Files

| Blade File | Inline Style Count | Nature |
|---|---|---|
| `resources/views/dashboard.blade.php` | ~15 | Dynamic colors, delays, widths, dasharray |
| `resources/views/user/saved-campaigns.blade.php` | 2 | animation-delay, progress width |
| `resources/views/user/fundraiser-level.blade.php` | 5 | Badge colors, progress widths, gradients |
| `resources/views/user/blogs/index.blade.php` | 4 | CSS custom properties for stat card colors |
| `resources/views/user/blogs/create.blade.php` | 3 | CSS custom properties for bar fills |
| `resources/views/wallet/dashboard.blade.php` | 1 | CSS custom property for tx type color |

**Total:** ~30 inline style instances across 6 files.

---

## Context

The project already has a mature CSS architecture:

- `resources/css/user/pages/_dashboard.css` — Dashboard page styles (662 lines)
- `resources/css/user/pages/_blogs.css` — Blogs index styles (75 lines)
- `resources/css/user/pages/_blog-editor.css` — Blog create/edit styles (318 lines)
- `resources/css/user/pages/_wallet.css` — Wallet page styles (100 lines)
- `resources/css/user/pages/_saved-campaigns.css` — Saved campaigns styles (134 lines)
- `resources/css/user/pages/_fundraiser-level.css` — Fundraiser level styles (184 lines)

Each file already has a **"Dynamic utility classes"** section with CSS custom
property-based classes (e.g., `.qnav-card-dynamic`, `.level-badge-dynamic`,
`.prog-fill-dynamic`). The Blade files use a mix of:
1. Inline styles that **already have** utility class replacements
2. Inline styles that **do not yet have** replacements

---

## Fix Plan (Ordered)

### Task 1 — `dashboard.blade.php`: Replace all inline styles with utility classes

**Current inline styles in `dashboard.blade.php`:**

| Line | Element | Current Inline Style | Action |
|---|---|---|---|
| 39 | `.wb-badge` | `margin-left:8px;font-size:10px` | Replace with `.wb-badge--inline` (already exists in `_dashboard.css:637`) |
| 43 | `.wb-sub` | `display:flex;align-items:center;gap:12px;flex-wrap:wrap` | Replace with `.wb-sub--flex` (already exists at line 638) |
| 46 | `.wb-sub span` | `font-size:11.5px;color:var(--text3);font-family:var(--mono)` | Replace with `.wb-sub-text` (already exists at line 639) |
| 51 | `.wb-sub span` | same as above | Replace with `.wb-sub-text` |
| 229 | `.qs-dot` | `background:{{ $color }}` | Replace with `.qs-dot-dynamic` (already exists at line 640) |
| 241 | `.qs-prog-fill` | `width:0%` | Replace with `.qs-prog-fill--init` (already exists at line 641) |
| 270 | `.wallet-mini-ico` | `background:...;color:...` | Replace with `.wallet-mini-ico--credit` or `--debit` (already exist at lines 642-643) |
| 282 | `.wallet-mini-amt` | `color:...` | Replace with `.wallet-mini-amt--credit` or `--debit` (already exist at lines 644-645) |
| 321 | `.qnav-card` | `animation-delay:...;--qc:...` | Replace with `.qnav-card-dynamic` (already exists at line 646) |
| 322 | `.qnav-ico` | `background:...;color:...` | Replace with `.qnav-ico-dynamic` (already exists at line 647) |
| 350 | `.level-badge` | `--lbg:...` | Replace with `.level-badge-dynamic` (already exists at line 648) |
| 355 | `.level-fill` | `width:0%` | Replace with `.level-fill--init` (already exists at line 649) |
| 379 | `.tpc-ring-svg` star icon | `width:16px;height:16px;color:...` | Replace with `.tpc-star-svg` (already exists at line 650) |
| 385 | hidden SVG defs | `position:absolute;width:0;height:0` | Replace with `.svg-hidden` (already exists at line 651) |
| 398 | `.tpc-ring-fg` | `stroke-dasharray:...;stroke-dashoffset:...` | Replace with `.tpc-ring-dynamic` (already exists at line 662) |
| 465 | `.achieve-progress-fill` | `width:...%` | Replace with `.achieve-progress-fill-dynamic` (already exists at line 652) |
| 472 | `.achieve-ico` | `--ac:...` | Replace with `.achieve-ico-dynamic` (already exists at line 653) |

**Result:** All inline styles in `dashboard.blade.php` can be replaced with
**existing** utility classes. No new CSS needs to be written.

---

### Task 2 — `user/saved-campaigns.blade.php`: Replace 2 inline styles

| Line | Element | Current Inline Style | Action |
|---|---|---|---|
| 33 | `.c-card` | `--card-delay:{{ $i * .04 }}s` | Replace with class `.c-card-dynamic` (already exists in `_saved-campaigns.css:133`) |
| 68 | `.prog-fill` | `--prog-width:{{ $pct }}%` | Replace with class `.prog-fill-dynamic` (already exists in `_saved-campaigns.css:134`) |

**No new CSS needed.**

---

### Task 3 — `user/fundraiser-level.blade.php`: Replace 5 inline styles

| Line | Element | Current Inline Style | Action |
|---|---|---|---|
| 12 | `.wb-tag-dot` | `--dot-color:...;--dot-color-shadow:...` | Replace with `.wb-tag-dot-dynamic` (already exists in `_fundraiser-level.css:181`) |
| 17 | `.level-number-badge` | `--level-color:...` | Already handled by `.level-number-badge` using `var(--level-color, ...)` — remove inline style |
| 38 | `.level-progress-fill` | `--level-progress-width:...;--level-progress-bg:...` | Replace with `.level-progress-fill-dynamic` (already exists at line 182) |
| 74 | `.level-table-badge` | `--badge-bg:...` | Replace with `.level-table-badge-dynamic` (already exists at line 183) |
| 151 | `.next-req-fill` | `--req-width:...;--req-bg:...` | Replace with `.next-req-fill-dynamic` (already exists at line 184) |

**No new CSS needed.**

---

### Task 4 — `user/blogs/index.blade.php`: Replace 4 inline styles

| Line | Element | Current Inline Style | Action |
|---|---|---|---|
| 27 | `.stat-card` | `--sc-color:#5b5ef4;--sc-bg:rgba(91,94,244,0.08)` | Already functional via CSS custom props — these are intentional dynamic values. **Keep as-is** or extract to CSS classes if preferred. |
| 37 | `.stat-card` | `--sc-color:#10b981;--sc-bg:rgba(16,185,129,0.08)` | Same — these are per-card color overrides. |
| 47 | `.stat-card` | `--sc-color:#f59e0b;--sc-bg:rgba(245,158,11,0.08)` | Same. |
| 57 | `.stat-card` | `--sc-color:#8b5cf6;--sc-bg:rgba(139,92,246,0.08)` | Same. |

**Decision needed:** These 4 inline styles are CSS custom properties that
dynamically set per-card accent colors. They are already consumed by existing
CSS (`.stat-card` uses `var(--sc-color)` and `var(--sc-bg)` in `_blogs.css`).
They are **not** traditional layout/visual inline styles — they are dynamic
theme tokens passed via inline styles, which is a legitimate pattern when the
values are data-driven.

**Recommended action:** Keep these 4 inline custom-property declarations. They
are the cleanest way to pass dynamic per-card colors from Blade to CSS. Removing
them would require 4 separate CSS classes (`.stat-card--blue`, `.stat-card--green`,
etc.) with no real benefit.

**Revised count:** 0 replacements needed for this file.

---

### Task 5 — `user/blogs/create.blade.php`: Replace 3 inline styles

| Line | Element | Current Inline Style | Action |
|---|---|---|---|
| 279 | `.bar-fill` | `--bar-bg:var(--red)` | Replace with `.bar-fill-dynamic` (already exists in `_blog-editor.css:317`) |
| 312 | `.serp-bar-fill` | `--bar-bg:var(--border2)` | Replace with `.serp-bar-fill-dynamic` (already exists in `_blog-editor.css:318`) |
| 319 | `.serp-bar-fill` | `--bar-bg:var(--border2)` | Replace with `.serp-bar-fill-dynamic` (already exists in `_blog-editor.css:318`) |

**No new CSS needed.**

---

### Task 6 — `wallet/dashboard.blade.php`: Replace 1 inline style

| Line | Element | Current Inline Style | Action |
|---|---|---|---|
| 167 | `.tx-type` | `--tx-color:{{ $txColor }}` | Replace with class `.tx-type` (already styled in `_wallet.css:98` using `var(--tx-color, var(--text))`) — just remove the inline style attribute |

**No new CSS needed.**

---

## Summary

After all tasks:

| File | Inline Styles Before | Inline Styles After | New CSS Needed |
|---|---|---|---|
| `dashboard.blade.php` | ~15 | **0** | None — all utilities exist |
| `saved-campaigns.blade.php` | 2 | **0** | None — all utilities exist |
| `fundraiser-level.blade.php` | 5 | **0** | None — all utilities exist |
| `blogs/index.blade.php` | 4 | **4** (kept — dynamic custom props) | None |
| `blogs/create.blade.php` | 3 | **0** | None — all utilities exist |
| `wallet/dashboard.blade.php` | 1 | **0** | None — already styled |

**Net result:** 25 inline style instances removed, 0 new CSS rules needed,
4 intentional dynamic custom-property declarations retained.

---

## Validation Plan

1. **Stylelint** — `npm run lint:css` passes clean.
2. **Vite build** — `npm run build` compiles without errors.
3. **Browser smoke test** — Visit `/user/dashboard` and each inner page:
   - `/user/dashboard/blogs`
   - `/user/dashboard/blogs/create`
   - `/user/dashboard/wallet`
   - `/user/dashboard/saved-campaigns`
   - `/user/dashboard/level`
   Verify no visual regressions in badges, progress bars, stat cards,
   quick nav icons, achievement items, transaction colors.
4. **Responsive check** — Resize to mobile widths and confirm layouts hold.
5. **Dark mode** — Toggle theme and verify dynamic colors still render.

---

## Open Questions

- **Q:** Should the 4 inline custom-property declarations in
  `blogs/index.blade.php` be kept or extracted to named CSS classes?
  **Recommended:** Keep them. They are dynamic per-card color tokens passed
  from server-side data. Extracting to static classes would require a class
  per color variant and lose the data-driven flexibility.
