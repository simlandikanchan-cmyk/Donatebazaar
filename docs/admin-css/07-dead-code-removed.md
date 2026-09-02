# Admin CSS Refactor — Dead Code Removed

## Views

| File | Why dead | Proof |
|---|---|---|
| `resources/views/admin/dashboard_yoyo.blade.php` | No route renders it | `route:list` / grep for `dashboard_yoyo` in routes — zero matches |

Its ~120 unique classes (`main-layout`, `side-panel`, `donut-wrap`, `topbar-*`, `theme-toggle*`, `t-user*`, `avatar-dropdown`, `s-upgrade*`, `c-*` cluster, `toast-container`, `sp-rows`, `chip-y/r`, `reason-y/r`, `js-approve/feature/archive`, `stat-icon-*`, `charts-row`, `chart-*`) were deliberately not defined in the new CSS — they died with the view. `final_check.ps1` excludes this file.

## CSS files (19 legacy flat files removed)

`_pages.css`, `_layout.css`, `_tables.css`, `_campaigns.css`, `_dashboard_stats.css`, `_colors.css`, `_badges.css`, `_buttons.css` (after restore), `_dashboard_charts.css`, `_dashboard_hero.css`, `_dashboard_impact.css`, `_dashboard_quicknav.css`, `_forms.css`, `_modals.css`, `_sidebar.css`, `_topbar.css`, `_utilities.css`, `components/_buttons.css` (old duplicate), and the never-imported `components/_breadcrumbs.css` (created and reverted this session).

## Inline `<style>` blocks

- `resources/views/admin/coupons/index.blade.php` (26 classes) — removed; classes now live in `pages/_finance.css`.
- `resources/views/admin/gift-cards/index.blade.php` (29 classes) — removed; classes now live in `pages/_finance.css`.

Both were verified class-by-class (`inline_check.ps1`) before removal.

## Resulting admin CSS tree

34 files: `admin.css` + `_variables.css` + `base/` (4) + `utilities/` (2) + `layout/` (4) + `components/` (15) + `pages/` (12). All 38 imports in `admin.css` resolve.

## Left untouched (out of scope)

- `resources/css/_core.css`, `_components.css`, `_components_continued.css`, `_animations.css` (root-level, used by the old admin build and possibly the user build — not migrated, still tracked in git).
- `errors-*.css` esbuild warning (`Unexpected "*"`) — pre-existing, error-page CSS.
