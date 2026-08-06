# Admin Categories CSS — Production Readiness Audit & Fix Plan

## Problem

The admin categories CSS is **not production-ready**. Two critical CSS classes
used in the Blade templates have their base styles defined only in a CSS file
that is **not imported** by the categories CSS entry point, causing visible
rendering breakage.

### Affected views
- `resources/views/admin/categories/index.blade.php` (table + grid status toggles)
- `resources/views/admin/category-products/index.blade.php`
- `resources/views/admin/legal/edit.blade.php`

---

## Critical Issues (Production-Breaking)

### 1. `.status-badge` base styles missing on categories index

| Reference | Location |
|---|---|
| Blade usage | `resources/views/admin/categories/index.blade.php:253` |
| Blade usage | `resources/views/admin/category-products/index.blade.php:289,291` |
| Blade usage | `resources/views/admin/legal/edit.blade.php:147,149` |
| Base styles defined | `resources/css/admin/pages/products.css:22-24` |
| Imported by | `entries/products.css` only |

**Root cause:** `.status-badge`, `.status-active`, `.status-inactive` are
defined **only** in `pages/products.css`. That file is imported exclusively by
`entries/products.css` (Vite entry for campaign-product pages).

`entries/categories.css` imports `pages/categories/products.css` (a
**different** file), which does **not** contain `.status-badge` styles.
`index-table.css` only adds `:hover/:active/:focus-visible` overrides — no
base styles.

**Result:** Status toggle buttons render with zero padding, no border-radius,
no background color, no text color. Visually broken.

### 2. `.product-cell`, `.product-meta`, `.product-name` missing on categories index

| Reference | Location |
|---|---|
| Blade usage | `resources/views/admin/categories/index.blade.php:242-244` |
| Base styles defined | `resources/css/admin/pages/products.css:6-9` |

**Root cause:** These classes exist only in `pages/products.css`, not imported
by the categories CSS bundle.

**Result:** Category name + slug cells in the table view lack flex layout;
content misaligns.

### 3. `.status-badge` missing on legal/edit page

**Root cause:** `legal/edit.blade.php` loads `entries/misc.css` →
`pages/misc.css`. Neither defines `.status-badge` base styles.

**Result:** Same unstyled status badges on the legal edit page.

---

## Structural Issues

### 4. Empty directories in `core/`

```
resources/css/admin/core/components/   ← empty
resources/css/admin/core/layout/       ← empty
resources/css/admin/core/utilities/    ← empty
```

Leftover stubs from a directory migration.

### 5. Duplicate CSS definitions

`.cat-grid`, `.cat-grid-item`, `.grid-icon-box`, `.grid-check` are defined in
**both** `pages/categories/index.css` (lines 6, 12, 15, 24, 27) **and**
`pages/categories/index-grid.css` (lines 7, 14, 42, 38).

Since `index-grid.css` is imported after `index.css` in `entries/categories.css`,
the grid definitions win — the `index.css` copies are dead code.

### 6. `index-toolbar.css` stub file

Contains only a comment (7 lines) explaining toolbar styles come from shared
components. Not harmful but misleading.

### 7. Over-fragmented CSS bundle

`entries/categories.css` has 9 `@import` statements for 9 separate CSS files.
Excessive granularity for admin CSS.

---

## Fix Plan (Ordered Tasks)

### Task 1 — Fix `.status-badge` base styles (CRITICAL)

**File:** `resources/css/admin/components/_badges.css`

Move base `.status-badge` styles from `pages/products.css` into the shared
`_badges.css` (where `.status-dot`, `.dot-active`, `.dot-draft`, `.slug-pill`
already live), available on all admin pages via `core.css`:

```css
.status-badge{
  display:inline-flex;align-items:center;
  gap:5px;padding:3px 10px;
  border-radius:100px;
  font-size:11px;font-weight:700;font-family:var(--mono);
}
.status-active{
  background:var(--green-lt);
  color:var(--green);
  border:1px solid rgba(5,196,138,.2);
}
.status-inactive{
  background:var(--surface2);
  color:var(--text3);
  border:1px solid var(--border2);
}
```

### Task 2 — Fix `.product-cell`/`.product-meta`/`.product-name` (CRITICAL)

Move from `pages/products.css` into `components/_tables.css` (table-cell
utilities already live there — `.td-sub`, `.serial`, `.campaign-count`).

### Task 3 — Remove duplicates from `pages/products.css`

Remove now-duplicated `.status-badge`, `.status-active`, `.status-inactive`,
`.product-cell`, `.product-meta`, `.product-name` from `pages/products.css`.

### Task 4 — Remove empty `core/` subdirectories

Delete `core/components/`, `core/layout/`, `core/utilities/`.

### Task 5 — Eliminate duplicate CSS in categories index

Remove `.cat-grid`, `.cat-grid-item`, `.grid-icon-box`, `.grid-check` from
`pages/categories/index.css`. Keep only `.cat-row`, `.cat-toggle`,
`.cat-toggle-txt`, `.cat-icon-box`, and table-context overrides.

### Task 6 — Remove `index-toolbar.css` stub

Delete `pages/categories/index-toolbar.css` and its `@import` from
`entries/categories.css`.

### Task 7 — Consolidate stub files (optional, lower priority)

Merge `index-stats.css`, `index-skeleton.css`, `index-toolbar.css` (deleted),
`index-responsive.css` into `index.css`.

---

## Validation Plan

1. **Stylelint** — `npm run lint:css` passes clean.
2. **Vite build** — `npm run build` compiles without errors.
3. **Browser check** — Load `/admin/categories` and verify status badges
   show proper styling, name/slug cells are correctly laid out, and all
   existing UI (stats, toolbar, table, grid, skeleton, responsive) renders.
4. **Tests** — `php artisan test --filter="test_category_"` passes (14 tests).
5. **Cross-page check** — Verify `/admin/category-products` and
   `/admin/legal/edit/{id}` status badges render correctly.

## Open Questions

- **Q:** Should `.product-cell`/`.product-meta`/`.product-name` move to
  `_tables.css` (shared) or stay page-specific?
  **Recommended:** Move to `_tables.css` — generic table-cell utilities reused
  across campaigns, products, and categories.
- **Q:** Is the 9-import `categories.css` chain worth simplifying?
  **Recommended:** Consolidate the 4 smallest stub files into `index.css`.
