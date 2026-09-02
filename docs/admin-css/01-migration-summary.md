# Admin CSS Refactor — Migration Summary

## What changed

The admin panel's CSS started as 19 flat files with thousands of duplicated and dead rules, inline `<style>` blocks scattered across Blade templates, and a dead dashboard view carrying its own orphaned styles. The refactor replaced all of that with a layered, component-based tree.

**Before:** `resources/css/admin/*.css` — 19 flat files imported through `admin.css`, plus inline styles in `coupons/index` and `gift-cards/index`, plus `dashboard_yoyo` (a dead view with its own `<style>` block).

**After:** `resources/css/admin/admin.css` (entry point, 38 imports) → `base/`, `utilities/`, `layout/`, `components/`, `pages/` + `_variables.css` and `layout/_responsive.css`.

## Verified facts

- `npm run build` succeeds. Built output: `public/build/assets/admin-Bgvzs2_L.css`, 247.1 kB raw.
- The full tree-wide scan (`final_check.ps1`) confirmed that every custom class used by live admin views is defined in the new tree. The only remaining undefined names are documented dead utilities (Tailwind `bg-*`/`text-*`/`border-*`/`rounded-*`/`shadow-*`, Bootstrap `col-md-*`) and JS hooks (`js-*`).
- 646 legacy custom classes, 121 old-only rules, and the 55-class inline `<style>` pair were all migrated into the new tree.
- `dashboard_yoyo.blade.php` was deleted (dead: no route references it). Its ~120 unique classes were never defined in the new tree — correct, since they died with the view.

## Files deleted

**Legacy flat files:** `_pages.css`, `_layout.css`, `_tables.css`, `_campaigns.css`, `_dashboard_stats.css`, `_badges.css`, `_buttons.css`, `_dashboard_charts.css`, `_dashboard_hero.css`, `_dashboard_impact.css`, `_dashboard_quicknav.css`, `_forms.css`, `_modals.css`, `_sidebar.css`, `_topbar.css`, `_utilities.css`, `components/_buttons.css` (old duplicate, recreated from `_buttons.css`).

**Views:** `dashboard_yoyo.blade.php`; inline `<style>` blocks in `coupons/index.blade.php` and `gift-cards/index.blade.php`.

## Notes

- `components/_buttons.css` was accidentally deleted mid-session and restored 1:1 from the top-level `_buttons.css` (identical content; see `06-buttons-restoration.md`).
- The `errors-*.css` esbuild warning (`Unexpected "*"`) is pre-existing, in error-page CSS, and out of scope.
